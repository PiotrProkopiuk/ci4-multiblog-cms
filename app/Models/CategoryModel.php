<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['blog_id', 'name', 'slug', 'language'];
    protected $useTimestamps = true;
}