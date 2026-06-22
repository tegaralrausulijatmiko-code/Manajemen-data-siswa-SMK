<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblMataPelajaran extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_mapel' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'kode_mapel' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => false,
            ],
            'nama_mapel' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Produktif', 'Non Produktif'],
                'null'       => false,
                'default'    => 'Non Produktif',
            ],
            'tingkat' => [
                'type'       => 'ENUM',
                'constraint' => ['X', 'XI', 'XII'],
                'null'       => false,
                'default'    => 'X',
            ],
            'id_guru' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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

        $this->forge->addKey('id_mapel', true);
        $this->forge->addUniqueKey('kode_mapel', 'uq_kode_mapel');

        $this->forge->createTable('tbl_mata_pelajaran', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);

        // Seed data
        $this->db->table('tbl_mata_pelajaran')->insert([
            'id_mapel'   => 6,
            'kode_mapel' => '177',
            'nama_mapel' => 'Pemrograman Dasar',
            'status'     => 'Produktif',
            'tingkat'    => 'X',
            'id_guru'    => 1872,
            'created_at' => '2026-06-09 05:19:17',
            'updated_at' => '2026-06-20 16:48:38',
        ]);

        $this->db->query('ALTER TABLE tbl_mata_pelajaran AUTO_INCREMENT = 10');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_mata_pelajaran', true);
    }
}