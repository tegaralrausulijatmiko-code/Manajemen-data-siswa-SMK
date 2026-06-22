<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblJadwal extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_jadwal' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'id_kelas' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'comment'    => 'FK ke tbl_kelas',
            ],
            'id_mapel' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'comment'    => 'FK ke tbl_mata_pelajaran',
            ],
            'id_guru' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'comment'    => 'FK ke tbl_guru',
            ],
            'hari' => [
                'type'       => 'ENUM',
                'constraint' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                'null'       => false,
            ],
            'jam_mulai' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'jam_selesai' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'ruang' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
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

        $this->forge->addKey('id_jadwal', true);
        $this->forge->addKey('id_kelas', false, false, 'fk_jadwal_kelas');
        $this->forge->addKey('id_mapel', false, false, 'fk_jadwal_mapel');
        $this->forge->addKey('id_guru',  false, false, 'fk_jadwal_guru');

        $this->forge->createTable('tbl_jadwal', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);

        // Foreign keys
        $this->db->query('
            ALTER TABLE tbl_jadwal
            ADD CONSTRAINT fk_jadwal_kelas
            FOREIGN KEY (id_kelas) REFERENCES tbl_kelas(id_kelas)
            ON UPDATE CASCADE
        ');
        $this->db->query('
            ALTER TABLE tbl_jadwal
            ADD CONSTRAINT fk_jadwal_mapel
            FOREIGN KEY (id_mapel) REFERENCES tbl_mata_pelajaran(id_mapel)
            ON UPDATE CASCADE
        ');
        $this->db->query('
            ALTER TABLE tbl_jadwal
            ADD CONSTRAINT fk_jadwal_guru
            FOREIGN KEY (id_guru) REFERENCES tbl_guru(id_guru)
            ON UPDATE CASCADE
        ');

        // Seed data
        $this->db->table('tbl_jadwal')->insert([
            'id_jadwal'   => 4,
            'id_kelas'    => 12,
            'id_mapel'    => 6,
            'id_guru'     => 1872,
            'hari'        => 'Selasa',
            'jam_mulai'   => '15:00:00',
            'jam_selesai' => '17:30:00',
            'ruang'       => '401',
            'created_at'  => '2026-06-20 16:52:14',
            'updated_at'  => '2026-06-20 16:52:14',
        ]);

        $this->db->query('ALTER TABLE tbl_jadwal AUTO_INCREMENT = 5');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE tbl_jadwal DROP FOREIGN KEY fk_jadwal_kelas');
        $this->db->query('ALTER TABLE tbl_jadwal DROP FOREIGN KEY fk_jadwal_mapel');
        $this->db->query('ALTER TABLE tbl_jadwal DROP FOREIGN KEY fk_jadwal_guru');
        $this->forge->dropTable('tbl_jadwal', true);
    }
}