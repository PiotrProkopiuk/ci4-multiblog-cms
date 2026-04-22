<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPostImages extends Migration
{
    public function up()
    {
        $this->forge->addColumn('posts', [
            'featured_image_url' => ['type' => 'TEXT', 'null' => true],
            'featured_image_alt' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'featured_image_source' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'featured_image_author' => ['type' => 'VARCHAR', 'constraint' => 160, 'null' => true],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('posts', ['featured_image_url', 'featured_image_alt', 'featured_image_source', 'featured_image_author']);
    }
}