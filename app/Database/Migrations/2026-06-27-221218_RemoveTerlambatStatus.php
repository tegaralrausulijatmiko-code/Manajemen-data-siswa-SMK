<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveTerlambatStatus extends Migration
{
    public function up()
    {
        // Hapus enum Terlambat
        $this->db->query("
            ALTER TABLE tbl_absensi
            MODIFY status ENUM('Hadir','Izin','Sakit','Alpha')
            NOT NULL DEFAULT 'Hadir'
        ");
    }

    public function down()
    {
        // Tambahkan kembali enum Terlambat
        $this->db->query("
            ALTER TABLE tbl_absensi
            MODIFY status ENUM('Hadir','Izin','Sakit','Alpha','Terlambat')
            NOT NULL DEFAULT 'Hadir'
        ");
    }
}