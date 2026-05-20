<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPostSlugAndCategory extends Migration
{
    public function up()
    {
        // Detect existing columns
        $fieldNames = array_map(static fn($f) => $f->name, $this->db->getFieldData('posts') ?? []);

        $fields = [];
        if (! in_array('slug', $fieldNames, true)) {
            $fields['slug'] = ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true];
        }
        if (! in_array('category_id', $fieldNames, true)) {
            $fields['category_id'] = ['type' => 'INT', 'unsigned' => true, 'null' => true];
        }
        if ($fields) {
            $this->forge->addColumn('posts', $fields);
        }

        // Unique index for (blog_id, language, slug)
        try {
            // PostgreSQL
            if (stripos($this->db->DBDriver, 'Postgre') !== false) {
                $this->db->query('CREATE UNIQUE INDEX IF NOT EXISTS posts_blog_lang_slug_idx ON posts (blog_id, language, slug)');
            } else {
                // MySQL/MariaDB
                $this->db->query('ALTER TABLE `posts` ADD UNIQUE KEY `posts_blog_lang_slug_idx` (`blog_id`,`language`,`slug`)');
            }
        } catch (\Throwable $e) {
            // ignore if exists
        }

        // FK to categories(id)
        try {
            if (stripos($this->db->DBDriver, 'Postgre') !== false) {
                $this->db->query('ALTER TABLE posts ADD CONSTRAINT IF NOT EXISTS posts_category_fk FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE SET NULL');
            } else {
                $this->db->query('ALTER TABLE `posts` ADD CONSTRAINT `posts_category_fk` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON UPDATE CASCADE ON DELETE SET NULL');
            }
        } catch (\Throwable $e) {
        }
    }

    public function down()
    {
        // Drop FK
        try {
            if (stripos($this->db->DBDriver, 'Postgre') !== false) {
                $this->db->query('ALTER TABLE posts DROP CONSTRAINT IF EXISTS posts_category_fk');
            } else {
                $this->db->query('ALTER TABLE `posts` DROP FOREIGN KEY `posts_category_fk`');
            }
        } catch (\Throwable $e) {}

        // Drop unique index
        try {
            if (stripos($this->db->DBDriver, 'Postgre') !== false) {
                $this->db->query('DROP INDEX IF EXISTS posts_blog_lang_slug_idx');
            } else {
                $this->db->query('ALTER TABLE `posts` DROP INDEX `posts_blog_lang_slug_idx`');
            }
        } catch (\Throwable $e) {}

        // Drop columns if present
        $fieldNames = array_map(static fn($f) => $f->name, $this->db->getFieldData('posts') ?? []);
        if (in_array('slug', $fieldNames, true)) {
            $this->forge->dropColumn('posts', 'slug');
        }
        if (in_array('category_id', $fieldNames, true)) {
            $this->forge->dropColumn('posts', 'category_id');
        }
    }
}
