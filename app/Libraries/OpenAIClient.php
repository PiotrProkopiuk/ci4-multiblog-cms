<?php

namespace App\Libraries;

class OpenAIClient
{
    private string $apiKey;
    private string $model;

    public function __construct(string $model = 'gpt-4o')
    {
        $key = getenv('OPENAI_API_KEY') ?: env('OPENAI_API_KEY');

        if (! $key) {
            throw new \RuntimeException('Brakuje klucza OPENAI_API_KEY w sekretach projektu.');
        }

        $this->apiKey = $key;
        $this->model  = $model;
    }

    public function chat(array $messages, float $temperature = 0.7, int $maxTokens = 4096): string
    {
        $payload = json_encode([
            'model'       => $this->model,
            'messages'    => $messages,
            'temperature' => $temperature,
            'max_tokens'  => $maxTokens,
        ]);

        $curl = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 90,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
            ],
        ]);

        $body  = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $error  = curl_error($curl);
        curl_close($curl);

        if ($body === false) {
            throw new \RuntimeException('Błąd połączenia z OpenAI: ' . $error);
        }

        $data = json_decode((string) $body, true);

        if ($status >= 400 || ! isset($data['choices'][0]['message']['content'])) {
            $msg = $data['error']['message'] ?? ('Status HTTP ' . $status);
            throw new \RuntimeException('Błąd OpenAI API: ' . $msg);
        }

        return $data['choices'][0]['message']['content'];
    }

    public function chatJson(array $messages, float $temperature = 0.4): array
    {
        $raw = $this->chat($messages, $temperature);
        $clean = preg_replace('/^```(?:json)?\s*/m', '', $raw);
        $clean = preg_replace('/\s*```$/m', '', $clean);
        $decoded = json_decode(trim((string) $clean), true);

        if (! is_array($decoded)) {
            throw new \RuntimeException('OpenAI nie zwrócił poprawnego JSON. Odpowiedź: ' . mb_substr($raw, 0, 300));
        }

        return $decoded;
    }
}
