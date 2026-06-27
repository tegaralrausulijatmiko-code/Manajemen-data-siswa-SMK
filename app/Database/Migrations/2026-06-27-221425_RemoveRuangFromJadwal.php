<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveRuangFromJadwal extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('tbl_jadwal', 'ruang');
    }

    public function down()
    {
        $this->forge->addColumn('tbl_jadwal', [
            'ruang' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
        ]);
    }
}