<?php

namespace App\Controllers;

use App\Libraries\BlogContext;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login(): string
    {
        return view('auth/login', [
            'title' => 'Login',
            'error' => $this->session->getFlashdata('error'),
        ]);
    }

    public function attempt()
    {
        $blog = (new BlogContext())->current();
        $email = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');
        $user = (new UserModel())
            ->where('blog_id', $blog['id'])
            ->where('email', $email)
            ->first();

        if (! $user || ! password_verify($password, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Invalid login credentials.');
        }

        $this->session->set([
            'user_id' => $user['id'],
            'blog_id' => $blog['id'],
            'role' => $user['role'],
            'user_name' => $user['name'],
            'is_logged_in' => true,
        ]);

        return redirect()->to(site_url('admin/posts'));
    }

    public function logout()
    {
        $this->session->destroy();

        return redirect()->to(site_url('login'));
    }
}