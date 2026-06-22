<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table      = 'tbl_absensi';
    protected $primaryKey = 'id_absensi';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_siswa', 'id_kelas', 'id_jadwal', 'tanggal', 'status', 'keterangan',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAll(?string $keyword = null, ?string $tanggal = null, ?string $id_kelas = null): array
    {
        $builder = $this->db->table('tbl_absensi a')
            ->select('a.*, s.nisn, s.nama_siswa, k.nama_kelas, jd.hari, jd.jam_mulai, jd.jam_selesai, m.nama_mapel, g.nama_guru')
            ->join('tbl_siswa s', 's.id_siswa = a.id_siswa', 'left')
            ->join('tbl_kelas k', 'k.id_kelas = a.id_kelas', 'left')
            ->join('tbl_jadwal jd', 'jd.id_jadwal = a.id_jadwal', 'left')
            ->join('tbl_mata_pelajaran m', 'm.id_mapel = jd.id_mapel', 'left')
            ->join('tbl_guru g', 'g.id_guru = jd.id_guru', 'left');
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

    public function getByDateAndKelas(string $tanggal, ?string $id_kelas = null): array
    {
        $builder = $this->db->table('tbl_absensi a')
            ->select('a.*, s.id_siswa, s.nisn, s.nama_siswa, k.nama_kelas')
            ->join('tbl_siswa s', 's.id_siswa = a.id_siswa', 'left')
            ->join('tbl_kelas k', 'k.id_kelas = a.id_kelas', 'left')
            ->where('a.tanggal', $tanggal);

        if ($id_kelas) {
            $builder->where('a.id_kelas', $id_kelas);
        }

        $rows = $builder->get()->getResultArray();
        return array_column($rows, null, 'id_siswa');
    }

    public function getByJadwalDate(int $idJadwal, string $tanggal): array
    {
        $rows = $this->where('id_jadwal', $idJadwal)
            ->where('tanggal', $tanggal)
            ->findAll();

        $mapped = [];
        foreach ($rows as $row) {
            $mapped[$row['id_siswa']] = $row;
        }

        return $mapped;
    }

    public function findByStudentScheduleDate(int $idSiswa, int $idJadwal, string $tanggal): ?array
    {
        return $this->where('id_siswa', $idSiswa)
            ->where('id_jadwal', $idJadwal)
            ->where('tanggal', $tanggal)
            ->first();
    }

    public function getRekap(array $filters = []): array
    {
        $builder = $this->db->table('tbl_absensi a')
            ->select('a.*, s.nisn, s.nama_siswa, k.nama_kelas, jd.hari, jd.jam_mulai, jd.jam_selesai, m.nama_mapel, g.nama_guru')
            ->join('tbl_siswa s', 's.id_siswa = a.id_siswa', 'left')
            ->join('tbl_kelas k', 'k.id_kelas = a.id_kelas', 'left')
            ->join('tbl_jadwal jd', 'jd.id_jadwal = a.id_jadwal', 'left')
            ->join('tbl_mata_pelajaran m', 'm.id_mapel = jd.id_mapel', 'left')
            ->join('tbl_guru g', 'g.id_guru = jd.id_guru', 'left');

        if (! empty($filters['tanggal_awal'])) {
            $builder->where('a.tanggal >=', $filters['tanggal_awal']);
        }

        if (! empty($filters['tanggal_akhir'])) {
            $builder->where('a.tanggal <=', $filters['tanggal_akhir']);
        }

        if (! empty($filters['id_kelas'])) {
            $builder->where('a.id_kelas', $filters['id_kelas']);
        }

        if (! empty($filters['id_jadwal'])) {
            $builder->where('a.id_jadwal', $filters['id_jadwal']);
        }

        if (! empty($filters['status'])) {
            $builder->where('a.status', $filters['status']);
        }

        return $builder
            ->orderBy('a.tanggal', 'DESC')
            ->orderBy("FIELD(jd.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')", '', false)
            ->orderBy('jd.jam_mulai', 'ASC')
            ->orderBy('s.nama_siswa', 'ASC')
            ->get()
            ->getResultArray();
    }
}
