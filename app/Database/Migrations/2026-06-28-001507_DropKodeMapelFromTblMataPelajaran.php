<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropKodeMapelFromTblMataPelajaran extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        $fields = $db->getFieldNames('tbl_mata_pelajaran');


    }

    public function down()
    {
        $db = \Config\Database::connect();

        $fields = $db->getFieldNames('tbl_mata_pelajaran');

        if (! in_array('kode_mapel', $fields)) {
            $this->forge->addColumn('tbl_mata_pelajaran', [
                'kode_mapel' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 10,
                    'null'       => false,
                    'after'      => 'id_mapel',
                ],
            ]);
        }
    }
}