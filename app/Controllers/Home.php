<?php

namespace App\Controllers;

use App\Libraries\BlogContext;
use App\Libraries\BlogTheme;
use App\Libraries\Translator;
use App\Models\PostModel;
use App\Models\CategoryModel;

class Home extends BaseController
{
    public function index(?string $locale = null): string
    {
        $context = new BlogContext();
        $blog = $context->current();
        $language = $this->resolveLanguage($locale);
        $translations = (new Translator())->all((int) $blog['id'], $language);
        $theme = BlogTheme::forBlog($blog);
        $posts = (new PostModel())
            ->where('blog_id', $blog['id'])
            ->where('language', $language)
            ->where('status', 'publish')
            ->orderBy('updated_at', 'DESC')
            ->findAll();

        // Map category slugs for pretty URLs
        $catModel = new CategoryModel();
        $categories = $catModel
            ->where('blog_id', $blog['id'])
            ->where('language', $language)
            ->findAll();
        $catMap = array_column($categories, 'slug', 'id');
        foreach ($posts as &$p) {
            $p['category_slug'] = $p['category_id'] ? ($catMap[$p['category_id']] ?? '') : '';
        }

        return view('home', [
            'title' => $blog['name'],
            'blog' => $blog,
            'posts' => $posts,
            'language' => $language,
            'theme' => $theme,
            't' => $translations,
        ]);
    }

    public function show(int $id, ?string $locale = null): string
    {
        $context = new BlogContext();
        $blog = $context->current();
        $language = $this->resolveLanguage($locale);
        $translations = (new Translator())->all((int) $blog['id'], $language);
        $theme = BlogTheme::forBlog($blog);
        $post = (new PostModel())
            ->where('blog_id', $blog['id'])
            ->where('language', $language)
            ->where('status', 'publish')
            ->find($id);

        if (! $post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('posts/show', [
            'title' => $post['title'],
            'blog' => $blog,
            'post' => $post,
            'language' => $language,
            'theme' => $theme,
            't' => $translations,
        ]);
    }

    public function showBySlug(string $lang, string $categorySlug, string $postSlug): string
    {
        $context = new BlogContext();
        $blog = $context->current();
        $language = $this->resolveLanguage($lang);
        $translations = (new Translator())->all((int) $blog['id'], $language);
        $theme = BlogTheme::forBlog($blog);

        $category = (new CategoryModel())
            ->where('blog_id', $blog['id'])
            ->where('language', $language)
            ->where('slug', $categorySlug)
            ->first();

        if (! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $post = (new PostModel())
            ->where('blog_id', $blog['id'])
            ->where('language', $language)
            ->where('status', 'publish')
            ->where('category_id', $category['id'])
            ->where('slug', $postSlug)
            ->first();

        if (! $post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('posts/show', [
            'title' => $post['title'],
            'blog' => $blog,
            'post' => $post,
            'language' => $language,
            'theme' => $theme,
            't' => $translations,
        ]);
    }

    public function switchLanguage(string $locale)
    {
        if (in_array($locale, ['en', 'pl'], true)) {
            $this->session->set('language', $locale);
        }

        return redirect()->to(previous_url() ?: site_url('/'));
    }

    private function resolveLanguage(?string $locale): string
    {
        if ($locale && in_array($locale, ['en', 'pl','de'], true)) {
            $this->session->set('language', $locale);
            return $locale;
        }

        return $this->session->get('language') ?: 'en';
    }
}
