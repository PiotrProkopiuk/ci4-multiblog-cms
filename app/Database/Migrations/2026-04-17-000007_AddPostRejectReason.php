<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPostRejectReason extends Migration
{
    public function up()
    {
        $this->forge->addColumn('posts', [
            'reject_reason' => ['type' => 'TEXT', 'null' => true],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('posts', ['reject_reason']);
    }
}
