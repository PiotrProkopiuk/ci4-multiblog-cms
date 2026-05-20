<?php

namespace App\Models;

use CodeIgniter\Model;

class KeywordClusterModel extends Model
{
    protected $table         = 'keyword_clusters';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['blog_id', 'name', 'description', 'keywords', 'language', 'post_id'];
    protected $useTimestamps = true;

    public function keywordsArray(array $cluster): array
    {
        $raw = $cluster['keywords'] ?? '[]';
        return json_decode($raw, true) ?: [];
    }
}
