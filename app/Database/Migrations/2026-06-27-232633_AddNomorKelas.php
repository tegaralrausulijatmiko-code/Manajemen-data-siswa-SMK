<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNomorKelas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tbl_kelas', [
            'nomor_kelas' => [
                'type'       => 'INT',
                'constraint' => 2,
                'after'      => 'tingkat',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_kelas', 'nomor_kelas');
    }
}
