<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKelasGuruToMapel extends Migration
{
    public function up()
    {
        $fields = [
            'id_kelas' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'status',
            ],
            'id_guru' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_kelas',
            ],
        ];

        $this->forge->addColumn('tbl_mata_pelajaran', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_mata_pelajaran', ['id_kelas', 'id_guru']);
    }
}
