<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Libraries\BlogContext;
use App\Models\PostModel;

class Posts extends BaseController
{
    public function create()
    {
        $payload = $this->request->getJSON(true) ?? [];
        $blog = (new BlogContext())->current();
        $posts = new PostModel();
        $data = [
            'blog_id' => $blog['id'],
            'user_id' => $this->session->get('user_id'),
            'title' => trim((string) ($payload['title'] ?? '')),
            'content' => (string) ($payload['content'] ?? ''),
            'status' => (string) ($payload['status'] ?? 'draft'),
            'language' => (string) ($payload['language'] ?? $blog['default_language']),
        ];

        if (! $posts->save($data)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'errors' => $posts->errors(),
            ]);
        }

        $id = $posts->getInsertID();

        return $this->response->setStatusCode(201)->setJSON([
            'success' => true,
            'id' => $id,
            'post' => $posts->find($id),
        ]);
    }
}