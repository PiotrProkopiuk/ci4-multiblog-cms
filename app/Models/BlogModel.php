<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogModel extends Model
{
    protected $table = 'blogs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'slug', 'domain', 'default_language', 'description', 'homepage_layout', 'accent_color', 'hero_image_url', 'tagline'];
    protected $useTimestamps = true;
}