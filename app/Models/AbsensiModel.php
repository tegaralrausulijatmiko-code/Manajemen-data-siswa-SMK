<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table      = 'tbl_absensi';
    protected $primaryKey = 'id_absensi';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_siswa', 'id_kelas', 'id_tahun_ajaran', 'tanggal', 'status', 'keterangan',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAll(?string $keyword = null, ?string $tanggal = null, ?string $id_kelas = null): array
    {
        $builder = $this->db->table('tbl_absensi a')
            ->select('a.*, s.nisn, s.nama_siswa, k.nama_kelas, ta.tahun_ajaran, ta.semester')
            ->join('tbl_siswa s', 's.id_siswa = a.id_siswa', 'left')
            ->join('tbl_kelas k', 'k.id_kelas = a.id_kelas', 'left')
            ->join('tbl_tahun_ajaran ta', 'ta.id_tahun_ajaran = a.id_tahun_ajaran', 'left');

        if ($keyword) {
            $builder->groupStart()
                ->like('s.nama_siswa', $keyword)
                ->orLike('s.nisn', $keyword)
                ->orLike('k.nama_kelas', $keyword)
            ->groupEnd();
        }

        if ($tanggal) {
            $builder->where('a.tanggal', $tanggal);
        }

        if ($id_kelas) {
            $builder->where('a.id_kelas', $id_kelas);
        }

        return $builder->orderBy('a.tanggal', 'DESC')->orderBy('s.nama_siswa')->get()->getResultArray();
    }
}
