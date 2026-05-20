<?php

namespace App\Libraries;

class ArticleGenerator
{
    private OpenAIClient $ai;

    public function __construct()
    {
        $this->ai = new OpenAIClient('gpt-4o');
    }

    public function clusterKeywords(array $keywords, string $language = 'pl'): array
    {
        $langName = match ($language) {
            'pl' => 'Polish',
            'de' => 'German',
            default => 'English',
        };

        $list = implode("\n", array_map('trim', $keywords));

        $result = $this->ai->chatJson([
            ['role' => 'system', 'content' =>
                'You are an SEO expert. Group the provided keywords into topic clusters for blog articles. ' .
                'Each cluster should be tightly related and suitable for one comprehensive article. ' .
                'Return ONLY a JSON array of objects: [{name: "...", description: "...", keywords: ["...", "..."]}]. ' .
                'Cluster names and descriptions must be in ' . $langName . '. ' .
                'Aim for 3-7 clusters. Do not include any other text.'],
            ['role' => 'user', 'content' => "Keywords to cluster:\n" . $list],
        ], 0.3);

        if (isset($result[0])) {
            return $result;
        }

        if (isset($result['clusters'])) {
            return $result['clusters'];
        }

        throw new \RuntimeException('Nieoczekiwana struktura odpowiedzi OpenAI podczas grupowania słów kluczowych.');
    }

    public function generateArticle(array $cluster, string $language, array $blog): array
    {
        $langName = match ($language) {
            'pl' => 'Polish',
            'de' => 'German',
            default => 'English',
        };

        $blogDesc = match ($blog['slug'] ?? '') {
            'tailo'       => 'a practical pet care blog for dog, cat, and small animal owners',
            'gardenhaven' => 'a home and garden lifestyle blog with inspiration for plant lovers',
            'zenvitality' => 'a wellness and beauty blog focused on healthy habits and self-care',
            default       => 'a lifestyle blog',
        };

        $keywords = implode(', ', $cluster['keywords_array'] ?? []);

        $result = $this->ai->chatJson([
            ['role' => 'system', 'content' =>
                'You are an expert content writer specializing in SEO blog articles. ' .
                'Write a comprehensive, engaging, human-sounding article in ' . $langName . '. ' .
                'The blog is ' . $blogDesc . '. ' .
                'Return ONLY a JSON object with these fields: ' .
                '{ "title": "...", "meta_description": "...", "content": "<full HTML article>", "pexels_queries": ["query1", "query2", "query3"] }. ' .
                'The content must be full HTML (use <h2>, <h3>, <p>, <ul>, <li> tags). ' .
                'Length: 600-900 words. Do NOT use markdown, only HTML. ' .
                'Do not include any text outside the JSON.'],
            ['role' => 'user', 'content' =>
                "Topic cluster: " . ($cluster['name'] ?? 'General') . "\n" .
                "Description: " . ($cluster['description'] ?? '') . "\n" .
                "Keywords to include naturally: " . $keywords],
        ], 0.75);

        foreach (['title', 'content', 'meta_description'] as $field) {
            if (empty($result[$field])) {
                throw new \RuntimeException("OpenAI nie zwrócił pola '$field' dla artykułu.");
            }
        }

        return $result;
    }
}
