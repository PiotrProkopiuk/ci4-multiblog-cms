<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategories extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'blog_id' => ['type' => 'INT', 'unsigned' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 160],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 160],
            'language' => ['type' => 'VARCHAR', 'constraint' => 8, 'default' => 'en'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['blog_id', 'slug', 'language']);
        $this->forge->addForeignKey('blog_id', 'blogs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('categories');
    }

    public function down()
    {
        $this->forge->dropTable('categories');
    }
}