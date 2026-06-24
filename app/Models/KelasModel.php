<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table      = 'tbl_kelas';
    protected $primaryKey = 'id_kelas';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_jurusan', 'nama_kelas', 'tingkat', 'id_wali_kelas', 'jumlah_siswa',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAll(?string $keyword = null, ?string $tingkat = null): array
    {
        $builder = $this->db->table('tbl_kelas k')
            ->select('k.*, j.nama_jurusan, j.kode_jurusan, g.nama_guru')
            ->join('tbl_jurusan j', 'j.id_jurusan = k.id_jurusan', 'left')
            ->join('tbl_guru g', 'g.id_guru = k.id_wali_kelas', 'left');

        if ($keyword) {
            $builder->groupStart()
                ->like('k.nama_kelas', $keyword)
                ->orLike('j.nama_jurusan', $keyword)
                ->orLike('g.nama_guru', $keyword)
            ->groupEnd();
        }

        if ($tingkat) {
            $builder->where('k.tingkat', $tingkat);
        }

        return $builder->orderBy('k.tingkat')->orderBy('j.kode_jurusan')->orderBy('k.nama_kelas')->get()->getResultArray();
    }

    public function getKelasWithJurusan(): array
    {
        return $this->db->table('tbl_kelas k')
            ->select('k.id_kelas, k.nama_kelas, j.nama_jurusan')
            ->join('tbl_jurusan j', 'j.id_jurusan = k.id_jurusan', 'left')
            ->orderBy('k.tingkat')->orderBy('k.nama_kelas')
            ->get()->getResultArray();
    }

    public function isNamaKelasTaken(string $namaKelas, ?int $excludeId = null): bool
    {
        $builder = $this->builder()->where('nama_kelas', $namaKelas);

        if ($excludeId !== null) {
            $builder->where('id_kelas !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    public function isWaliKelasUsed(int $idGuru, ?int $excludeId = null): bool
    {
        $builder = $this->builder()->where('id_wali_kelas', $idGuru);

        if ($excludeId !== null) {
            $builder->where('id_kelas !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    // public function getJumSiswa(): int
    // {
    //     return random_int(30, 40);
    // }
    
    public function syncJumlahSiswa(int $id_kelas): void
    {
        $jumlah = $this->db->table('tbl_siswa')
            ->where('id_kelas', $id_kelas)
            ->countAllResults();

        $this->update($id_kelas, ['jumlah_siswa' => $jumlah]);
    }

    public function getWithSiswa(int $id): ?array
    {
        $kelas = $this->db->table('tbl_kelas k')
            ->select('k.*, j.nama_jurusan, j.kode_jurusan, g.nama_guru')
            ->join('tbl_jurusan j', 'j.id_jurusan = k.id_jurusan', 'left')
            ->join('tbl_guru g', 'g.id_guru = k.id_wali_kelas', 'left')
            ->where('k.id_kelas', $id)
            ->get()->getRowArray();

        if (! $kelas) return null;

        $siswa = $this->db->table('tbl_siswa')
            ->where('id_kelas', $id)
            ->orderBy('nama_siswa', 'ASC')
            ->get()->getResultArray();

        $kelas['siswa']         = $siswa;
        $kelas['jumlah_siswa']  = count($siswa);
        $kelas['jumlah_laki']   = count(array_filter($siswa, fn($s) => $s['jenis_kelamin'] === 'L'));
        $kelas['jumlah_perempuan'] = count(array_filter($siswa, fn($s) => $s['jenis_kelamin'] === 'P'));

        return $kelas;
    }
}