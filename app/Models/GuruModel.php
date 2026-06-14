<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruModel extends Model
{
    protected $table      = 'tbl_guru';
    protected $primaryKey = 'id_guru';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nip', 'nama_guru', 'jenis_kelamin', 'no_hp', 'alamat',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function search(string $keyword): array
    {
        return $this->groupStart()
                    ->like('nama_guru', $keyword)
                    ->orLike('nip', $keyword)
                    ->groupEnd()
                    ->findAll();
    }
}
