<?php

namespace App\Controllers;

use App\Libraries\BlogContext;
use App\Models\PostModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (! $this->session->get('is_logged_in')) {
            return redirect()->to(site_url('login'));
        }

        $blog = (new BlogContext())->current();
        $posts = new PostModel();

        return view('dashboard', [
            'title' => 'Dashboard',
            'blog' => $blog,
            'publishedCount' => $posts->where('blog_id', $blog['id'])->where('status', 'publish')->countAllResults(),
            'draftCount' => $posts->where('blog_id', $blog['id'])->where('status', 'draft')->countAllResults(),
        ]);
    }
}