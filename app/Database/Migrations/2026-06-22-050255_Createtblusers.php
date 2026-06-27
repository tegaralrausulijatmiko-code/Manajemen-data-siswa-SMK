<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Createtblusers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_user' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => null,
            ],
            'id_guru' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => null,
            ],
            'id_siswa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => null,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => false,
                'default' => 'current_timestamp()',
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => false,
                'default' => 'current_timestamp()',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => 'aktif',
            ],
        ]);

        $this->forge->addKey('id_user', true);

        $this->forge->createTable('tbl_users', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);

        // Seed: akun admin default
        $this->db->table('tbl_users')->insert([
            'id_user'    => 1,
            'username'   => 'admin',
            'nama'       => 'Administrator',
            'password'   => '$2y$10$SjgwuNpUMQZK3Ir.BNmeWO2qHv5cFSIe4tqCyvAh.G25A.t/bZPNW',
            'email'      => 'admin@sekolah.local',
            'role'       => 'admin',
            'id_guru'    => null,
            'id_siswa'   => null,
            'created_at' => '2026-06-14 12:52:39',
            'updated_at' => '2026-06-14 05:55:53',
            'status'     => 'aktif',
        ]);

        // Seed: akun BK 
        $this->db->table('tbl_users')->insert([
            'id_user'    => 2,
            'username'   => 'bk',
            'nama'       => 'Bimbingan Konseling',
            'password'   => '$2y$10$eJ.rVZQrQ5o6c2.5I2.7u.u.4Q5o6c2.5I2.7u.u.4Q5o6c2.5I2.7u.u',
            'email'      => 'bk@sekolah.local',
            'role'       => 'bk',
            'id_guru'    => null,
            'id_siswa'   => null,
            'created_at' => '2026-06-14 12:52:39',
            'updated_at' => '2026-06-14 05:55:53',
            'status'     => 'aktif',
        ]);

        $this->db->query('ALTER TABLE tbl_users AUTO_INCREMENT = 3');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_users', true);
    }
}