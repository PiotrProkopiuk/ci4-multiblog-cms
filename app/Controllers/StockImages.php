<?php

namespace App\Controllers;

use App\Libraries\BlogContext;
use App\Libraries\StockImageSearch;

class StockImages extends BaseController
{
    public function suggest()
    {
        if (! $this->session->get('is_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Login required.',
            ]);
        }

        $payload = $this->request->getJSON(true) ?? [];
        $blog = (new BlogContext())->current();
        $title = (string) ($payload['title'] ?? '');
        $content = (string) ($payload['content'] ?? '');

        try {
            $result = (new StockImageSearch())->suggest($blog, $title, $content);

            return $this->response->setJSON([
                'success' => true,
                'provider' => $result['provider'],
                'query' => $result['query'],
                'images' => $result['images'],
            ]);
        } catch (\RuntimeException $exception) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}