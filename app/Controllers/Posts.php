<?php

namespace App\Controllers;

use App\Libraries\BlogContext;
use App\Models\PostModel;

class Posts extends BaseController
{
    private PostModel $posts;

    public function __construct()
    {
        $this->posts = new PostModel();
    }

    public function index()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $blog = (new BlogContext())->current();
        $posts = $this->posts
            ->where('blog_id', $blog['id'])
            ->orderBy('updated_at', 'DESC')
            ->findAll();

        return view('posts/index', [
            'title' => 'Posts',
            'blog' => $blog,
            'posts' => $posts,
            'statuses' => PostModel::STATUSES,
        ]);
    }

    public function new()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        return view('posts/form', [
            'title' => 'New post',
            'post' => null,
            'errors' => [],
            'action' => site_url('admin/posts'),
        ]);
    }

    public function create()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $blog = (new BlogContext())->current();
        $data = $this->postData($blog['id']);

        if (! $this->posts->save($data)) {
            return view('posts/form', [
                'title' => 'New post',
                'post' => $data,
                'errors' => $this->posts->errors(),
                'action' => site_url('admin/posts'),
            ]);
        }

        return redirect()->to(site_url('admin/posts'));
    }

    public function edit(int $id)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $blog = (new BlogContext())->current();
        $post = $this->posts->where('blog_id', $blog['id'])->find($id);

        if (! $post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('posts/form', [
            'title' => 'Edit post',
            'post' => $post,
            'errors' => [],
            'action' => site_url("admin/posts/{$id}"),
        ]);
    }

    public function update(int $id)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $blog = (new BlogContext())->current();
        $post = $this->posts->where('blog_id', $blog['id'])->find($id);

        if (! $post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = $this->postData($blog['id']) + ['id' => $id];

        if (! $this->posts->save($data)) {
            return view('posts/form', [
                'title' => 'Edit post',
                'post' => $data,
                'errors' => $this->posts->errors(),
                'action' => site_url("admin/posts/{$id}"),
            ]);
        }

        return redirect()->to(site_url('admin/posts'));
    }

    public function delete(int $id)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $blog = (new BlogContext())->current();
        $post = $this->posts->where('blog_id', $blog['id'])->find($id);

        if ($post) {
            $this->posts->delete($id);
        }

        return redirect()->to(site_url('admin/posts'));
    }

    public function submitReview(int $id)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $blog = (new BlogContext())->current();
        $post = $this->posts->where('blog_id', $blog['id'])->find($id);

        if (! $post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $allowed = ['draft', 'rejected'];

        if (! in_array($post['status'], $allowed, true)) {
            return redirect()->to(site_url('admin/posts'))->with('flash_error', 'Tylko szkic lub odrzucony wpis może być wysłany do recenzji.');
        }

        $this->posts->update($id, ['status' => 'review_pending', 'reject_reason' => null]);

        return redirect()->to(site_url('admin/posts'))->with('flash_success', 'Wpis wysłany do recenzji.');
    }

    public function reviewQueue()
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $blog = (new BlogContext())->current();
        $pending = $this->posts
            ->where('blog_id', $blog['id'])
            ->where('status', 'review_pending')
            ->orderBy('updated_at', 'ASC')
            ->findAll();

        return view('posts/review_queue', [
            'title' => 'Review queue',
            'blog' => $blog,
            'posts' => $pending,
        ]);
    }

    public function reviewPost(int $id)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $blog = (new BlogContext())->current();
        $post = $this->posts->where('blog_id', $blog['id'])->find($id);

        if (! $post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('posts/review_detail', [
            'title' => 'Review: ' . $post['title'],
            'blog' => $blog,
            'post' => $post,
        ]);
    }

    public function approve(int $id)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $blog = (new BlogContext())->current();
        $post = $this->posts->where('blog_id', $blog['id'])->find($id);

        if (! $post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $imageUrl = trim((string) $this->request->getPost('featured_image_url')) ?: ($post['featured_image_url'] ?? null);

        if (empty($imageUrl)) {
            return view('posts/review_detail', [
                'title' => 'Review: ' . $post['title'],
                'blog' => $blog,
                'post' => $post,
                'errors' => ['Musisz wybrać zdjęcie główne przed zatwierdzeniem wpisu.'],
            ]);
        }

        $this->posts->update($id, [
            'status' => 'publish',
            'reject_reason' => null,
            'featured_image_url' => $imageUrl,
            'featured_image_alt' => trim((string) $this->request->getPost('featured_image_alt')) ?: null,
            'featured_image_source' => trim((string) $this->request->getPost('featured_image_source')) ?: null,
            'featured_image_author' => trim((string) $this->request->getPost('featured_image_author')) ?: null,
        ]);

        return redirect()->to(site_url('admin/posts/review'))->with('flash_success', 'Wpis zatwierdzony i opublikowany.');
    }

    public function reject(int $id)
    {
        if ($redirect = $this->requireAuth()) {
            return $redirect;
        }

        $blog = (new BlogContext())->current();
        $post = $this->posts->where('blog_id', $blog['id'])->find($id);

        if (! $post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $reason = trim((string) $this->request->getPost('reject_reason'));

        $this->posts->update($id, [
            'status' => 'rejected',
            'reject_reason' => $reason ?: null,
        ]);

        return redirect()->to(site_url('admin/posts/review'))->with('flash_success', 'Wpis odrzucony.');
    }

    private function requireAuth()
    {
        if (! $this->session->get('is_logged_in')) {
            return redirect()->to(site_url('login'));
        }

        return null;
    }

    private function postData(int $blogId): array
    {
        return [
            'blog_id' => $blogId,
            'user_id' => $this->session->get('user_id'),
            'title' => trim((string) $this->request->getPost('title')),
            'content' => (string) $this->request->getPost('content'),
            'status' => (string) $this->request->getPost('status'),
            'language' => (string) $this->request->getPost('language'),
            'featured_image_url' => trim((string) $this->request->getPost('featured_image_url')) ?: null,
            'featured_image_alt' => trim((string) $this->request->getPost('featured_image_alt')) ?: null,
            'featured_image_source' => trim((string) $this->request->getPost('featured_image_source')) ?: null,
            'featured_image_author' => trim((string) $this->request->getPost('featured_image_author')) ?: null,
        ];
    }
}
