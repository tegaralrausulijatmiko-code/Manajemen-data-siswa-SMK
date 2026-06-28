<?php

namespace App\Models;

use CodeIgniter\Model;

class MapelModel extends Model
{
    protected $table      = 'tbl_mata_pelajaran';
    protected $primaryKey = 'id_mapel';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nama_mapel',
        'tingkat',
        'jenis',
        'id_jurusan',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAll(?string $keyword = null): array
    {
        $builder = $this->db->table('tbl_mata_pelajaran m')
            ->select("
                m.*,
                COALESCE(j.nama_jurusan, 'Semua Jurusan') AS nama_jurusan
            ")
            ->join('tbl_jurusan j', 'j.id_jurusan = m.id_jurusan', 'left');

        if ($keyword) {
            $builder->groupStart()
                ->like('m.nama_mapel', $keyword)
                ->orLike('m.tingkat', $keyword)
                ->orLike('m.jenis', $keyword)
                ->orLike('j.nama_jurusan', $keyword)
                ->groupEnd();
        }

        return $builder->get()->getResultArray();
    }
}