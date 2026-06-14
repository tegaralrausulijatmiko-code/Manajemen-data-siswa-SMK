<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table      = 'tbl_siswa';
    protected $primaryKey = 'id_siswa';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_kelas', 'nisn', 'nama_siswa', 'jenis_kelamin',
        'alamat', 'no_hp', 'foto',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAll(?string $keyword = null, ?string $id_kelas = null, ?string $jk = null): array
    {
        $builder = $this->db->table('tbl_siswa s')
            ->select('s.*, k.nama_kelas, j.kode_jurusan, j.nama_jurusan')
            ->join('tbl_kelas k', 'k.id_kelas = s.id_kelas', 'left')
            ->join('tbl_jurusan j', 'j.id_jurusan = k.id_jurusan', 'left');

        if ($keyword) {
            $builder->groupStart()
                ->like('s.nama_siswa', $keyword)
                ->orLike('s.nisn', $keyword)
                ->orLike('k.nama_kelas', $keyword)
            ->groupEnd();
        }

        if ($id_kelas) {
            $builder->where('s.id_kelas', $id_kelas);
        }

        if ($jk) {
            $builder->where('s.jenis_kelamin', $jk);
        }

        return $builder->orderBy('k.nama_kelas')->orderBy('s.nama_siswa')->get()->getResultArray();
    }
    public function getLatestWithKelas(int $limit = 5): array
    {
        return $this->db->table('tbl_siswa s')
            ->select('s.*, k.nama_kelas, j.kode_jurusan, j.nama_jurusan')
            ->join('tbl_kelas k', 'k.id_kelas = s.id_kelas', 'left')
            ->join('tbl_jurusan j', 'j.id_jurusan = k.id_jurusan', 'left')
            ->orderBy('s.created_at', 'DESC')
            ->orderBy('s.id_siswa', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}
