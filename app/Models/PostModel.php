<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['blog_id', 'user_id', 'title', 'content', 'status', 'language', 'featured_image_url', 'featured_image_alt', 'featured_image_source', 'featured_image_author'];
    protected $useTimestamps = true;
    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'content' => 'required',
        'status' => 'required|in_list[draft,publish]',
        'language' => 'required|in_list[en,pl,de]',
        'blog_id' => 'required|integer',
    ];
}