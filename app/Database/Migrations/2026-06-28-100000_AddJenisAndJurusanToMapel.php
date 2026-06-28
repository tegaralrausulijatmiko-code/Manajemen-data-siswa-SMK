<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJenisAndJurusanToMapel extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('tbl_mata_pelajaran');

        // Add jenis column if it doesn't exist
        if (! in_array('jenis', $fields)) {
            $this->forge->addColumn('tbl_mata_pelajaran', [
                'jenis' => [
                    'type'       => 'ENUM',
                    'constraint' => ['Umum', 'Kejuruan'],
                    'default'    => 'Umum',
                    'after'      => 'tingkat',
                ],
            ]);
        }

        // Add id_jurusan column if it doesn't exist
        if (! in_array('id_jurusan', $fields)) {
            $this->forge->addColumn('tbl_mata_pelajaran', [
                'id_jurusan' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                    'after'      => 'jenis',
                ],
            ]);

            // Add foreign key constraint
            $this->forge->addForeignKey('id_jurusan', 'tbl_jurusan', 'id_jurusan', 'CASCADE', 'CASCADE');
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('tbl_mata_pelajaran');

        if (in_array('jenis', $fields)) {
            $this->forge->dropColumn('tbl_mata_pelajaran', 'jenis');
        }

        if (in_array('id_jurusan', $fields)) {
            $this->forge->dropForeignKey('tbl_mata_pelajaran', 'tbl_mata_pelajaran_id_jurusan_foreign');
            $this->forge->dropColumn('tbl_mata_pelajaran', 'id_jurusan');
        }
    }
}
