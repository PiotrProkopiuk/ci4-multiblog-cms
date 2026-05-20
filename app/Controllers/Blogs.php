<?php

namespace App\Controllers;

use App\Models\BlogModel;

class Blogs extends BaseController
{
    private BlogModel $blogModel;

    public function __construct()
    {
        $this->blogModel = new BlogModel();
    }

    private function guard()
    {
        if (! $this->session->get('is_logged_in')) {
            return redirect()->to(site_url('login'));
        }
        return null;
    }

    public function index()
    {
        if ($r = $this->guard()) return $r;

        $blogs = $this->blogModel->findAll();

        return view('admin/blogs/index', [
            'title' => 'Konfiguracja blogów',
            'blogs' => $blogs,
        ]);
    }

    public function create()
    {
        if ($r = $this->guard()) return $r;

        return view('admin/blogs/create', [
            'title'   => 'Nowy blog',
            'layouts' => $this->layoutOptions(),
        ]);
    }

    public function store()
    {
        if ($r = $this->guard()) return $r;

        $name = trim((string) $this->request->getPost('name'));
        $slug = $this->slugify($this->request->getPost('slug') ?: $name);

        if ($this->blogModel->where('slug', $slug)->first()) {
            return redirect()->back()->withInput()
                ->with('flash_error', 'Slug "' . $slug . '" jest juz zajety - wybierz inny.');
        }

        $id = $this->blogModel->insert([
            'name'             => $name,
            'slug'             => $slug,
            'description'      => $this->request->getPost('description') ?: null,
            'domain'           => $this->request->getPost('domain') ?: null,
            'default_language' => $this->request->getPost('default_language') ?: 'pl',
            'homepage_layout'  => $this->request->getPost('homepage_layout') ?: 'variant_a',
            'accent_color'     => $this->request->getPost('accent_color') ?: null,
            'tagline'          => $this->request->getPost('tagline') ?: null,
            'languages'        => json_encode($this->request->getPost('languages') ?: [$this->request->getPost('default_language') ?: 'pl']),
        ]);

        return redirect()->to(site_url('admin/blogs/' . $id . '/edit'))
            ->with('flash_success', 'Blog "' . $name . '" zostal utworzony. Uzupelnij reszte ustawien.');
    }

    public function edit(int $id)
    {
        if ($r = $this->guard()) return $r;

        $blog = $this->blogModel->find($id);
        if (! $blog) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $dnsStatus = $this->checkDns($blog['domain'] ?? '');

        return view('admin/blogs/edit', [
            'title'     => 'Edytuj: ' . $blog['name'],
            'blog'      => $blog,
            'layouts'   => $this->layoutOptions(),
            'dnsStatus' => $dnsStatus,
            'cmsHost'   => getenv('REPLIT_DEV_DOMAIN') ?: getenv('REPLIT_DOMAINS') ?: '',
        ]);
    }

    public function update(int $id)
    {
        if ($r = $this->guard()) return $r;

        $blog = $this->blogModel->find($id);
        if (! $blog) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $this->blogModel->update($id, [
            'name'             => $this->request->getPost('name'),
            'description'      => $this->request->getPost('description') ?: null,
            'domain'           => $this->request->getPost('domain') ?: null,
            'tagline'          => $this->request->getPost('tagline') ?: null,
            'default_language' => $this->request->getPost('default_language'),
            'homepage_layout'  => $this->request->getPost('homepage_layout'),
            'accent_color'     => $this->request->getPost('accent_color') ?: null,
            'hero_image_url'   => $this->request->getPost('hero_image_url') ?: null,
        ]);

        return redirect()->to(site_url('admin/blogs/' . $id . '/edit'))
            ->with('flash_success', 'Ustawienia zapisane.');
    }

    public function delete(int $id)
    {
        if ($r = $this->guard()) return $r;

        $blog = $this->blogModel->find($id);
        if (! $blog) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        if (in_array($blog['slug'], ['main', 'tailo', 'gardenhaven', 'zenvitality'], true)) {
            return redirect()->to(site_url('admin/blogs'))
                ->with('flash_error', 'Nie można usunąć domyślnych blogów demonstracyjnych.');
        }

        $this->blogModel->delete($id);

        return redirect()->to(site_url('admin/blogs'))
            ->with('flash_success', 'Blog "' . $blog['name'] . '" zostal usuniety.');
    }

    private function checkDns(string $domain): array
    {
        if (! $domain) {
            return ['status' => 'none'];
        }

        $resolved = @dns_get_record($domain, DNS_A | DNS_CNAME);

        if (empty($resolved)) {
            return ['status' => 'unresolved', 'domain' => $domain];
        }

        $cmsHost = getenv('REPLIT_DEV_DOMAIN') ?: '';
        foreach ($resolved as $record) {
            if (($record['type'] === 'CNAME' && isset($record['target']) && strpos($record['target'], 'replit') !== false)
                || ($record['type'] === 'A' && isset($record['ip']))) {
                return ['status' => 'ok', 'domain' => $domain, 'record' => $record];
            }
        }

        return ['status' => 'wrong', 'domain' => $domain, 'records' => $resolved];
    }

    private function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9\-]+/', '-', $text) ?? '';
        return trim($text, '-');
    }

    private function layoutOptions(): array
    {
        return [
            'variant_a' => [
                'label'       => 'Wariant A — Pełnoekranowy hero',
                'description' => 'Duże tło ze zdjęciem, tekst na dole. Mocny, nowoczesny efekt.',
                'preview'     => '/__mockup/preview/tailo-homepage/VariantA',
            ],
            'variant_b' => [
                'label'       => 'Wariant B — Split hero',
                'description' => 'Lewa: tekst, prawa: zdjęcie. Elegancki, czytelny układ.',
                'preview'     => '/__mockup/preview/tailo-homepage/VariantB',
            ],
            'variant_c' => [
                'label'       => 'Wariant C — Editorial / Magazine',
                'description' => 'Układ magazynowy z bocznym paskiem.',
                'preview'     => '/__mockup/preview/tailo-homepage/VariantC',
            ],
        ];
    }
}
