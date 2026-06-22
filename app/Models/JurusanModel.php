<?php

namespace App\Models;

use CodeIgniter\Model;

class JurusanModel extends Model
{
    protected $table      = 'tbl_jurusan';
    protected $primaryKey = 'id_jurusan';
    protected $returnType = 'array';

    protected $allowedFields = [
        'kode_jurusan', 'nama_jurusan', 'id_kaprog',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAll(): array
    {
        return $this->db->table('tbl_jurusan j')
            ->select('j.*, g.nama_guru,
                (SELECT COUNT(*) FROM tbl_kelas k WHERE k.id_jurusan = j.id_jurusan) AS jumlah_kelas')
            ->join('tbl_guru g', 'g.id_guru = j.id_kaprog', 'left')
            ->orderBy('j.kode_jurusan', 'ASC')
            ->get()->getResultArray();
    }

    public function search(string $keyword): array
    {
        return $this->db->table('tbl_jurusan j')
            ->select('j.*, g.nama_guru,
                (SELECT COUNT(*) FROM tbl_kelas k WHERE k.id_jurusan = j.id_jurusan) AS jumlah_kelas')
            ->join('tbl_guru g', 'g.id_guru = j.id_kaprog', 'left')
            ->groupStart()
                ->like('j.nama_jurusan', $keyword)
                ->orLike('j.kode_jurusan', $keyword)
                ->orLike('g.nama_guru', $keyword)
            ->groupEnd()
            ->orderBy('j.kode_jurusan')
            ->get()->getResultArray();
    }

    public function getUsedKaprogIds(?int $excludeJurusanId = null): array
    {
        $builder = $this->db->table('tbl_jurusan')
            ->select('id_kaprog')
            ->where('id_kaprog IS NOT NULL', null, false);

        if ($excludeJurusanId !== null) {
            $builder->where('id_jurusan !=', $excludeJurusanId);
        }

        return array_values(array_filter(array_column($builder->get()->getResultArray(), 'id_kaprog')));
    }

    public function isKaprogUsed(int $guruId, ?int $excludeJurusanId = null): bool
    {
        $builder = $this->db->table('tbl_jurusan')
            ->where('id_kaprog', $guruId);

        if ($excludeJurusanId !== null) {
            $builder->where('id_jurusan !=', $excludeJurusanId);
        }

        return $builder->countAllResults() > 0;
    }

    public function getJurusanWithStats(): array
    {
        return $this->db->table('tbl_jurusan j')
            ->select('j.*,
                (SELECT COUNT(*) FROM tbl_kelas k WHERE k.id_jurusan = j.id_jurusan) AS jumlah_kelas,
                (SELECT COUNT(*) FROM tbl_siswa s JOIN tbl_kelas k2 ON k2.id_kelas = s.id_kelas WHERE k2.id_jurusan = j.id_jurusan) AS jumlah_siswa')
            ->orderBy('j.kode_jurusan')
            ->get()->getResultArray();
    }
}
