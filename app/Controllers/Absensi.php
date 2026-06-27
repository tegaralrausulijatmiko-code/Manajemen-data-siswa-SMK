<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\JadwalModel;
use App\Models\KelasModel;
use App\Models\SiswaModel;

class Absensi extends BaseController
{
    protected AbsensiModel $model;
    protected JadwalModel  $jadwalModel;
    protected SiswaModel   $siswaModel;
    protected KelasModel   $kelasModel;

    public function __construct()
    {
        $this->model       = new AbsensiModel();
        $this->jadwalModel = new JadwalModel();
        $this->siswaModel  = new SiswaModel();
        $this->kelasModel  = new KelasModel();
    }

    // =========================================================================
    // ADMIN — Absensi umum
    // =========================================================================

    public function index()
    {
        $keyword = $this->request->getGet('q');
        $tanggal = $this->request->getGet('tanggal') ?: date('Y-m-d');
        $kelas   = $this->request->getGet('kelas');

        $siswaList  = $this->siswaModel->getAll($keyword, $kelas);
        $attendance = $this->model->getByDateAndKelas($tanggal, $kelas);

        return view('MasterAbsensi/master-data-absensi', [
            'siswa_list'     => $siswaList,
            'attendance_map' => $attendance,
            'kelas_list'     => $this->kelasModel->getKelasWithJurusan(),
            'keyword'        => $keyword,
            'filter_tanggal' => $tanggal,
            'filter_kelas'   => $kelas,
        ]);
    }

    public function simpanAbsensi()
    {
        $statuses      = $this->request->getPost('status') ?? [];
        $idKelasMap    = $this->request->getPost('id_kelas') ?? [];
        $keteranganMap = $this->request->getPost('keterangan') ?? [];
        $tanggal       = $this->request->getPost('tanggal') ?: date('Y-m-d');
        $validStatuses = array_merge(['Belum Absen'], $this->statusList());

        foreach ($statuses as $id_siswa => $status) {
            if (! in_array($status, $validStatuses, true)) {
                continue;
            }

            $existing = $this->model->where(['id_siswa' => $id_siswa, 'tanggal' => $tanggal])->first();

            if ($status === 'Belum Absen') {
                if ($existing) {
                    $this->model->delete($existing['id_absensi']);
                }
                continue;
            }

            $keterangan = in_array($status, ['Izin', 'Sakit'], true)
                ? trim((string) ($keteranganMap[$id_siswa] ?? ''))
                : '';

            $data = [
                'id_siswa'   => $id_siswa,
                'id_kelas'   => $idKelasMap[$id_siswa] ?? null,
                'tanggal'    => $tanggal,
                'jenis'      => 'harian',
                'status'     => $status,
                'keterangan' => $keterangan,
            ];

            if ($existing) {
                $this->model->update($existing['id_absensi'], $data);
            } else {
                $this->model->insert($data);
            }
        }

        $query    = http_build_query(array_filter([
            'kelas'   => $this->request->getPost('kelas'),
            'tanggal' => $tanggal,
            'q'       => $this->request->getPost('q'),
        ]));
        return redirect()->to(base_url('absensi' . ($query ? '?' . $query : '')))->with('success', 'Perubahan absensi berhasil disimpan.');
    }

    // =========================================================================
    // GURU — Dashboard (pilihan: absen mapel atau absen harian wali kelas)
    // =========================================================================

    public function guruIndex()
    {
        $idGuru = $this->getGuruId();
        if (! $idGuru) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akun belum terhubung dengan data guru.');
        }

        $tanggal    = $this->request->getGet('tanggal') ?: date('Y-m-d');
        $jadwalList = $this->jadwalModel->getAll(null, null, (string) $idGuru);

        // Kelas yang diampu guru ini sebagai wali kelas
        $kelasWali  = $this->kelasModel->getKelasWaliByGuru($idGuru);

