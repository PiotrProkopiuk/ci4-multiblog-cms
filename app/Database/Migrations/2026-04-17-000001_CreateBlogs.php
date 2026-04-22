<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBlogs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 160],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 120],
            'domain' => ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true],
            'default_language' => ['type' => 'VARCHAR', 'constraint' => 8, 'default' => 'en'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addUniqueKey('domain');
        $this->forge->createTable('blogs');
    }

    public function down()
    {
        $this->forge->dropTable('blogs');
    }
}