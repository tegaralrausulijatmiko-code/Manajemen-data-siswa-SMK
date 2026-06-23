<?php

namespace App\Models;

use CodeIgniter\Model;

class MapelModel extends Model
{
    protected $table      = 'tbl_mata_pelajaran';
    protected $primaryKey = 'id_mapel';
    protected $returnType = 'array';

    protected $allowedFields = [
        'kode_mapel', 'nama_mapel', 'status', 'tingkat', 'id_guru',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAll(?string $keyword = null, ?string $status = null): array
    {
        $builder = $this->db->table('tbl_mata_pelajaran m')
            ->select('m.*, m.tingkat, g.nama_guru')
            ->join('tbl_guru g', 'g.id_guru = m.id_guru', 'left');

        if ($keyword) {
            $builder->groupStart()
                ->like('m.nama_mapel', $keyword)
                ->orLike('m.kode_mapel', $keyword)
                ->orLike('m.tingkat', $keyword)
                ->orLike('g.nama_guru', $keyword)
            ->groupEnd();
        }

        if ($status) {
            $builder->where('m.status', $status);
        }

        return $builder->orderBy('m.kode_mapel')->get()->getResultArray();
    }

    public function getNextKodeMapel(): string
    {
        $attempt = 0;

        do {
            $attempt++;
            $kode = (string) random_int(120, 199);
            $exists = $this->where('kode_mapel', $kode)->countAllResults();

            if ($attempt >= 10) {
                break;
            }
        } while ($exists > 0);

        return $kode;
    }
}
