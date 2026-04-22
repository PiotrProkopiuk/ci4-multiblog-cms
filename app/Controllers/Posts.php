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