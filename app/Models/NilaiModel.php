<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiModel extends Model
{
    protected $table      = 'tbl_nilai';
    protected $primaryKey = 'id_nilai';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_siswa', 'id_mapel', 'id_tahun_ajaran', 'nilai_tugas', 'nilai_uts', 'nilai_uas', 'nilai_akhir', 'predikat',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAll(?string $keyword = null, ?string $id_tahun_ajaran = null): array
    {
        $builder = $this->db->table('tbl_nilai n')
            ->select('n.*, s.nisn, s.nama_siswa, k.nama_kelas, m.kode_mapel, m.nama_mapel, ta.tahun_ajaran, ta.semester')
            ->join('tbl_siswa s', 's.id_siswa = n.id_siswa', 'left')
            ->join('tbl_kelas k', 'k.id_kelas = s.id_kelas', 'left')
            ->join('tbl_mata_pelajaran m', 'm.id_mapel = n.id_mapel', 'left')
            ->join('tbl_tahun_ajaran ta', 'ta.id_tahun_ajaran = n.id_tahun_ajaran', 'left');

        if ($keyword) {
            $builder->groupStart()
                ->like('s.nama_siswa', $keyword)
                ->orLike('s.nisn', $keyword)
                ->orLike('m.nama_mapel', $keyword)
                ->orLike('k.nama_kelas', $keyword)
            ->groupEnd();
        }

        if ($id_tahun_ajaran) {
            $builder->where('n.id_tahun_ajaran', $id_tahun_ajaran);
        }

        return $builder->orderBy('s.nama_siswa')->orderBy('m.nama_mapel')->get()->getResultArray();
    }
}
