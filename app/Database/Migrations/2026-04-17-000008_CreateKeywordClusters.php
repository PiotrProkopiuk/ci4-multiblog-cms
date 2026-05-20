<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKeywordClusters extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'blog_id'     => ['type' => 'INT', 'null' => false],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'description' => ['type' => 'TEXT', 'null' => true],
            'keywords'    => ['type' => 'TEXT'],
            'language'    => ['type' => 'VARCHAR', 'constraint' => 8, 'default' => 'pl'],
            'post_id'     => ['type' => 'INT', 'null' => true],
            'created_at'  => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'  => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('keyword_clusters');
    }

    public function down()
    {
        $this->forge->dropTable('keyword_clusters');
    }
}
