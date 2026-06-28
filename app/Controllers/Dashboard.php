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
        // =========================================================================
        // DASHBOARD GURU
        // =========================================================================
        if (session()->get('role') === 'guru') {
            $idGuru = session()->get('id_guru');
            
            $jadwalModel = new JadwalModel();
            $kelasModel  = new KelasModel();

            $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $hariIni = $days[date('w')];

            $allJadwal = $jadwalModel->getAll(null, null, (string) $idGuru);
            
            $jadwalHariIni = array_filter($allJadwal, function($j) use ($hariIni) {
                return $j['hari'] === $hariIni;
            });

            $kelasDiajar = $jadwalModel->getKelasByGuru($idGuru);

            $kelasWali = $kelasModel->where('id_wali_kelas', $idGuru)->first();

            return view('dashboard/dashboard_guru', [
                'jadwal_hari_ini' => $jadwalHariIni,
                'hari_ini'         => $hariIni,
                'total_kelas'      => count($kelasDiajar),
                'kelas_wali'       => $kelasWali,
            ]);
        }

        // =========================================================================
        // DASHBOARD BK
        // =========================================================================
        if (session()->get('role') === 'bk') {
            return view('dashboard/dashboard_bk');
        }

                if (session()->get('role') === 'bk') {
            $absensiModel = new AbsensiModel();
            $siswaModel   = new SiswaModel();
            
            $today = date('Y-m-d');
            
            $statsHariIni = [
                'total_siswa' => $siswaModel->countAll(),
                'hadir'       => $absensiModel->where('tanggal', $today)->where('status', 'Hadir')->countAllResults(),
                'sakit'       => $absensiModel->where('tanggal', $today)->where('status', 'Sakit')->countAllResults(),
                'izin'        => $absensiModel->where('tanggal', $today)->where('status', 'Izin')->countAllResults(),
                'alpha'       => $absensiModel->where('tanggal', $today)->where('status', 'Alpha')->countAllResults(),
            ];

            $topAlpha = $absensiModel->getTopAlphaStudents(5);

            return view('dashboard/dashboard_bk', [
                'stats'     => $statsHariIni,
                'top_alpha' => $topAlpha,
            ]);
        }

        // =========================================================================
        // DASHBOARD SISWA 
        // =========================================================================
        //if (session()->get('role') === 'siswa') {
        //    return view('dashboard/dashboard_siswa');
        //}

        // =========================================================================
        // DASHBOARD ADMIN (Default)
        // =========================================================================
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
            'jadwal'  => $jadwalModel->countAll(),
            'absensi' => $absensiModel->countAll(),
        ];

        // Jurusan list dengan jumlah kelas dan siswa
        $jurusan_list = $jurusanModel->getJurusanWithStats();
        $siswa_terbaru = $siswaModel->getLatestWithKelas(5);

        return view('dashboard/dashboard', [
            'stats'         => $stats,
            'jurusan_list'  => $jurusan_list,
            'siswa_terbaru' => $siswa_terbaru,
        ]);
    }
}