<?php

namespace App\Models;

use CodeIgniter\Model;

class MapelModel extends Model
{
    protected $table      = 'tbl_mata_pelajaran';
    protected $primaryKey = 'id_mapel';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nama_mapel', 'status', 
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAll(?string $keyword = null, ?string $status = null): array
    {
        $builder = $this->db->table('tbl_mata_pelajaran m');

        if ($keyword) {
            $builder->groupStart()
                ->like('m.nama_mapel', $keyword)
            ->groupEnd();
        }

        if ($status) {
            $builder->where('m.status', $status);
        }

        return $builder->get()->getResultArray();
    }

}
