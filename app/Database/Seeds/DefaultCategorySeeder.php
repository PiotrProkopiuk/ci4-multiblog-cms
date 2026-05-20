<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\BlogModel;
use App\Models\CategoryModel;

class DefaultCategorySeeder extends Seeder
{
    public function run()
    {
        $blogModel = new BlogModel();
        $catModel  = new CategoryModel();
        $blogs = $blogModel->findAll();

        foreach ($blogs as $blog) {
            $blogId = $blog['id'];
            $defaultLang = $blog['default_language'] ?? 'en';
            // check for existing "uncategorized" category
            $exists = $catModel
                ->where('blog_id', $blogId)
                ->where('language', $defaultLang)
                ->where('slug', 'uncategorized')
                ->first();

            if (! $exists) {
                // create default category
                $catId = $catModel->insert([
                    'blog_id' => $blogId,
                    'name'    => 'Uncategorized',
                    'slug'    => 'uncategorized',
                    'language'=> $defaultLang,
                ]);
                echo "Created 'Uncategorized' category (ID: {$catId}) for blog ID {$blogId}\n";
            } else {
                $catId = $exists['id'];
                echo "Found existing 'Uncategorized' category (ID: {$catId}) for blog ID {$blogId}\n";
            }

            // assign posts without category to this default category
            $builder = $this->db->table('posts');
            $builder->where('blog_id', $blogId)
                    ->where('category_id IS NULL')
                    ->update(['category_id' => $catId]);

            // Use DB connection to get affected rows (Query Builder update returns true/false)
            $count = $this->db->affectedRows();

            echo "Assigned {$count} posts to category ID {$catId}\n";
        }
    }
}
