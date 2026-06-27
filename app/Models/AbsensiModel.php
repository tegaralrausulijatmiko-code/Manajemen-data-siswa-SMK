<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table      = 'tbl_absensi';
    protected $primaryKey = 'id_absensi';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_siswa',
        'id_kelas',
        'id_jadwal',
        'jenis',
        'tanggal',
        'status',
        'keterangan',
    ];

    // -------------------------------------------------------------------------
    // Lookup helpers
    // -------------------------------------------------------------------------

    /**
     * Cari satu record absensi berdasarkan siswa + jadwal + tanggal (untuk absen mapel).
     */
    public function findByStudentScheduleDate(int $idSiswa, int $idJadwal, string $tanggal): ?array
    {
        return $this->where([
            'id_siswa'  => $idSiswa,
            'id_jadwal' => $idJadwal,
            'tanggal'   => $tanggal,
        ])->first();
    }

    /**
     * Cari satu record absensi harian berdasarkan siswa + tanggal (tanpa jadwal).
     */
    public function findHarian(int $idSiswa, string $tanggal): ?array
    {
        return $this->where([
            'id_siswa' => $idSiswa,
            'jenis'    => 'harian',
            'tanggal'  => $tanggal,
        ])->first();
    }

    // -------------------------------------------------------------------------
    // Fetch untuk form input
    // -------------------------------------------------------------------------

    /**
     * Ambil map [id_siswa => absensi] berdasarkan jadwal + tanggal (absen mapel).
     */
    public function getByJadwalDate(int $idJadwal, string $tanggal): array
    {
        $rows = $this->where([
            'id_jadwal' => $idJadwal,
            'tanggal'   => $tanggal,
        ])->findAll();

        $map = [];
        foreach ($rows as $row) {
            $map[$row['id_siswa']] = $row;
        }
        return $map;
    }

    /**
     * Ambil map [id_siswa => absensi] harian berdasarkan kelas + tanggal.
     */
    public function getHarianByKelasDate(int $idKelas, string $tanggal): array
    {
        $rows = $this->where([
            'id_kelas' => $idKelas,
            'jenis'    => 'harian',
            'tanggal'  => $tanggal,
        ])->findAll();

        $map = [];
        foreach ($rows as $row) {
            $map[$row['id_siswa']] = $row;
        }
        return $map;
    }

    /**
     * Ambil map [id_siswa => absensi] berdasarkan tanggal dan kelas (admin).
     */
    public function getByDateAndKelas(string $tanggal, ?string $idKelas = null): array
    {
        $builder = $this->where('tanggal', $tanggal);

        if ($idKelas) {
            $builder->where('id_kelas', $idKelas);
        }

        $rows = $builder->findAll();

        $map = [];
        foreach ($rows as $row) {
            $map[$row['id_siswa']] = $row;
        }
        return $map;
    }

    // -------------------------------------------------------------------------
    // Rekap / laporan
    // -------------------------------------------------------------------------

    /**
     * Rekap absensi dengan join lengkap, support filter fleksibel.
     *
     * Filter keys: id_siswa, id_kelas, id_jadwal, id_guru, jenis,
     *              status, tanggal_awal, tanggal_akhir, bulan, tahun
     */
    public function getRekap(array $filters = []): array
    {
        $db = db_connect();

        $sql = "
            SELECT
                a.id_absensi,
                a.tanggal,
                a.status,
                a.keterangan,
                a.jenis,
                s.id_siswa,
                s.nisn,
                s.nama_siswa,
                k.id_kelas,
                k.nama_kelas,
                j.id_jadwal,
                j.hari,
                j.jam_mulai,
                j.jam_selesai,
                mp.nama_mapel,
                g.nama_guru
            FROM tbl_absensi a
            LEFT JOIN tbl_siswa      s  ON s.id_siswa  = a.id_siswa
            LEFT JOIN tbl_kelas      k  ON k.id_kelas  = a.id_kelas
            LEFT JOIN tbl_jadwal     j  ON j.id_jadwal = a.id_jadwal
            LEFT JOIN tbl_mata_pelajaran mp ON mp.id_mapel = j.id_mapel
            LEFT JOIN tbl_guru       g  ON g.id_guru   = COALESCE(j.id_guru, k.id_wali_kelas)
            WHERE 1=1
        ";

        $params = [];

        if (! empty($filters['id_siswa'])) {
            $sql     .= ' AND a.id_siswa = ?';
            $params[] = $filters['id_siswa'];
        }

        if (! empty($filters['id_kelas'])) {
            $sql     .= ' AND a.id_kelas = ?';
            $params[] = $filters['id_kelas'];
        }

        if (! empty($filters['id_jadwal'])) {
            $sql     .= ' AND a.id_jadwal = ?';
            $params[] = $filters['id_jadwal'];
        }

        if (! empty($filters['id_guru'])) {
            $sql     .= ' AND (j.id_guru = ? OR k.id_wali_kelas = ?)';
            $params[] = $filters['id_guru'];
            $params[] = $filters['id_guru'];
        }

        if (! empty($filters['jenis'])) {
            $sql     .= ' AND a.jenis = ?';
            $params[] = $filters['jenis'];
        }

        if (! empty($filters['status'])) {
            $sql     .= ' AND a.status = ?';
            $params[] = $filters['status'];
        }

        if (! empty($filters['tanggal_awal'])) {
            $sql     .= ' AND a.tanggal >= ?';
            $params[] = $filters['tanggal_awal'];
        }

        if (! empty($filters['tanggal_akhir'])) {
            $sql     .= ' AND a.tanggal <= ?';
            $params[] = $filters['tanggal_akhir'];
        }

        $sql .= ' ORDER BY a.tanggal DESC, k.nama_kelas, s.nama_siswa';

        $query = $db->query($sql, $params);
        return $query ? $query->getResultArray() : [];
    }

    /**
     * Rekap bulanan per siswa (untuk BK / wali kelas).
     */
    public function rekapBulanan(int $idKelas, int $bulan, int $tahun, string $jenis = 'harian'): array
    {
        $db    = db_connect();
        $start = sprintf('%04d-%02d-01', $tahun, $bulan);
        $end   = date('Y-m-t', strtotime($start));

        $sql = "
            SELECT
                s.id_siswa,
                s.nisn,
                s.nama_siswa,
                SUM(a.status = 'Hadir') AS hadir,
                SUM(a.status = 'Izin')  AS izin,
                SUM(a.status = 'Sakit') AS sakit,
                SUM(a.status = 'Alpha')  AS Alpha,
                COUNT(a.id_absensi)     AS total
            FROM tbl_siswa s
            LEFT JOIN tbl_absensi a
                ON  a.id_siswa = s.id_siswa
                AND a.id_kelas = ?
                AND a.jenis    = ?
                AND a.tanggal BETWEEN ? AND ?
            WHERE s.id_kelas = ?
            GROUP BY s.id_siswa, s.nisn, s.nama_siswa
            ORDER BY s.nama_siswa
        ";

        $query = $db->query($sql, [$idKelas, $jenis, $start, $end, $idKelas]);
        return $query ? $query->getResultArray() : [];
    }

    /**
     * Data untuk export per pertemuan (jadwal mapel).
     */
    public function dataExportPertemuan(int $idJadwal, string $tanggalAwal, string $tanggalAkhir): array
    {
        $db  = db_connect();
        $sql = "
            SELECT
                a.tanggal,
                a.status,
                a.keterangan,
                s.nisn,
                s.nama_siswa
            FROM tbl_absensi a
            JOIN tbl_siswa s ON s.id_siswa = a.id_siswa
            WHERE a.id_jadwal = ?
              AND a.tanggal BETWEEN ? AND ?
            ORDER BY a.tanggal, s.nama_siswa
        ";

        $query = $db->query($sql, [$idJadwal, $tanggalAwal, $tanggalAkhir]);
        return $query ? $query->getResultArray() : [];
    }
}