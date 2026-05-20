<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLanguagesToBlogs extends Migration
{
    public function up()
    {
        $fields = [];
        // Add languages column as JSON/text
        $fields['languages'] = ['type' => 'TEXT', 'null' => true, 'after' => 'default_language'];
        $this->forge->addColumn('blogs', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('blogs', 'languages');
    }
}

