<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\JurusanModel;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use App\Models\MapelModel;
use App\Models\JadwalModel;
use App\Models\AbsensiModel;


class Dashboard extends BaseController
{
    public function index()
    {
        $guruModel   = new GuruModel();
        $jurusanModel = new JurusanModel();
        $kelasModel  = new KelasModel();
        $siswaModel  = new SiswaModel();
        $mapelModel  = new MapelModel();
        $jadwalModel = new JadwalModel();
        $absensiModel = new AbsensiModel();

        // Stats
        $stats = [
            'guru'    => $guruModel->countAll(),
            'jurusan' => $jurusanModel->countAll(),
            'kelas'   => $kelasModel->countAll(),
            'siswa'   => $siswaModel->countAll(),
            'mapel'   => $mapelModel->countAll(),
            'jadwal' => $jadwalModel->countAll(),
            'absensi' => $absensiModel->countAll(),
        ];

        // Jurusan list dengan jumlah kelas dan siswa
        $jurusan_list = $jurusanModel->getJurusanWithStats();
        $siswa_terbaru = $siswaModel->getLatestWithKelas(5);

        return view('dashboard/dashboard', [
            'stats'        => $stats,
            'jurusan_list' => $jurusan_list,
            'siswa_terbaru' => $siswa_terbaru,
        ]);
    }
}
