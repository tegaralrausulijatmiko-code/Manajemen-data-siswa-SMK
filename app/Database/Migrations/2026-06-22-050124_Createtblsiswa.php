<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblSiswa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_siswa' => [
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
            'nisn' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
            ],
            'nama_siswa' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'jenis_kelamin' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
                'null'       => false,
            ],
            'alamat' => [
                'type'    => 'TEXT',
                'null'    => true,
                'default' => null,
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => true,
                'default'    => null,
            ],
            'foto' => [
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

        $this->forge->addKey('id_siswa', true);
        $this->forge->addUniqueKey('nisn', 'uq_nisn');
        $this->forge->addKey('id_kelas', false, false, 'fk_siswa_kelas');

        $this->forge->createTable('tbl_siswa', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);

        // Foreign key
        $this->db->query('
            ALTER TABLE tbl_siswa
            ADD CONSTRAINT fk_siswa_kelas
            FOREIGN KEY (id_kelas) REFERENCES tbl_kelas(id_kelas)
            ON UPDATE CASCADE
        ');

        // Seed data
        $this->db->table('tbl_siswa')->insertBatch([
            ['id_siswa' =>  3, 'id_kelas' =>  3, 'nisn' => '0012000001', 'nama_siswa' => 'Samni Marito Lestian Panjaitan', 'jenis_kelamin' => 'L', 'alamat' => 'Jakarta Timur', 'no_hp' => '08253363324', 'foto' => '1781497906_27e3144773e087e526e3.jpg', 'created_at' => '2026-06-05 17:40:28', 'updated_at' => '2026-06-15 04:52:21'],
            ['id_siswa' =>  4, 'id_kelas' =>  2, 'nisn' => '0012000002', 'nama_siswa' => 'Meylinda Zelin',                 'jenis_kelamin' => 'P', 'alamat' => 'Cilangkap',     'no_hp' => '08345354323', 'foto' => '1781497918_382bd398afc87b79336c.jpg', 'created_at' => '2026-06-09 04:25:57', 'updated_at' => '2026-06-15 04:31:58'],
            ['id_siswa' =>  6, 'id_kelas' =>  2, 'nisn' => '0012000004', 'nama_siswa' => 'Riski Dwiyanto',                 'jenis_kelamin' => 'L', 'alamat' => 'Pondok Gede',   'no_hp' => '087698765511','foto' => '1781497935_84d47d8c7d3205b96427.jpg', 'created_at' => '2026-06-11 07:04:43', 'updated_at' => '2026-06-15 04:47:30'],
            ['id_siswa' =>  7, 'id_kelas' =>  6, 'nisn' => '0012000005', 'nama_siswa' => 'Canggih Wibowo',                 'jenis_kelamin' => 'L', 'alamat' => 'Depok',         'no_hp' => '087545671890','foto' => '1781497944_926906babb3e03cee46f.jpg', 'created_at' => '2026-06-13 10:07:35', 'updated_at' => '2026-06-15 04:51:46'],
            ['id_siswa' =>  8, 'id_kelas' =>  9, 'nisn' => '0012000006', 'nama_siswa' => 'Nohan Aurel',                    'jenis_kelamin' => 'L', 'alamat' => 'Pengasinan',    'no_hp' => '087283421234','foto' => '1781497953_3dd1bd9716971256f336.jpg', 'created_at' => '2026-06-14 12:59:43', 'updated_at' => '2026-06-20 16:21:19'],
            ['id_siswa' =>  9, 'id_kelas' => 11, 'nisn' => '0012000007', 'nama_siswa' => 'Tegar',                          'jenis_kelamin' => 'L', 'alamat' => 'Pondok Indah',  'no_hp' => '089654371288','foto' => '1781499031_53763be8685ea63d0b3c.jpg', 'created_at' => '2026-06-15 04:50:31', 'updated_at' => '2026-06-15 04:50:31'],
            ['id_siswa' => 10, 'id_kelas' => 10, 'nisn' => '0012000008', 'nama_siswa' => 'Firmansyah',                     'jenis_kelamin' => 'L', 'alamat' => 'Bogor',         'no_hp' => '081247581233','foto' => '1781499066_a1a6288d26f7df928df4.jpg', 'created_at' => '2026-06-15 04:51:06', 'updated_at' => '2026-06-15 07:35:29'],
            ['id_siswa' => 11, 'id_kelas' => 12, 'nisn' => '0012000003', 'nama_siswa' => 'Rizky Dharmawan',                'jenis_kelamin' => 'L', 'alamat' => 'CIpayung',      'no_hp' => '08976583465', 'foto' => '1781972489_7d1d53d67a1dc187ec32.jpg', 'created_at' => '2026-06-20 16:21:02', 'updated_at' => '2026-06-20 16:21:29'],
        ]);

        $this->db->query('ALTER TABLE tbl_siswa AUTO_INCREMENT = 12');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE tbl_siswa DROP FOREIGN KEY fk_siswa_kelas');
        $this->forge->dropTable('tbl_siswa', true);
    }
}