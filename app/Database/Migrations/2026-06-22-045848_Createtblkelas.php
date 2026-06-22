<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblKelas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_kelas' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'id_jurusan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'comment'    => 'FK ke tbl_jurusan',
            ],
            'nama_kelas' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'tingkat' => [
                'type'       => 'ENUM',
                'constraint' => ['X', 'XI', 'XII'],
                'null'       => false,
            ],
            'id_wali_kelas' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => null,
                'comment'    => 'FK ke tbl_guru',
            ],
            'jumlah_siswa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'default'    => 0,
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

        $this->forge->addKey('id_kelas', true);
        $this->forge->addKey('id_jurusan', false, false, 'fk_kelas_jurusan');
        $this->forge->addKey('id_wali_kelas', false, false, 'fk_kelas_wali');

        $this->forge->createTable('tbl_kelas', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);

        // Foreign keys
        $this->db->query('
            ALTER TABLE tbl_kelas
            ADD CONSTRAINT fk_kelas_jurusan
            FOREIGN KEY (id_jurusan) REFERENCES tbl_jurusan(id_jurusan)
            ON UPDATE CASCADE
        ');
        $this->db->query('
            ALTER TABLE tbl_kelas
            ADD CONSTRAINT fk_kelas_wali
            FOREIGN KEY (id_wali_kelas) REFERENCES tbl_guru(id_guru)
            ON DELETE SET NULL ON UPDATE CASCADE
        ');

        // Seed data
        $this->db->table('tbl_kelas')->insertBatch([
            ['id_kelas' =>  2, 'id_jurusan' => 1, 'nama_kelas' => 'X RPL 2', 'tingkat' => 'X', 'id_wali_kelas' => null,  'jumlah_siswa' => 28, 'created_at' => '2026-06-09 04:23:57', 'updated_at' => '2026-06-09 04:23:57'],
            ['id_kelas' =>  3, 'id_jurusan' => 1, 'nama_kelas' => 'X RPL 3', 'tingkat' => 'X', 'id_wali_kelas' => null,  'jumlah_siswa' => 28, 'created_at' => '2026-06-09 04:26:34', 'updated_at' => '2026-06-09 04:26:34'],
            ['id_kelas' =>  6, 'id_jurusan' => 2, 'nama_kelas' => 'X TKJ 1', 'tingkat' => 'X', 'id_wali_kelas' => null,  'jumlah_siswa' => 29, 'created_at' => '2026-06-09 04:32:18', 'updated_at' => '2026-06-09 04:32:18'],
            ['id_kelas' =>  7, 'id_jurusan' => 2, 'nama_kelas' => 'X TKJ 2', 'tingkat' => 'X', 'id_wali_kelas' => null,  'jumlah_siswa' => 25, 'created_at' => '2026-06-09 04:32:51', 'updated_at' => '2026-06-09 04:32:51'],
            ['id_kelas' =>  9, 'id_jurusan' => 3, 'nama_kelas' => 'X AKL 1', 'tingkat' => 'X', 'id_wali_kelas' => 1975, 'jumlah_siswa' => 30, 'created_at' => '2026-06-15 04:48:17', 'updated_at' => '2026-06-15 04:48:17'],
            ['id_kelas' => 10, 'id_jurusan' => 4, 'nama_kelas' => 'X TF 1',  'tingkat' => 'X', 'id_wali_kelas' => 1872, 'jumlah_siswa' => 40, 'created_at' => '2026-06-15 04:48:48', 'updated_at' => '2026-06-15 04:48:48'],
            ['id_kelas' => 11, 'id_jurusan' => 5, 'nama_kelas' => 'X TLM 1', 'tingkat' => 'X', 'id_wali_kelas' => 1872, 'jumlah_siswa' => 31, 'created_at' => '2026-06-15 04:49:08', 'updated_at' => '2026-06-15 04:49:08'],
            ['id_kelas' => 12, 'id_jurusan' => 1, 'nama_kelas' => 'X RPL 1', 'tingkat' => 'X', 'id_wali_kelas' => 1872, 'jumlah_siswa' => 26, 'created_at' => '2026-06-20 16:20:20', 'updated_at' => '2026-06-20 16:20:20'],
        ]);

        $this->db->query('ALTER TABLE tbl_kelas AUTO_INCREMENT = 13');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE tbl_kelas DROP FOREIGN KEY fk_kelas_jurusan');
        $this->db->query('ALTER TABLE tbl_kelas DROP FOREIGN KEY fk_kelas_wali');
        $this->forge->dropTable('tbl_kelas', true);
    }
}