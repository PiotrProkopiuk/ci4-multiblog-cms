<?php

namespace App\Libraries;

use App\Models\BlogModel;
use CodeIgniter\HTTP\IncomingRequest;
use Config\Services;

class BlogContext
{
    private BlogModel $blogs;

    public function __construct()
    {
        $this->blogs = new BlogModel();
    }

    public function current(): array
    {
        $request = Services::request();
        $session = Services::session();
        $config = config('Blog');
        $slug = $config->defaultSlug;

        if ($request instanceof IncomingRequest) {
            $requestedSlug = $request->getGet('blog');

            if (is_string($requestedSlug) && $requestedSlug !== '') {
                $blog = $this->blogs->where('slug', $requestedSlug)->first();

                if ($blog) {
                    $session->set('preview_blog_slug', $blog['slug']);
                    return $blog;
                }
            }

            $host = strtolower(explode(':', $request->getServer('HTTP_HOST') ?: '')[0]);

            if ($host && isset($config->hostMap[$host])) {
                $slug = $config->hostMap[$host];
            } elseif ($host) {
                $domainBlog = $this->blogs->where('domain', $host)->first();

                if ($domainBlog) {
                    return $domainBlog;
                }
            }

            $sessionSlug = $session->get('preview_blog_slug');

            if (is_string($sessionSlug) && $sessionSlug !== '') {
                $blog = $this->blogs->where('slug', $sessionSlug)->first();

                if ($blog) {
                    return $blog;
                }
            }
        }

        $blog = $this->blogs->where('slug', $slug)->first();

        if (! $blog) {
            $blog = $this->blogs->first();
        }

        if (! $blog) {
            throw new \RuntimeException('No blog configured. Run migrations and seeders first.');
        }

        return $blog;
    }
}