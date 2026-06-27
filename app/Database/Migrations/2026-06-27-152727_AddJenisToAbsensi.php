<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJenisToAbsensi extends Migration
{
    public function up()
    {
        $fields = [
            'jenis' => [
                'type'       => 'ENUM',
                'constraint' => ['mapel', 'harian'],
                'null'       => false,
                'default'    => 'mapel',
                'after'      => 'id_jadwal',
            ],
        ];

        $this->forge->addColumn('tbl_absensi', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_absensi', 'jenis');
    }
}