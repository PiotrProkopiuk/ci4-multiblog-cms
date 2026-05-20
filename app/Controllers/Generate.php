<?php

namespace App\Controllers;

use App\Libraries\BlogContext;
use App\Libraries\ArticleGenerator;
use App\Models\KeywordClusterModel;
use App\Models\PostModel;

class Generate extends BaseController
{
    private KeywordClusterModel $clusters;
    private PostModel $posts;

    public function __construct()
    {
        $this->clusters = new KeywordClusterModel();
        $this->posts    = new PostModel();
    }

    public function index()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $blog = (new BlogContext())->current();

        $clusters = $this->clusters
            ->where('blog_id', $blog['id'])
            ->orderBy('created_at', 'DESC')
            ->findAll();

        foreach ($clusters as &$c) {
            $c['keywords_array'] = $this->clusters->keywordsArray($c);
        }

        return view('generate/index', [
            'title'    => 'AI Article Generator',
            'blog'     => $blog,
            'clusters' => $clusters,
        ]);
    }

    public function cluster()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $blog     = (new BlogContext())->current();
        $raw      = (string) $this->request->getPost('keywords');
        $language = (string) $this->request->getPost('language') ?: 'pl';

        $keywords = array_values(array_filter(
            array_map('trim', preg_split('/[\n,]+/', $raw) ?: []),
            static fn($k) => $k !== ''
        ));

        if (count($keywords) < 2) {
            return redirect()->to(site_url('admin/generate'))
                ->with('flash_error', 'Wpisz co najmniej 2 słowa kluczowe.');
        }

        try {
            $generator = new ArticleGenerator();
            $grouped   = $generator->clusterKeywords($keywords, $language);
        } catch (\RuntimeException $e) {
            return redirect()->to(site_url('admin/generate'))
                ->with('flash_error', 'Błąd AI: ' . $e->getMessage());
        }

        $this->clusters->where('blog_id', $blog['id'])->where('post_id IS NULL')->delete();

        foreach ($grouped as $group) {
            $this->clusters->save([
                'blog_id'     => $blog['id'],
                'name'        => $group['name'] ?? 'Cluster',
                'description' => $group['description'] ?? '',
                'keywords'    => json_encode(array_values($group['keywords'] ?? [])),
                'language'    => $language,
                'post_id'     => null,
            ]);
        }

        return redirect()->to(site_url('admin/generate'))
            ->with('flash_success', 'Pogrupowano słowa kluczowe w ' . count($grouped) . ' klastry.');
    }

    public function generateOne(int $clusterId)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $cluster = $this->clusters->find($clusterId);

        if (! $cluster) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $blogModel = new \App\Models\BlogModel();
        $blog      = $blogModel->find($cluster['blog_id']);

        if (! $blog) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        try {
            $generator = new ArticleGenerator();
            $cluster['keywords_array'] = $this->clusters->keywordsArray($cluster);
            $article = $generator->generateArticle($cluster, $cluster['language'], $blog);
        } catch (\RuntimeException $e) {
            return redirect()->to(site_url('admin/generate'))
                ->with('flash_error', 'Błąd generowania: ' . $e->getMessage());
        }

        $postId = $this->posts->insert([
            'blog_id'  => $blog['id'],
            'user_id'  => $this->session->get('user_id'),
            'title'    => $article['title'],
            'content'  => $article['content'],
            'status'   => 'review_pending',
            'language' => $cluster['language'],
        ]);

        $this->clusters->update($clusterId, ['post_id' => $postId]);

        $this->session->set('ai_pexels_queries_' . $postId, $article['pexels_queries'] ?? []);

        return redirect()->to(site_url('admin/posts/' . $postId . '/review'))
            ->with('flash_success', 'Artykuł wygenerowany przez GPT-4o — przejrzyj go i zatwierdź.');
    }

    public function generateBatch()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $pending  = $this->clusters
            ->where('post_id IS NULL')
            ->findAll();

        if (! $pending) {
            return redirect()->to(site_url('admin/generate'))
                ->with('flash_error', 'Brak klastrów bez przypisanego artykułu.');
        }

        $blogModel = new \App\Models\BlogModel();
        $generated = 0;
        $errors    = [];

        foreach ($pending as $cluster) {
            try {
                $blog = $blogModel->find($cluster['blog_id']);
                if (! $blog) {
                    $errors[] = $cluster['name'] . ': nieznany blog';
                    continue;
                }
                $generator = new ArticleGenerator();
                $cluster['keywords_array'] = $this->clusters->keywordsArray($cluster);
                $article = $generator->generateArticle($cluster, $cluster['language'], $blog);

                $postId = $this->posts->insert([
                    'blog_id'  => $blog['id'],
                    'user_id'  => $this->session->get('user_id'),
                    'title'    => $article['title'],
                    'content'  => $article['content'],
                    'status'   => 'review_pending',
                    'language' => $cluster['language'],
                ]);

                $this->clusters->update($cluster['id'], ['post_id' => $postId]);
                $this->session->set('ai_pexels_queries_' . $postId, $article['pexels_queries'] ?? []);
                $generated++;
            } catch (\RuntimeException $e) {
                $errors[] = $cluster['name'] . ': ' . $e->getMessage();
            }
        }

        $msg = "Wygenerowano $generated artykułów — trafiły do kolejki recenzji.";

        if ($errors) {
            $msg .= ' Błędy: ' . implode('; ', $errors);
        }

        return redirect()->to(site_url('admin/posts/review'))->with('flash_success', $msg);
    }

    private function requireAuth()
    {
        if (! $this->session->get('is_logged_in')) {
            return redirect()->to(site_url('login'));
        }

        return null;
    }
}
