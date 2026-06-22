<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblGuru extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_guru' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'nip' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
            ],
            'nama_guru' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'jenis_kelamin' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
                'null'       => false,
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => true,
                'default'    => null,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
                'default' => null,
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

        $this->forge->addKey('id_guru', true); // PRIMARY KEY
        $this->forge->addUniqueKey('nip', 'uq_nip');

        $this->forge->createTable('tbl_guru', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);

        // Seed data awal
        $this->db->table('tbl_guru')->insertBatch([
            [
                'id_guru'       => 1872,
                'nip'           => '1987654324',
                'nama_guru'     => 'Sinta',
                'jenis_kelamin' => 'P',
                'no_hp'         => '08564123492',
                'alamat'        => 'Cibubur',
                'created_at'    => '2026-06-13 14:12:38',
                'updated_at'    => '2026-06-13 14:12:38',
            ],
            [
                'id_guru'       => 1975,
                'nip'           => '1987654323',
                'nama_guru'     => 'Bambang',
                'jenis_kelamin' => 'L',
                'no_hp'         => '086786568646',
                'alamat'        => 'Bojong gede',
                'created_at'    => '2026-06-13 14:07:14',
                'updated_at'    => '2026-06-13 14:07:29',
            ],
        ]);

        // Set AUTO_INCREMENT ke 1976
        $this->db->query('ALTER TABLE tbl_guru AUTO_INCREMENT = 1976');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_guru', true);
    }
}