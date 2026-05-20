<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBlogSettings extends Migration
{
    public function up()
    {
        $this->forge->addColumn('blogs', [
            'description'     => ['type' => 'TEXT', 'null' => true, 'after' => 'name'],
            'homepage_layout' => ['type' => 'VARCHAR', 'constraint' => 32, 'default' => 'variant_a', 'after' => 'default_language'],
            'accent_color'    => ['type' => 'VARCHAR', 'constraint' => 16, 'null' => true, 'after' => 'homepage_layout'],
            'hero_image_url'  => ['type' => 'TEXT', 'null' => true, 'after' => 'accent_color'],
            'tagline'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'hero_image_url'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('blogs', ['description', 'homepage_layout', 'accent_color', 'hero_image_url', 'tagline']);
    }
}
