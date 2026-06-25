<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBkRoleToUsers extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE tbl_users MODIFY role ENUM('admin','guru','siswa','bk') NOT NULL DEFAULT 'admin'");
    }

    public function down()
    {
        $this->db->query("UPDATE tbl_users SET role = 'admin' WHERE role = 'bk'");
        $this->db->query("ALTER TABLE tbl_users MODIFY role ENUM('admin','guru','siswa') NOT NULL DEFAULT 'admin'");
    }
}
