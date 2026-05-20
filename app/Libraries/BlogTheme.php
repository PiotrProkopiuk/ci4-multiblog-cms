<?php

namespace App\Libraries;

class BlogTheme
{
    public static function forBlog(array $blog): array
    {
        return self::forSlug($blog['slug'], $blog);
    }

    public static function forSlug(string $slug, array $blog = []): array
    {
        $defaults = [
            'tailo' => [
                'class'  => 'theme-tailo',
                'accent' => '#f97316',
                'image'  => 'https://images.unsplash.com/photo-1450778869180-41d0601e046e?auto=format&fit=crop&w=1800&q=80',
            ],
            'gardenhaven' => [
                'class'  => 'theme-garden',
                'accent' => '#2f8f46',
                'image'  => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?auto=format&fit=crop&w=1800&q=80',
            ],
            'zenvitality' => [
                'class'  => 'theme-zen',
                'accent' => '#be3b74',
                'image'  => 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=1800&q=80',
            ],
        ];

        $base = $defaults[$slug] ?? [
            'class'  => 'theme-default',
            'accent' => '#1455d9',
            'image'  => 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=1800&q=80',
        ];

        if (! empty($blog['accent_color'])) {
            $base['accent'] = $blog['accent_color'];
        }
        if (! empty($blog['hero_image_url'])) {
            $base['image'] = $blog['hero_image_url'];
        }

        $base['layout'] = $blog['homepage_layout'] ?? 'variant_a';

        return $base;
    }
}
