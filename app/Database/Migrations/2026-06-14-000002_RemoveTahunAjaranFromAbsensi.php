<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveTahunAjaranFromAbsensi extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('id_tahun_ajaran', 'tbl_absensi')) {
            return;
        }

        $constraint = $this->db->query("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'tbl_absensi'
                AND COLUMN_NAME = 'id_tahun_ajaran'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ")->getRowArray();

        if ($constraint && ! empty($constraint['CONSTRAINT_NAME'])) {
            $this->db->query('ALTER TABLE tbl_absensi DROP FOREIGN KEY ' . $constraint['CONSTRAINT_NAME']);
        }

        $this->forge->dropColumn('tbl_absensi', 'id_tahun_ajaran');
    }

    public function down()
    {
        if ($this->db->fieldExists('id_tahun_ajaran', 'tbl_absensi')) {
            return;
        }

        $this->forge->addColumn('tbl_absensi', [
            'id_tahun_ajaran' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'after'      => 'id_kelas',
            ],
        ]);
    }
}
