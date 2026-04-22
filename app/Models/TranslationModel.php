<?php

namespace App\Models;

use CodeIgniter\Model;

class TranslationModel extends Model
{
    protected $table = 'translations';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['blog_id', 'language', 'translation_key', 'value'];
    protected $useTimestamps = true;
    protected $validationRules = [
        'blog_id' => 'required|integer',
        'language' => 'required|max_length[8]',
        'translation_key' => 'required|max_length[120]',
        'value' => 'permit_empty',
    ];
}