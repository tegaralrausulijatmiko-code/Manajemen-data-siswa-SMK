<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateAbsensiStatusForGuru extends Migration
{
    public function up()
    {
        $this->db->query("UPDATE tbl_absensi SET status = 'Alpha', keterangan = 'Alpha' WHERE status = 'Alpa'");
        $this->db->query("ALTER TABLE tbl_absensi MODIFY status ENUM('Hadir','Izin','Sakit','Alpha','Terlambat') NOT NULL DEFAULT 'Hadir'");
    }

    public function down()
    {
        $this->db->query("UPDATE tbl_absensi SET status = 'Alpa', keterangan = 'Alpa' WHERE status = 'Alpha'");
        $this->db->query("UPDATE tbl_absensi SET status = 'Alpa', keterangan = 'Alpa' WHERE status = 'Terlambat'");
        $this->db->query("ALTER TABLE tbl_absensi MODIFY status ENUM('Hadir','Izin','Sakit','Alpa') NOT NULL DEFAULT 'Hadir'");
    }
}
