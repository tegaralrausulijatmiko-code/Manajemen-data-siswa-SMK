<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'tbl_users';
    protected $primaryKey = 'id_user';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nama', 'username', 'password', 'role', 'status', 'id_guru', 'id_siswa',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
