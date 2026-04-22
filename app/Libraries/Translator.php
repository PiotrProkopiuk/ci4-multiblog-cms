<?php

namespace App\Libraries;

use App\Models\TranslationModel;

class Translator
{
    public function all(int $blogId, string $language): array
    {
        $rows = (new TranslationModel())
            ->where('blog_id', $blogId)
            ->where('language', $language)
            ->findAll();

        $translations = [];

        foreach ($rows as $row) {
            $translations[$row['translation_key']] = $row['value'];
        }

        return $translations;
    }
}