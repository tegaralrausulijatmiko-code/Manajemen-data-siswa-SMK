<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblAbsensi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_absensi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'id_siswa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'id_kelas' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'id_tahun_ajaran' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'id_jadwal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => null,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Hadir', 'Izin', 'Sakit', 'Alpa'],
                'null'       => false,
                'default'    => 'Hadir',
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => 'current_timestamp()',
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => 'current_timestamp()',
            ],
        ]);

        $this->forge->addKey('id_absensi', true);
        $this->forge->addUniqueKey(['id_siswa', 'id_kelas', 'id_tahun_ajaran', 'id_jadwal'], 'id_siswa');

        $this->forge->createTable('tbl_absensi', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);

        // Seed data
        $this->db->table('tbl_absensi')->insert([
            'id_absensi'      => 0,
            'id_siswa'        => 8,
            'id_kelas'        => 9,
            'id_tahun_ajaran' => 0,
            'id_jadwal'       => null,
            'tanggal'         => '2026-06-22',
            'status'          => 'Hadir',
            'keterangan'      => '',
            'created_at'      => '2026-06-22 04:27:13',
            'updated_at'      => '2026-06-22 04:27:13',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_absensi', true);
    }
}