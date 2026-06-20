<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJadwalToAbsensi extends Migration
{
    public function up()
    {
        $fields = [
            'id_jadwal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'after'      => 'id_tahun_ajaran',
            ],
        ];

        $this->forge->addColumn('tbl_absensi', $fields);
        $this->db->query('ALTER TABLE tbl_absensi ADD INDEX id_jadwal (id_jadwal)');
        $this->db->query('ALTER TABLE tbl_absensi ADD CONSTRAINT fk_absensi_jadwal FOREIGN KEY (id_jadwal) REFERENCES tbl_jadwal(id_jadwal) ON UPDATE CASCADE ON DELETE SET NULL');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE tbl_absensi DROP FOREIGN KEY fk_absensi_jadwal');
        $this->forge->dropColumn('tbl_absensi', 'id_jadwal');
    }
}