        return view('GuruAbsensi/index', [
            'jadwal_list' => $jadwalList,
            'kelas_wali'  => $kelasWali,
            'tanggal'     => $tanggal,
            'hari_ini'    => date('Y-m-d'),
        ]);
    }

    // =========================================================================
    // GURU — Absen Mapel (berdasarkan Jadwal)
    // =========================================================================

    public function guruJadwal(int $id)
    {
        $idGuru = $this->getGuruId();
        $jadwal = $this->jadwalModel->getDetailForGuru($id, $idGuru);

        if (! $jadwal) {
            return redirect()->to(base_url('guru/absensi'))->with('error', 'Jadwal tidak ditemukan atau bukan jadwal Anda.');
        }

        $tanggal    = $this->request->getGet('tanggal') ?: date('Y-m-d');
        $siswa      = $this->siswaModel->getAll(null, (string) $jadwal['id_kelas']);
        $absensiMap = $this->model->getByJadwalDate($id, $tanggal);

        return view('GuruAbsensi/form-mapel', [
            'jadwal'      => $jadwal,
            'siswa_list'  => $siswa,
            'tanggal'     => $tanggal,
            'absensi_map' => $absensiMap,
            'status_list' => $this->statusList(),
        ]);
    }

    public function guruSimpanJadwal(int $id)
    {
        $idGuru = $this->getGuruId();
        $jadwal = $this->jadwalModel->getDetailForGuru($id, $idGuru);

        if (! $jadwal) {
            return redirect()->to(base_url('guru/absensi'))->with('error', 'Jadwal tidak ditemukan atau bukan jadwal Anda.');
        }

        $tanggal = $this->request->getPost('tanggal') ?: date('Y-m-d');
        $statuses    = $this->request->getPost('status') ?? [];
        $keterangan  = $this->request->getPost('keterangan') ?? [];
        $statusList  = $this->statusList();
        $siswa       = $this->siswaModel->getAll(null, (string) $jadwal['id_kelas']);

        if (empty($siswa)) {
            return redirect()->back()->with('error', 'Tidak ada siswa pada kelas ini.');
        }

        $db = db_connect();
        $db->transStart();

        foreach ($siswa as $row) {
            $idSiswa = (int) $row['id_siswa'];
            $status  = $statuses[$idSiswa] ?? 'Hadir';

            if (! in_array($status, $statusList, true)) {
                $status = 'Hadir';
            }

            $payload = [
                'id_siswa'   => $idSiswa,
                'id_kelas'   => $jadwal['id_kelas'],
                'id_jadwal'  => $jadwal['id_jadwal'],
                'jenis'      => 'mapel',
                'tanggal'    => $tanggal,
                'status'     => $status,
                'keterangan' => in_array($status, ['Izin', 'Sakit'], true)
                    ? trim($keterangan[$idSiswa] ?? '')
                    : '',
            ];

            $existing = $this->model->findByStudentScheduleDate($idSiswa, (int) $jadwal['id_jadwal'], $tanggal);
            if ($existing) {
                $this->model->update($existing['id_absensi'], $payload);
            } else {
                $this->model->insert($payload);
            }
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->with('error', 'Absensi gagal disimpan.');
        }

        return redirect()
            ->to(base_url('guru/absensi/jadwal/' . $id . '?tanggal=' . $tanggal))
            ->with('success', 'Absensi mapel berhasil disimpan.');
    }

    // =========================================================================
    // GURU — Absen Harian (Wali Kelas)
    // =========================================================================

    public function guruHarian(int $idKelas)
    {
        $idGuru    = $this->getGuruId();
        $kelasWali = $this->kelasModel->getKelasWaliByGuru($idGuru);

        // Pastikan guru adalah wali dari kelas ini
        $kelas = null;
        foreach ($kelasWali as $k) {
            if ((int) $k['id_kelas'] === $idKelas) {
                $kelas = $k;
                break;
            }
        }

        if (! $kelas) {
            return redirect()->to(base_url('guru/absensi'))->with('error', 'Anda bukan wali kelas dari kelas ini.');
        }

        $tanggal    = $this->request->getGet('tanggal') ?: date('Y-m-d');
        $siswa      = $this->siswaModel->getAll(null, (string) $idKelas);
        $absensiMap = $this->model->getHarianByKelasDate($idKelas, $tanggal);

        return view('GuruAbsensi/form-harian', [
            'kelas'       => $kelas,
            'siswa_list'  => $siswa,
            'tanggal'     => $tanggal,
            'absensi_map' => $absensiMap,
            'status_list' => $this->statusListHarian(),
        ]);
    }

    public function guruSimpanHarian(int $idKelas)
    {
        $idGuru    = $this->getGuruId();
        $kelasWali = $this->kelasModel->getKelasWaliByGuru($idGuru);

        $kelas = null;
        foreach ($kelasWali as $k) {
            if ((int) $k['id_kelas'] === $idKelas) {
                $kelas = $k;
                break;
            }
        }

        if (! $kelas) {
            return redirect()->to(base_url('guru/absensi'))->with('error', 'Anda bukan wali kelas dari kelas ini.');
        }

        $tanggal    = $this->request->getPost('tanggal') ?: date('Y-m-d');
        $statuses   = $this->request->getPost('status') ?? [];
        $keterangan = $this->request->getPost('keterangan') ?? [];
        $statusList = $this->statusListHarian();
        $siswa      = $this->siswaModel->getAll(null, (string) $idKelas);

        if (empty($siswa)) {
            return redirect()->back()->with('error', 'Tidak ada siswa pada kelas ini.');
        }

        $db = db_connect();
        $db->transStart();

        foreach ($siswa as $row) {
            $idSiswa = (int) $row['id_siswa'];
            $status  = $statuses[$idSiswa] ?? 'Hadir';

            if (! in_array($status, $statusList, true)) {
                $status = 'Hadir';
            }

            $payload = [
                'id_siswa'   => $idSiswa,
                'id_kelas'   => $idKelas,
                'id_jadwal'  => null,
                'jenis'      => 'harian',
                'tanggal'    => $tanggal,
                'status'     => $status,
                'keterangan' => in_array($status, ['Izin', 'Sakit'], true)
                    ? trim($keterangan[$idSiswa] ?? '')
                    : '',
            ];

            $existing = $this->model->findHarian($idSiswa, $tanggal);
            if ($existing) {
                $this->model->update($existing['id_absensi'], $payload);
            } else {
                $this->model->insert($payload);
            }
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->with('error', 'Absensi harian gagal disimpan.');
        }

        return redirect()
            ->to(base_url('guru/absensi/harian/' . $idKelas . '?tanggal=' . $tanggal))
            ->with('success', 'Absensi harian kelas ' . esc($kelas['nama_kelas']) . ' berhasil disimpan.');
    }

    // =========================================================================
    // GURU — Rekap
    // =========================================================================

    public function guruRekap()
    {
        $idGuru  = $this->getGuruId();
        $filters = array_merge($this->rekapFilters(), ['id_guru' => $idGuru]);
        $rows    = $this->model->getRekap($filters);
        $paged   = $this->paginateArray($rows, 20);

        return view('GuruAbsensi/rekap', [
            'rekap'       => $paged['items'],
            'kelas_list'  => $this->jadwalModel->getKelasByGuru($idGuru),
            'jadwal_list' => $this->jadwalModel->getAll(null, null, (string) $idGuru),
            'status_list' => $this->statusList(),
            'filters'     => $filters,
            'summary'     => $this->summarizeStatus($rows),
            'pagination'  => $paged['pagination'],
        ]);
    }

    // =========================================================================
    // ADMIN — Absensi dari Jadwal
    // =========================================================================

    public function jadwal(int $id)
    {
        $jadwal = $this->jadwalModel->getDetail($id);
        if (! $jadwal) {
            return redirect()->to(base_url('jadwal'))->with('error', 'Jadwal tidak ditemukan.');
        }

        $tanggal    = $this->request->getGet('tanggal') ?: date('Y-m-d');
        $siswa      = $this->siswaModel->getAll(null, (string) $jadwal['id_kelas']);
        $absensiMap = $this->model->getByJadwalDate($id, $tanggal);

        return view('MasterAbsensi/input-absensi-jadwal', [
            'jadwal'      => $jadwal,
            'siswa_list'  => $siswa,
            'tanggal'     => $tanggal,
            'absensi_map' => $absensiMap,
            'status_list' => $this->statusList(),
        ]);
    }

    public function simpanJadwal(int $id)
    {
        $jadwal = $this->jadwalModel->getDetail($id);
        if (! $jadwal) {
            return redirect()->to(base_url('jadwal'))->with('error', 'Jadwal tidak ditemukan.');
        }

        $tanggal = $this->request->getPost('tanggal');
        if (! $tanggal || ! strtotime($tanggal)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal absensi wajib diisi.');
        }

        $statuses   = $this->request->getPost('status') ?? [];
        $keterangan = $this->request->getPost('keterangan') ?? [];
        $statusList = $this->statusList();
        $siswa      = $this->siswaModel->getAll(null, (string) $jadwal['id_kelas']);

        if (empty($siswa)) {
            return redirect()->back()->with('error', 'Tidak ada siswa pada kelas ini.');
        }

        $db = db_connect();
        $db->transStart();

        foreach ($siswa as $row) {
            $idSiswa = (int) $row['id_siswa'];
            $status  = $statuses[$idSiswa] ?? 'Hadir';

            if (! in_array($status, $statusList, true)) {
                $status = 'Hadir';
            }

            $payload = [
                'id_siswa'   => $idSiswa,
                'id_kelas'   => $jadwal['id_kelas'],
                'id_jadwal'  => $jadwal['id_jadwal'],
                'jenis'      => 'mapel',
                'tanggal'    => $tanggal,
                'status'     => $status,
                'keterangan' => in_array($status, ['Izin', 'Sakit'], true)
                    ? trim($keterangan[$idSiswa] ?? '')
                    : '',
            ];

            $existing = $this->model->findByStudentScheduleDate($idSiswa, (int) $jadwal['id_jadwal'], $tanggal);
            if ($existing) {
                $this->model->update($existing['id_absensi'], $payload);
            } else {
                $this->model->insert($payload);
            }
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->with('error', 'Absensi gagal disimpan.');
        }

        return redirect()
            ->to(base_url('absensi/jadwal/' . $id . '?tanggal=' . $tanggal))
            ->with('success', 'Absensi jadwal berhasil disimpan.');
    }

    // =========================================================================
    // ADMIN — Rekap
    // =========================================================================

    public function rekap()
    {
        $filters = $this->rekapFilters();
        $rows    = $this->model->getRekap($filters);
        $paged   = $this->paginateArray($rows, 20);

        return view('MasterAbsensi/rekap-absensi', [
            'rekap'       => $paged['items'],
            'kelas_list'  => $this->kelasModel->getKelasWithJurusan(),
            'jadwal_list' => $this->jadwalModel->getAll(),
            'status_list' => $this->statusList(),
            'filters'     => $filters,
            'summary'     => $this->summarizeStatus($rows),
            'pagination'  => $paged['pagination'],
        ]);
    }

    public function exportRekap()
    {
        $filters  = $this->rekapFilters();
        $rows     = $this->model->getRekap($filters);
        $filename = 'rekap_absensi_' . date('Ymd_His') . '.xls';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($this->renderExcel($rows));
    }

    public function hapus(int $id)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('absensi'))->with('success', 'Absensi berhasil dihapus.');
    }

    // =========================================================================
    // Private helpers
    // =========================================================================

    private function getGuruId(): int
    {
        return (int) (session()->get('id_guru') ?? 0);
    }

    /** Status lengkap untuk absen mapel */
    private function statusList(): array
    {
        return ['Hadir', 'Izin', 'Sakit', 'Alpha', 'Terlambat'];
    }

    /** Status untuk absen harian wali kelas */
    private function statusListHarian(): array
    {
        return ['Hadir', 'Izin', 'Sakit', 'Alpha'];
    }

    private function rekapFilters(): array
    {
        $today = date('Y-m-d');

        return [
            'tanggal_awal'  => $this->request->getGet('tanggal_awal') ?: $today,
            'tanggal_akhir' => $this->request->getGet('tanggal_akhir') ?: $today,
            'id_kelas'      => $this->request->getGet('kelas'),
            'id_jadwal'     => $this->request->getGet('jadwal'),
            'status'        => $this->request->getGet('status'),
            'jenis'         => $this->request->getGet('jenis'),
        ];
    }

    private function summarizeStatus(array $rows): array
    {
        $summary = array_fill_keys($this->statusList(), 0);
        $summary['Total'] = 0;

        foreach ($rows as $row) {
            $summary['Total']++;
            if (isset($summary[$row['status']])) {
                $summary[$row['status']]++;
            }
        }

        return $summary;
    }

    /**
     * Paginasi sederhana dari array in-memory.
     */
    protected function paginateArray(array $items, int $perPage = 20): array
    {
        $page     = max(1, (int) ($this->request->getGet('page') ?? 1));
        $total    = count($items);
        $offset   = ($page - 1) * $perPage;
        $sliced   = array_slice($items, $offset, $perPage);

        return [
            'items'      => $sliced,
            'pagination' => [
                'page'       => $page,
                'per_page'   => $perPage,
                'total'      => $total,
                'total_page' => (int) ceil($total / $perPage),
            ],
        ];
    }

    private function renderExcel(array $rows): string
    {
        $html  = '<html><head><meta charset="UTF-8"></head><body>';
        $html .= '<h3>Rekap Absensi Siswa</h3>';
        $html .= '<table border="1"><thead><tr>';
        $html .= '<th>No</th><th>Tanggal</th><th>Jenis</th><th>NISN</th><th>Nama</th><th>Kelas</th>';
        $html .= '<th>Hari</th><th>Jam</th><th>Mata Pelajaran</th><th>Guru</th><th>Status</th><th>Keterangan</th>';
        $html .= '</tr></thead><tbody>';

        foreach ($rows as $i => $row) {
            $jam   = trim(substr($row['jam_mulai'] ?? '', 0, 5) . ' - ' . substr($row['jam_selesai'] ?? '', 0, 5), ' -');
            $jenis = $row['jenis'] === 'harian' ? 'Harian' : 'Mapel';
            $html .= '<tr>';
            $html .= '<td>' . ($i + 1) . '</td>';
            $html .= '<td>' . esc(date('d/m/Y', strtotime($row['tanggal']))) . '</td>';
            $html .= '<td>' . esc($jenis) . '</td>';
            $html .= '<td>' . esc($row['nisn'] ?? '-') . '</td>';
            $html .= '<td>' . esc($row['nama_siswa'] ?? '-') . '</td>';
            $html .= '<td>' . esc($row['nama_kelas'] ?? '-') . '</td>';
            $html .= '<td>' . esc($row['hari'] ?? '-') . '</td>';
            $html .= '<td>' . esc($jam ?: '-') . '</td>';
            $html .= '<td>' . esc($row['nama_mapel'] ?? '-') . '</td>';
            $html .= '<td>' . esc($row['nama_guru'] ?? '-') . '</td>';
            $html .= '<td>' . esc($row['status']) . '</td>';
            $html .= '<td>' . esc($row['keterangan'] ?: '-') . '</td>';
            $html .= '</tr>';
        }

        if (empty($rows)) {
            $html .= '<tr><td colspan="12">Tidak ada data</td></tr>';
        }

        $html .= '</tbody></table></body></html>';
        return $html;
    }
}