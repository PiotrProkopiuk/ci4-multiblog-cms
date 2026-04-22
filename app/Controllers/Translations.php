<?php

namespace App\Controllers;

use App\Libraries\BlogContext;
use App\Models\TranslationModel;

class Translations extends BaseController
{
    private TranslationModel $translations;

    public function __construct()
    {
        $this->translations = new TranslationModel();
    }

    public function index()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $blog = (new BlogContext())->current();
        $language = (string) ($this->request->getGet('language') ?: $blog['default_language']);
        $rows = $this->translations
            ->where('blog_id', $blog['id'])
            ->where('language', $language)
            ->orderBy('translation_key', 'ASC')
            ->findAll();

        return view('translations/index', [
            'title' => 'Translations',
            'blog' => $blog,
            'language' => $language,
            'rows' => $rows,
        ]);
    }

    public function update()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $blog = (new BlogContext())->current();
        $language = (string) $this->request->getPost('language');
        $values = $this->request->getPost('translations') ?? [];

        foreach ($values as $id => $value) {
            $row = $this->translations
                ->where('blog_id', $blog['id'])
                ->where('language', $language)
                ->find((int) $id);

            if ($row) {
                $this->translations->update((int) $id, ['value' => (string) $value]);
            }
        }

        return redirect()->to(site_url('admin/translations?language=' . $language));
    }

    private function requireAuth()
    {
        if (! $this->session->get('is_logged_in')) {
            return redirect()->to(site_url('login'));
        }

        return null;
    }
}