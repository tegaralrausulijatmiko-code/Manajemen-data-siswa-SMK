<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalModel extends Model
{
    protected $table      = 'tbl_jadwal';
    protected $primaryKey = 'id_jadwal';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_kelas', 'id_mapel', 'id_guru', 'hari', 'jam_mulai', 'jam_selesai',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAll(?string $keyword = null, ?string $id_kelas = null, ?string $id_guru = null): array
    {
        $builder = $this->db->table('tbl_jadwal jd')
            ->select('jd.*, k.nama_kelas, m.nama_mapel, m.kode_mapel, g.nama_guru')
            ->join('tbl_kelas k', 'k.id_kelas = jd.id_kelas', 'left')
            ->join('tbl_mata_pelajaran m', 'm.id_mapel = jd.id_mapel', 'left')
            ->join('tbl_guru g', 'g.id_guru = jd.id_guru', 'left');

        if ($keyword) {
            $builder->groupStart()
                ->like('k.nama_kelas', $keyword)
                ->orLike('m.nama_mapel', $keyword)
                ->orLike('g.nama_guru', $keyword)
            ->groupEnd();
        }

        if ($id_kelas) {
            $builder->where('jd.id_kelas', $id_kelas);
        }

        if ($id_guru) {
            $builder->where('jd.id_guru', $id_guru);
        }

        return $builder
            ->orderBy("FIELD(jd.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')", '', false)
            ->orderBy('jd.jam_mulai', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getKelasByGuru(int $idGuru): array
    {
        return $this->db->table('tbl_jadwal jd')
            ->select('k.id_kelas, k.nama_kelas, j.kode_jurusan, j.nama_jurusan')
            ->join('tbl_kelas k', 'k.id_kelas = jd.id_kelas', 'left')
            ->join('tbl_jurusan j', 'j.id_jurusan = k.id_jurusan', 'left')
            ->where('jd.id_guru', $idGuru)
            ->groupBy('k.id_kelas, k.nama_kelas, j.kode_jurusan, j.nama_jurusan')
            ->orderBy('k.nama_kelas')
            ->get()
            ->getResultArray();
    }

    public function getDetailForGuru(int $idJadwal, int $idGuru): ?array
    {
        return $this->db->table('tbl_jadwal jd')
            ->select('jd.*, k.nama_kelas, m.nama_mapel, m.kode_mapel, g.nama_guru')
            ->join('tbl_kelas k', 'k.id_kelas = jd.id_kelas', 'left')
            ->join('tbl_mata_pelajaran m', 'm.id_mapel = jd.id_mapel', 'left')
            ->join('tbl_guru g', 'g.id_guru = jd.id_guru', 'left')
            ->where('jd.id_jadwal', $idJadwal)
            ->where('jd.id_guru', $idGuru)
            ->get()
            ->getRowArray();
    }

    public function getDetail(int $id): ?array
    {
        return $this->db->table('tbl_jadwal jd')
            ->select('jd.*, k.nama_kelas, m.nama_mapel, m.kode_mapel, g.nama_guru')
            ->join('tbl_kelas k', 'k.id_kelas = jd.id_kelas', 'left')
            ->join('tbl_mata_pelajaran m', 'm.id_mapel = jd.id_mapel', 'left')
            ->join('tbl_guru g', 'g.id_guru = jd.id_guru', 'left')
            ->where('jd.id_jadwal', $id)
            ->get()
            ->getRowArray();
    }

    public function getGuruList()
    {
        return $this->db->table('tbl_guru')
            ->select('id_guru, nama_guru')
            ->orderBy('nama_guru', 'ASC')
            ->get()
            ->getResultArray();
    }
}
