<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTranslations extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'blog_id' => ['type' => 'INT', 'unsigned' => true],
            'language' => ['type' => 'VARCHAR', 'constraint' => 8],
            'translation_key' => ['type' => 'VARCHAR', 'constraint' => 120],
            'value' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['blog_id', 'language', 'translation_key']);
        $this->forge->addForeignKey('blog_id', 'blogs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('translations');
    }

    public function down()
    {
        $this->forge->dropTable('translations');
    }
}