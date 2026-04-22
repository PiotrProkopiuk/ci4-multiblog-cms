<?php

namespace App\Libraries;

class StockImageSearch
{
    public function suggest(array $blog, string $title, string $content): array
    {
        $provider = strtolower((string) (getenv('STOCK_IMAGE_PROVIDER') ?: env('STOCK_IMAGE_PROVIDER') ?: 'pexels'));
        // $query = $this->queryFor($blog, $title, $content);
        $query = $title;
        
        return match ($provider) {
            'pixabay' => $this->pixabay($query),
            default => $this->pexels($query),
        };
    }

    private function pexels(string $query): array
    {
        $key = getenv('PEXELS_API_KEY') ?: env('PEXELS_API_KEY');

        if (! $key) {
            throw new \RuntimeException('Brakuje klucza PEXELS_API_KEY. Dodaj darmowy klucz API w sekretach albo ustaw STOCK_IMAGE_PROVIDER=pixabay i PIXABAY_API_KEY.');
        }

        $response = $this->request('https://api.pexels.com/v1/search?' . http_build_query([
            'query' => $query,
            'per_page' => 9,
            'orientation' => 'landscape',
        ]), ['Authorization: ' . $key]);

        $images = [];

        foreach (($response['photos'] ?? []) as $photo) {
            $images[] = [
                'url' => $photo['src']['large2x'] ?? $photo['src']['large'] ?? '',
                'thumb' => $photo['src']['medium'] ?? '',
                'alt' => $photo['alt'] ?: $query,
                'source' => 'Pexels',
                'author' => $photo['photographer'] ?? '',
                'credit_url' => $photo['url'] ?? '',
            ];
        }

        return ['provider' => 'pexels', 'query' => $query, 'images' => array_values(array_filter($images, static fn($image) => $image['url'] !== ''))];
    }

    private function pixabay(string $query): array
    {
        $key = getenv('PIXABAY_API_KEY') ?: env('PIXABAY_API_KEY');

        if (! $key) {
            throw new \RuntimeException('Brakuje klucza PIXABAY_API_KEY. Dodaj darmowy klucz API w sekretach albo ustaw STOCK_IMAGE_PROVIDER=pexels i PEXELS_API_KEY.');
        }

        $response = $this->request('https://pixabay.com/api/?' . http_build_query([
            'key' => $key,
            'q' => $query,
            'image_type' => 'photo',
            'orientation' => 'horizontal',
            'safesearch' => 'true',
            'per_page' => 9,
        ]));

        $images = [];

        foreach (($response['hits'] ?? []) as $photo) {
            $images[] = [
                'url' => $photo['largeImageURL'] ?? $photo['webformatURL'] ?? '',
                'thumb' => $photo['webformatURL'] ?? '',
                'alt' => $photo['tags'] ?? $query,
                'source' => 'Pixabay',
                'author' => $photo['user'] ?? '',
                'credit_url' => $photo['pageURL'] ?? '',
            ];
        }

        return ['provider' => 'pixabay', 'query' => $query, 'images' => array_values(array_filter($images, static fn($image) => $image['url'] !== ''))];
    }

    private function request(string $url, array $headers = []): array
    {
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 12,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $body = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($body === false || $status >= 400) {
            throw new \RuntimeException($error ?: 'Nie udało się pobrać zdjęć stockowych. Sprawdź klucz API lub limit zapytań.');
        }

        $decoded = json_decode((string) $body, true);

        if (! is_array($decoded)) {
            throw new \RuntimeException('Dostawca zdjęć zwrócił niepoprawną odpowiedź.');
        }

        return $decoded;
    }

    private function queryFor(array $blog, string $title, string $content): string
    {
        $text = trim(strip_tags($title . ' ' . $content));
        $words = preg_split('/[^\p{L}\p{N}]+/u', mb_strtolower($text), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $stop = ['oraz', 'który', 'która', 'które', 'jest', 'jako', 'przez', 'dla', 'jak', 'się', 'with', 'this', 'that', 'from', 'und', 'oder', 'eine', 'der', 'die', 'das'];
        $dictionary = [
            'kot' => 'cat',
            'kota' => 'cat',
            'koty' => 'cats',
            'pies' => 'dog',
            'psa' => 'dog',
            'psy' => 'dogs',
            'zwierzę' => 'pet',
            'zwierzęta' => 'pets',
            'karmienie' => 'feeding',
            'karmieniu' => 'feeding',
            'karma' => 'pet food',
            'dom' => 'home',
            'domu' => 'home',
            'ogród' => 'garden',
            'ogrodu' => 'garden',
            'rośliny' => 'plants',
            'roślina' => 'plant',
            'kwiaty' => 'flowers',
            'zdrowie' => 'health',
            'uroda' => 'beauty',
            'pielęgnacja' => 'care',
            'dbać' => 'care',
            'porady' => 'tips',
            'relaks' => 'wellness',
            'kosmetyki' => 'cosmetics',
            'garten' => 'garden',
            'haus' => 'home',
            'katze' => 'cat',
            'hund' => 'dog',
            'gesundheit' => 'health',
            'schönheit' => 'beauty',
        ];
        $keywords = [];

        foreach ($words as $word) {
            if (mb_strlen($word) < 4 || in_array($word, $stop, true)) {
                continue;
            }

            if (isset($dictionary[$word])) {
                $keywords[] = $dictionary[$word];
            } elseif (preg_match('/^[a-z0-9]+$/', $word)) {
                $keywords[] = $word;
            }

            if (count($keywords) >= 5) {
                break;
            }
        }

        $fallback = match ($blog['slug'] ?? '') {
            'tailo' => 'pets dog cat home',
            'gardenhaven' => 'garden home plants',
            'zenvitality' => 'wellness beauty health',
            default => 'blog lifestyle',
        };

        if ($keywords) {
            return trim(implode(' ', array_unique($keywords)) . ' ' . $fallback);
        }

        return $fallback;
    }
}