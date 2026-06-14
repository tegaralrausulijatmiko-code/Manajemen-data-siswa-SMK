<?php

namespace App\Models;

use CodeIgniter\Model;

class TahunAjaranModel extends Model
{
    protected $table      = 'tbl_tahun_ajaran';
    protected $primaryKey = 'id_tahun_ajaran';
    protected $returnType = 'array';

    protected $allowedFields = [
        'tahun_ajaran', 'semester', 'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAktif(): ?array
    {
        return $this->where('status', 'Aktif')->first();
    }
}
