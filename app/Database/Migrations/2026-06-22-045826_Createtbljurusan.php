<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblJurusan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_jurusan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'kode_jurusan' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => false,
            ],
            'nama_jurusan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'id_kaprog' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => null,
                'comment'    => 'FK ke tbl_guru',
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

        $this->forge->addKey('id_jurusan', true);
        $this->forge->addUniqueKey('kode_jurusan', 'uq_kode_jurusan');
        $this->forge->addKey('id_kaprog', false, false, 'fk_jurusan_kaprog');

        $this->forge->createTable('tbl_jurusan', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);

        // Foreign key
        $this->db->query('
            ALTER TABLE tbl_jurusan
            ADD CONSTRAINT fk_jurusan_kaprog
            FOREIGN KEY (id_kaprog) REFERENCES tbl_guru(id_guru)
            ON DELETE SET NULL ON UPDATE CASCADE
        ');

        // Seed data
        $this->db->table('tbl_jurusan')->insertBatch([
            ['id_jurusan' => 1, 'kode_jurusan' => 'RPL',  'nama_jurusan' => 'Rekayasa Perangkat Lunak',   'id_kaprog' => 1975, 'created_at' => '2026-06-05 08:26:00', 'updated_at' => '2026-06-14 17:13:49'],
            ['id_jurusan' => 2, 'kode_jurusan' => 'TKJ',  'nama_jurusan' => 'Teknik Jaringan Komputer',   'id_kaprog' => 1975, 'created_at' => '2026-06-09 04:28:01', 'updated_at' => '2026-06-14 17:11:08'],
            ['id_jurusan' => 3, 'kode_jurusan' => 'AKL',  'nama_jurusan' => 'Akuntansi Keuangan Lembaga', 'id_kaprog' => 1872, 'created_at' => '2026-06-09 04:28:44', 'updated_at' => '2026-06-20 16:14:52'],
            ['id_jurusan' => 4, 'kode_jurusan' => 'TF',   'nama_jurusan' => 'Teknologi Farmasi',           'id_kaprog' => 1872, 'created_at' => '2026-06-09 04:29:06', 'updated_at' => '2026-06-14 17:11:02'],
            ['id_jurusan' => 5, 'kode_jurusan' => 'TLM',  'nama_jurusan' => 'Teknik Laboratorium Medik',  'id_kaprog' => 1872, 'created_at' => '2026-06-09 04:30:02', 'updated_at' => '2026-06-14 17:11:13'],
        ]);

        $this->db->query('ALTER TABLE tbl_jurusan AUTO_INCREMENT = 6');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE tbl_jurusan DROP FOREIGN KEY fk_jurusan_kaprog');
        $this->forge->dropTable('tbl_jurusan', true);
    }
}