<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\JurusanModel;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use App\Models\MapelModel;
use App\Models\JadwalModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Import extends BaseController
{
    // ─── Download Template ────────────────────────────────────────────────────

    public function template(string $modul)
    {
        $map = [
            'guru'    => 'template_import_guru.xlsx',
            'jurusan' => 'template_import_jurusan.xlsx',
            'kelas'   => 'template_import_kelas.xlsx',
            'siswa'   => 'template_import_siswa.xlsx',
            'mapel'   => 'template_import_mapel.xlsx',
            'jadwal'  => 'template_import_jadwal.xlsx',
            'user'    => 'template_import_user.xlsx',
        ];

        if (! isset($map[$modul])) {
            return redirect()->back()->with('error', 'Template tidak ditemukan.');
        }

        $path = ROOTPATH . 'public/templates/' . $map[$modul];
        if (! file_exists($path)) {
            return redirect()->back()->with('error', 'File template belum tersedia di server.');
        }

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $map[$modul] . '"')
            ->setBody(file_get_contents($path));
    }

    // ─── Import Guru ─────────────────────────────────────────────────────────

    public function guru()
    {

        $file = $this->request->getFile('file_import');
        if (! $this->isValidFile($file)) {
            return redirect()->to(base_url('guru'))->with('error', 'File tidak valid. Gunakan format .xlsx atau .csv.');
        }

        $rows    = $this->readSpreadsheet($file);
        $model   = new GuruModel();
        $ok = $skip = $errors = 0;
        $errorList = [];

        foreach ($rows as $i => $row) {
            $rowNum = $i + 2; // baris Excel (header=1, contoh=2 dilewati, data mulai baris 3)
            $nip        = trim((string) ($row[0] ?? ''));
            $namaGuru   = trim((string) ($row[1] ?? ''));
            $jk         = strtoupper(trim((string) ($row[2] ?? '')));
            $noHp       = trim((string) ($row[3] ?? ''));
            $alamat     = trim((string) ($row[4] ?? ''));

            if ($nip === '' && $namaGuru === '') { $skip++; continue; }

            if (! preg_match('/^\d{18}$/', $nip)) {
                $errorList[] = "Baris $rowNum: NIP '$nip' harus 18 digit angka.";
                $errors++; continue;
            }
            if ($namaGuru === '') {
                $errorList[] = "Baris $rowNum: Nama guru tidak boleh kosong.";
                $errors++; continue;
            }
            if (! in_array($jk, ['L', 'P'])) {
                $errorList[] = "Baris $rowNum: Jenis kelamin '$jk' harus L atau P.";
                $errors++; continue;
            }
            if ($model->where('nip', $nip)->countAllResults() > 0) {
                $errorList[] = "Baris $rowNum: NIP '$nip' sudah ada, dilewati.";
                $skip++; continue;
            }

            $model->insert([
                'nip'          => $nip,
                'nama_guru'    => $namaGuru,
                'jenis_kelamin'=> $jk,
                'no_hp'        => $noHp,
                'alamat'       => $alamat,
            ]);
            $ok++;
        }

        return $this->redirectWithResult('guru', $ok, $skip, $errors, $errorList);
    }

    // ─── Import Jurusan ───────────────────────────────────────────────────────

    public function jurusan()
    {
        
        $file = $this->request->getFile('file_import');
        if (! $this->isValidFile($file)) {
            return redirect()->to(base_url('jurusan'))->with('error', 'File tidak valid. Gunakan format .xlsx atau .csv.');
        }

        $rows  = $this->readSpreadsheet($file);
        $model = new JurusanModel();
        $ok = $skip = $errors = 0;
        $errorList = [];

        foreach ($rows as $i => $row) {
            $rowNum      = $i + 2;
            $kodeJurusan = strtoupper(trim((string) ($row[0] ?? '')));
            $namaJurusan = trim((string) ($row[1] ?? ''));

            if ($kodeJurusan === '' && $namaJurusan === '') { $skip++; continue; }

            if ($kodeJurusan === '' || strlen($kodeJurusan) > 10) {
                $errorList[] = "Baris $rowNum: Kode jurusan '$kodeJurusan' tidak valid (maks 10 karakter).";
                $errors++; continue;
            }
            if ($namaJurusan === '') {
                $errorList[] = "Baris $rowNum: Nama jurusan tidak boleh kosong.";
                $errors++; continue;
            }
            if ($model->where('kode_jurusan', $kodeJurusan)->countAllResults() > 0) {
                $errorList[] = "Baris $rowNum: Kode '$kodeJurusan' sudah ada, dilewati.";
                $skip++; continue;
            }

            $model->insert([
                'kode_jurusan' => $kodeJurusan,
                'nama_jurusan' => $namaJurusan,
                'id_kaprog'    => null,
            ]);
            $ok++;
        }

        return $this->redirectWithResult('jurusan', $ok, $skip, $errors, $errorList);
    }

     // ─── Import Kelas ─────────────────────────────────────────────────────────

    public function kelas()
    {
        $file = $this->request->getFile('file_import');
        if (! $this->isValidFile($file)) {
            return redirect()->to(base_url('kelas'))->with('error', 'File tidak valid. Gunakan format .xlsx atau .csv.');
        }

        $rows         = $this->readSpreadsheet($file);
        $kelasModel   = new KelasModel();
        $jurusanModel = new JurusanModel();
        $guruModel    = new GuruModel(); 
        $ok = $skip = $errors = 0;
        $errorList = [];

        $jurusanMap = [];
        foreach ($jurusanModel->findAll() as $j) {

            $key = preg_replace('/\s+/', '', strtolower(trim($j['nama_jurusan'])));
            $jurusanMap[$key] = $j;
        }
 
        $guruMap = [];
        foreach ($guruModel->findAll() as $g) {
            $key = preg_replace('/\s+/', '', strtolower(trim($g['nama_guru'])));
            $guruMap[$key] = $g['id_guru'];
        }

        foreach ($rows as $i => $row) {
            $rowNum = $i + 2; // Data dimulai dari baris ke-2

            $namaKelas   = trim((string) ($row[1] ?? ''));
            $namaJurusan = trim((string) ($row[2] ?? ''));
            $tingkat     = strtoupper(trim((string) ($row[3] ?? '')));
            $namaWali    = trim((string) ($row[4] ?? ''));

            if ($namaKelas === '' && $namaJurusan === '' && $tingkat === '') { $skip++; continue; }

            if ($namaKelas === '') {
                $errorList[] = "Baris $rowNum: Nama Kelas tidak boleh kosong.";
                $errors++; continue;
            }
            
            if (! in_array($tingkat, ['X', 'XI', 'XII'])) {
                $errorList[] = "Baris $rowNum: Tingkat '$tingkat' harus X, XI, atau XII.";
                $errors++; continue;
            }
            
            $jurusanKey = preg_replace('/\s+/', '', strtolower($namaJurusan));
            if (! isset($jurusanMap[$jurusanKey])) {
                $errorList[] = "Baris $rowNum: Nama Jurusan '$namaJurusan' tidak ditemukan di database.";
                $errors++; continue;
            }
            $jurusan = $jurusanMap[$jurusanKey];

            $idWaliKelas = null;
            if ($namaWali !== '') {
                $waliKey = preg_replace('/\s+/', '', strtolower($namaWali));
                if (! isset($guruMap[$waliKey])) {
                    $errorList[] = "Baris $rowNum: Nama Wali Kelas '$namaWali' tidak ditemukan di data guru.";
                    $errors++; continue;
                }
                $idWaliKelas = $guruMap[$waliKey];
            }

            $nomorKelas = 1;
            if (preg_match('/\s(\d+)$/', $namaKelas, $matches)) {
                $nomorKelas = (int) $matches[1];
            }

            if ($kelasModel->isNamaKelasTaken($namaKelas)) {
                $errorList[] = "Baris $rowNum: Kelas '$namaKelas' sudah ada, dilewati.";
                $skip++; continue;
            }

            $kelasModel->insert([
                'id_jurusan'    => $jurusan['id_jurusan'],
                'tingkat'       => $tingkat,
                'nomor_kelas'   => $nomorKelas,
                'nama_kelas'    => $namaKelas,
                'id_wali_kelas' => $idWaliKelas,
            ]);
            $ok++;
        }

        return $this->redirectWithResult('kelas', $ok, $skip, $errors, $errorList);
    }
    // ─── Import Siswa ─────────────────────────────────────────────────────────

    public function siswa()
    {
        $file = $this->request->getFile('file_import');
        if (! $this->isValidFile($file)) {
            return redirect()->to(base_url('siswa'))->with('error', 'File tidak valid. Gunakan format .xlsx atau .csv.');
        }

        $rows       = $this->readSpreadsheet($file);
        $siswaModel = new SiswaModel();
        $kelasModel = new KelasModel();
        $ok = $skip = $errors = 0;
        $errorList  = [];

        // cache nama_kelas → id_kelas
        $kelasMap = [];
        foreach ($kelasModel->findAll() as $k) {
            $kelasMap[strtolower($k['nama_kelas'])] = $k['id_kelas'];
        }

        foreach ($rows as $i => $row) {
            $rowNum    = $i + 2;
            $nisn      = trim((string) ($row[0] ?? ''));
            $namaSiswa = trim((string) ($row[1] ?? ''));
            $namaKelas = trim((string) ($row[2] ?? ''));
            $jk        = strtoupper(trim((string) ($row[3] ?? '')));
            $noHp      = trim((string) ($row[4] ?? ''));
            $alamat    = trim((string) ($row[5] ?? ''));

            if ($nisn === '' && $namaSiswa === '') { $skip++; continue; }

            if (! preg_match('/^\d{10}$/', $nisn)) {
                $errorList[] = "Baris $rowNum: NISN '$nisn' harus 10 digit angka.";
                $errors++; continue;
            }
            if ($namaSiswa === '') {
                $errorList[] = "Baris $rowNum: Nama siswa tidak boleh kosong.";
                $errors++; continue;
            }
            if (! in_array($jk, ['L', 'P'])) {
                $errorList[] = "Baris $rowNum: Jenis kelamin '$jk' harus L atau P.";
                $errors++; continue;
            }
            $idKelas = $kelasMap[strtolower($namaKelas)] ?? null;
            if (! $idKelas) {
                $errorList[] = "Baris $rowNum: Kelas '$namaKelas' tidak ditemukan.";
                $errors++; continue;
            }
            if ($siswaModel->where('nisn', $nisn)->countAllResults() > 0) {
                $errorList[] = "Baris $rowNum: NISN '$nisn' sudah ada, dilewati.";
                $skip++; continue;
            }

            $siswaModel->insert([
                'id_kelas'      => $idKelas,
                'nisn'          => $nisn,
                'nama_siswa'    => $namaSiswa,
                'jenis_kelamin' => $jk,
                'no_hp'         => $noHp,
                'alamat'        => $alamat,
                'foto'          => null,
            ]);
            $kelasModel->syncJumlahSiswa($idKelas);
            $ok++;
        }

        return $this->redirectWithResult('siswa', $ok, $skip, $errors, $errorList);
    }

    // ─── Import Mapel ─────────────────────────────────────────────────────────

    public function mapel()
    {
        $file = $this->request->getFile('file_import');
        if (! $this->isValidFile($file)) {
            return redirect()->to(base_url('mapel'))->with('error', 'File tidak valid. Gunakan format .xlsx atau .csv.');
        }

        $rows  = $this->readSpreadsheet($file);
        $model = new MapelModel();
        $ok = $skip = $errors = 0;
        $errorList = [];

        // cache existing (lowercase)
        $existing = array_map(
            fn($m) => strtolower(preg_replace('/\s+/', ' ', trim($m['nama_mapel']))),
            $model->findAll()
        );

        foreach ($rows as $i => $row) {
            $rowNum    = $i + 2;
            $namaMapel = preg_replace('/\s+/', ' ', trim((string) ($row[0] ?? '')));
            $status    = trim((string) ($row[1] ?? ''));

            if ($namaMapel === '' && $status === '') { $skip++; continue; }

            if ($namaMapel === '') {
                $errorList[] = "Baris $rowNum: Nama mata pelajaran tidak boleh kosong.";
                $errors++; continue;
            }
            if (! in_array($status, ['Produktif', 'Umum'])) {
                $errorList[] = "Baris $rowNum: Status '$status' harus Produktif atau Umum.";
                $errors++; continue;
            }
            if (in_array(strtolower($namaMapel), $existing)) {
                $errorList[] = "Baris $rowNum: Mapel '$namaMapel' sudah ada, dilewati.";
                $skip++; continue;
            }

            $model->insert(['nama_mapel' => $namaMapel, 'status' => $status]);
            $existing[] = strtolower($namaMapel);
            $ok++;
        }

        return $this->redirectWithResult('mapel', $ok, $skip, $errors, $errorList);
    }

    // ─── Import Jadwal ────────────────────────────────────────────────────────

    public function jadwal()
    {
        $file = $this->request->getFile('file_import');
        if (! $this->isValidFile($file)) {
            return redirect()->to(base_url('jadwal'))->with('error', 'File tidak valid. Gunakan format .xlsx atau .csv.');
        }

        $rows        = $this->readSpreadsheet($file);
        $jadwalModel = new JadwalModel();
        $kelasModel  = new KelasModel();
        $mapelModel  = new MapelModel();
        $guruModel   = new GuruModel();
        $ok = $skip = $errors = 0;
        $errorList   = [];

        $hariValid = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        // cache maps
        $kelasMap = [];
        foreach ($kelasModel->findAll() as $k) {
            $kelasMap[strtolower($k['nama_kelas'])] = $k['id_kelas'];
        }
        $mapelMap = [];
        foreach ($mapelModel->findAll() as $m) {
            $mapelMap[strtolower(preg_replace('/\s+/', ' ', trim($m['nama_mapel'])))] = $m['id_mapel'];
        }
        $guruMap = [];
        foreach ($guruModel->findAll() as $g) {
            $guruMap[$g['nip']] = $g['id_guru'];
        }

        foreach ($rows as $i => $row) {
            $rowNum    = $i + 2;
            $namaKelas = trim((string) ($row[0] ?? ''));
            $namaMapel = preg_replace('/\s+/', ' ', trim((string) ($row[1] ?? '')));
            $nipGuru   = trim((string) ($row[2] ?? ''));
            $hari      = ucfirst(strtolower(trim((string) ($row[3] ?? ''))));
            $jamMulai  = trim((string) ($row[4] ?? ''));
            $jamSelesai= trim((string) ($row[5] ?? ''));

            if ($namaKelas === '' && $namaMapel === '' && $nipGuru === '') { $skip++; continue; }

            $idKelas = $kelasMap[strtolower($namaKelas)] ?? null;
            if (! $idKelas) {
                $errorList[] = "Baris $rowNum: Kelas '$namaKelas' tidak ditemukan.";
                $errors++; continue;
            }
            $idMapel = $mapelMap[strtolower($namaMapel)] ?? null;
            if (! $idMapel) {
                $errorList[] = "Baris $rowNum: Mapel '$namaMapel' tidak ditemukan.";
                $errors++; continue;
            }
            if (! isset($guruMap[$nipGuru])) {
                $errorList[] = "Baris $rowNum: NIP guru '$nipGuru' tidak ditemukan.";
                $errors++; continue;
            }
            if (! in_array($hari, $hariValid)) {
                $errorList[] = "Baris $rowNum: Hari '$hari' tidak valid.";
                $errors++; continue;
            }
            if (! preg_match('/^\d{2}:\d{2}$/', $jamMulai) || ! preg_match('/^\d{2}:\d{2}$/', $jamSelesai)) {
                $errorList[] = "Baris $rowNum: Format jam harus HH:MM.";
                $errors++; continue;
            }
            if (strtotime($jamMulai) >= strtotime($jamSelesai)) {
                $errorList[] = "Baris $rowNum: Jam selesai harus lebih besar dari jam mulai.";
                $errors++; continue;
            }

            // cek bentrok kelas
            $bentrok = $jadwalModel
                ->where('id_kelas', $idKelas)
                ->where('hari', $hari)
                ->groupStart()
                    ->where('jam_mulai <', $jamSelesai)
                    ->where('jam_selesai >', $jamMulai)
                ->groupEnd()
                ->countAllResults() > 0;

            if ($bentrok) {
                $errorList[] = "Baris $rowNum: Jadwal bertabrakan dengan jadwal kelas yang sudah ada, dilewati.";
                $skip++; continue;
            }

            $jadwalModel->insert([
                'id_kelas'    => $idKelas,
                'id_mapel'    => $idMapel,
                'id_guru'     => $guruMap[$nipGuru],
                'hari'        => $hari,
                'jam_mulai'   => $jamMulai,
                'jam_selesai' => $jamSelesai,
            ]);
            $ok++;
        }

        return $this->redirectWithResult('jadwal', $ok, $skip, $errors, $errorList);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function isValidFile($file): bool
    {
        if (! $file || ! $file->isValid() || $file->hasMoved()) return false;
        return in_array(strtolower($file->getClientExtension()), ['xlsx', 'csv']);
    }

    /**
     * Membaca spreadsheet dan mengembalikan array baris data,
     * melewati baris 1 (header) dan baris 2 (contoh).
     */
    private function readSpreadsheet($file): array
    {
        $tmpPath = $file->getTempName();
        $ext     = strtolower($file->getClientExtension());

        if ($ext === 'csv') {
            $rows = [];
            if (($handle = fopen($tmpPath, 'r')) !== false) {
                $lineNum = 0;
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $lineNum++;
                    if ($lineNum <= 1) continue; // skip header 
                    $rows[] = $data;
                }
                fclose($handle);
            }
            return $rows;
        }

        // xlsx via PhpSpreadsheet
        $spreadsheet = IOFactory::load($tmpPath);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = [];
        $rowNum      = 0;

        foreach ($sheet->getRowIterator() as $row) {
            $rowNum++;
            if ($rowNum <= 1) continue; // skip header

            $cells = [];
            foreach ($row->getCellIterator() as $cell) {
                $cells[] = $cell->getValue();
            }
            // lewati baris yang semua kolomnya kosong
            if (count(array_filter($cells, fn($v) => trim((string) $v) !== '')) === 0) continue;
            $rows[] = $cells;
        }

        return $rows;
    }

    private function redirectWithResult(
        string $modul,
        int $ok,
        int $skip,
        int $errors,
        array $errorList
    ) {
        $parts = [];
        if ($ok)     $parts[] = "$ok data berhasil diimport";
        if ($skip)   $parts[] = "$skip dilewati";
        if ($errors) $parts[] = "$errors gagal";

        $message = implode(', ', $parts) . '.';

        if (! empty($errorList)) {
            session()->setFlashdata('import_errors', $errorList);
        }

        if ($errors > 0 && $ok === 0) {
            return redirect()->to(base_url($modul))->with('error', 'Import gagal: ' . $message);
        }

        return redirect()->to(base_url($modul))->with('success', 'Import selesai: ' . $message);
    }

        // ─── Import User (Preview Flow) ───────────────────────────────────────────

    public function user()
    {
        $file = $this->request->getFile('file_import');
        if (! $this->isValidFile($file)) {
            return redirect()->to(base_url('user'))->with('error', 'File tidak valid. Gunakan format .xlsx atau .csv.');
        }

        $rows = $this->readSpreadsheet($file);
        $guruModel = new \App\Models\GuruModel();
        $userModel = new \App\Models\UserModel();

        // Cache data guru NIP -> Nama
        $guruMap = [];
        foreach ($guruModel->findAll() as $g) {
            $guruMap[$g['nip']] = $g['nama_guru'];
        }

        $existingUsers = array_column($userModel->findAll(), 'username');

        $previewData = [];
        foreach ($rows as $row) {
            $nip  = trim((string)($row[0] ?? ''));
            $role = strtolower(trim((string)($row[1] ?? '')));
            $pass = trim((string)($row[2] ?? ''));

            if (empty($nip) && empty($role)) continue;

            $status = 'OK';
            $nama   = 'NIP Tidak Ditemukan';

            if (!isset($guruMap[$nip])) {
                $status = 'NIP tidak ada di Data Guru';
            } else {
                $nama = $guruMap[$nip];
            }

            if (!in_array($role, ['guru', 'bk'])) {
                $status = 'Role harus guru/bk';
            }

            if (in_array($nip, $existingUsers)) {
                $status = 'User sudah ada (Skip)';
            }

            if (empty($pass)) {
                $status = 'Password kosong';
            }

            $previewData[] = [
                'nip'     => $nip,
                'nama'    => $nama,
                'role'    => $role,
                'password'=> $pass,
                'status'  => $status
            ];
        }

        session()->set('import_user_preview', $previewData);
        return redirect()->to(base_url('user/import/preview'));
    }
}