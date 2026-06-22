<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\JadwalModel;
use App\Models\KelasModel;
use App\Models\SiswaModel;

class Absensi extends BaseController
{
    protected AbsensiModel $model;
    protected JadwalModel $jadwalModel;
    protected SiswaModel $siswaModel;
    protected KelasModel $kelasModel;

    public function __construct()
    {
        $this->model      = new AbsensiModel();
        $this->jadwalModel = new JadwalModel();
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
    }

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

    public function point($id_siswa)
    {
        $status     = $this->request->getPost('status');
        $tanggal    = $this->request->getPost('tanggal') ?: date('Y-m-d');
        $id_kelas   = $this->request->getPost('id_kelas');
        $keterangan = $status === 'Izin' ? trim((string) $this->request->getPost('keterangan')) : '';

        if (! in_array($status, ['Hadir', 'Izin', 'Alpa'], true)) {
            return redirect()->back()->with('error', 'Status absensi tidak valid.');
        }

        $data = [
            'id_siswa'   => $id_siswa,
            'id_kelas'   => $id_kelas,
            'tanggal'    => $tanggal,
            'status'     => $status,
            'keterangan' => $keterangan,
        ];

        $existing = $this->model->where(['id_siswa' => $id_siswa, 'tanggal' => $tanggal])->first();
        if ($existing) {
            $this->model->update($existing['id_absensi'], $data);
        } else {
            $this->model->insert($data);
        }

        $queryParams = [];
        if ($kelas = $this->request->getPost('kelas')) {
            $queryParams['kelas'] = $kelas;
        }
        if ($tanggal = $this->request->getPost('tanggal')) {
            $queryParams['tanggal'] = $tanggal;
        }
        if ($q = $this->request->getPost('q')) {
            $queryParams['q'] = $q;
        }

        $query    = http_build_query($queryParams);
        $redirect = base_url('absensi' . ($query ? '?' . $query : ''));
        return redirect()->to($redirect)->with('success', "Absensi siswa berhasil disimpan: {$status}.");
    }

    public function simpanAbsensi()
    {
        $statuses      = $this->request->getPost('status') ?? [];
        $idKelasMap    = $this->request->getPost('id_kelas') ?? [];
        $keteranganMap = $this->request->getPost('keterangan') ?? [];
        $validStatuses = ['Belum Absen', 'Hadir', 'Izin', 'Alpa'];

        foreach ($statuses as $id_siswa => $status) {
            if (! in_array($status, $validStatuses, true)) {
                continue;
            }

            $existing = $this->model->where(['id_siswa' => $id_siswa, 'tanggal' => $this->request->getPost('tanggal')])->first();

            if ($status === 'Belum Absen') {
                if ($existing) {
                    $this->model->delete($existing['id_absensi']);
                }
                continue;
            }

            // Keterangan hanya berlaku untuk status Izin
            $keterangan = $status === 'Izin' ? trim((string) ($keteranganMap[$id_siswa] ?? '')) : '';

            $data = [
                'id_siswa'   => $id_siswa,
                'id_kelas'   => $idKelasMap[$id_siswa] ?? null,
                'tanggal'    => $this->request->getPost('tanggal') ?: date('Y-m-d'),
                'status'     => $status,
                'keterangan' => $keterangan,
            ];

            if ($existing) {
                $this->model->update($existing['id_absensi'], $data);
            } else {
                $this->model->insert($data);
            }
        }

        $queryParams = [];
        if ($kelas = $this->request->getPost('kelas')) {
            $queryParams['kelas'] = $kelas;
        }
        if ($tanggal = $this->request->getPost('tanggal')) {
            $queryParams['tanggal'] = $tanggal;
        }
        if ($q = $this->request->getPost('q')) {
            $queryParams['q'] = $q;
        }

        $query    = http_build_query($queryParams);
        $redirect = base_url('absensi' . ($query ? '?' . $query : ''));
        return redirect()->to($redirect)->with('success', 'Perubahan absensi berhasil disimpan.');
    }

    public function tambah()
    {
        return view('MasterAbsensi/input-absensi', $this->getFormData());
    }

    public function jadwal($id)
    {
        $jadwal = $this->jadwalModel->getDetail((int) $id);
        if (! $jadwal) {
            return redirect()->to(base_url('jadwal'))->with('error', 'Jadwal tidak ditemukan.');
        }

        $tanggal = $this->request->getGet('tanggal') ?: date('Y-m-d');
        $siswa = $this->siswaModel->getAll(null, (string) $jadwal['id_kelas']);
        $absensiMap = $this->model->getByJadwalDate((int) $id, $tanggal);

        return view('MasterAbsensi/input-absensi-jadwal', [
            'jadwal'      => $jadwal,
            'siswa_list'  => $siswa,
            'tanggal'     => $tanggal,
            'absensi_map' => $absensiMap,
            'status_list' => $this->statusList(),
        ]);
    }

    public function simpanJadwal($id)
    {
        $jadwal = $this->jadwalModel->getDetail((int) $id);
        if (! $jadwal) {
            return redirect()->to(base_url('jadwal'))->with('error', 'Jadwal tidak ditemukan.');
        }

        $tanggal = $this->request->getPost('tanggal');
        if (! $tanggal || ! strtotime($tanggal)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal absensi wajib diisi.');
        }

        $statuses = $this->request->getPost('status') ?? [];
        $keterangan = $this->request->getPost('keterangan') ?? [];
        $statusList = $this->statusList();
        $siswa = $this->siswaModel->getAll(null, (string) $jadwal['id_kelas']);

        if ($siswa === []) {
            return redirect()->back()->with('error', 'Tidak ada siswa pada kelas ini.');
        }

        $db = db_connect();
        $db->transStart();

        foreach ($siswa as $row) {
            $idSiswa = (int) $row['id_siswa'];
            $status = $statuses[$idSiswa] ?? 'Hadir';

            if (! in_array($status, $statusList, true)) {
                $status = 'Hadir';
            }

            $payload = [
                'id_siswa'        => $idSiswa,
                'id_kelas'        => $jadwal['id_kelas'],
                'id_jadwal'       => $jadwal['id_jadwal'],
                'tanggal'         => $tanggal,
                'status'          => $status,
                'keterangan'      => trim($keterangan[$idSiswa] ?? ''),
            ];

            $existing = $this->model->findByStudentScheduleDate($idSiswa, (int) $jadwal['id_jadwal'], $tanggal);
            if ($existing) {
                $this->model->update($existing['id_absensi'], $payload);
                continue;
            }

            $this->model->insert($payload);
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->with('error', 'Absensi gagal disimpan.');
        }

        return redirect()
            ->to(base_url('absensi/jadwal/' . $jadwal['id_jadwal'] . '?tanggal=' . $tanggal))
            ->with('success', 'Absensi jadwal berhasil disimpan.');
    }

    public function rekap()
    {
        $filters = $this->rekapFilters();
        $rows = $this->model->getRekap($filters);
        $paged = $this->paginateArray($rows, 20);

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
        $filters = $this->rekapFilters();
        $rows = $this->model->getRekap($filters);
        $filename = 'rekap_absensi_' . date('Ymd_His') . '.xls';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($this->renderExcel($rows));
    }

    public function simpan()
    {
        if (! $this->validate($this->rules())) {
            return view('MasterAbsensi/input-absensi', $this->getFormData([
                'errors' => $this->validator->getErrors(),
            ]));
        }

        $this->model->insert($this->payload());
        return redirect()->to(base_url('absensi'))->with('success', 'Absensi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $absensi = $this->model->find($id);
        if (! $absensi) {
            return redirect()->to(base_url('absensi'))->with('error', 'Data tidak ditemukan.');
        }

        return view('MasterAbsensi/edit-absensi', $this->getFormData(['absensi' => $absensi]));
    }

    public function update($id)
    {
        if (! $this->validate($this->rules())) {
            return view('MasterAbsensi/edit-absensi', $this->getFormData([
                'absensi' => $this->model->find($id),
                'errors'  => $this->validator->getErrors(),
            ]));
        }

        $this->model->update($id, $this->payload());
        return redirect()->to(base_url('absensi'))->with('success', 'Absensi berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('absensi'))->with('success', 'Absensi berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'id_siswa'        => 'required|integer',
            'id_kelas'        => 'required|integer',
            'tanggal'         => 'required|valid_date',
            'status'          => 'required|in_list[Hadir,Izin,Sakit,Alpa]',
            'keterangan'      => 'permit_empty|max_length[255]',
        ];
    }

    private function payload(): array
    {
        return [
            'id_siswa'        => $this->request->getPost('id_siswa'),
            'id_kelas'        => $this->request->getPost('id_kelas'),
            'tanggal'         => $this->request->getPost('tanggal'),
            'status'          => $this->request->getPost('status'),
            'keterangan'      => $this->request->getPost('keterangan'),
        ];
    }

    private function getFormData(array $extra = []): array
    {
        return array_merge([
            'siswa_list' => $this->siswaModel->getAll(),
            'kelas_list' => $this->kelasModel->getKelasWithJurusan(),
            'jadwal_list'=> $this->jadwalModel->getAll(),
            'status_list'=> $this->statusList(),
        ], $extra);
    }

    private function statusList(): array
    {
        return ['Hadir', 'Izin', 'Sakit', 'Alpa'];
    }

    private function rekapFilters(): array
    {
        return [
            'tanggal_awal'  => $this->request->getGet('tanggal_awal'),
            'tanggal_akhir' => $this->request->getGet('tanggal_akhir'),
            'id_kelas'      => $this->request->getGet('kelas'),
            'id_jadwal'     => $this->request->getGet('jadwal'),
            'status'        => $this->request->getGet('status'),
        ];
    }

    private function summarizeStatus(array $rows): array
    {
        $summary = array_fill_keys($this->statusList(), 0);

        foreach ($rows as $row) {
            if (isset($summary[$row['status']])) {
                $summary[$row['status']]++;
            }
        }

        $summary['Total'] = count($rows);

        return $summary;
    }

    private function renderExcel(array $rows): string
    {
        $html = '<html><head><meta charset="UTF-8"></head><body>';
        $html .= '<h3>Rekap Absensi Siswa</h3>';
        $html .= '<table border="1">';
        $html .= '<thead><tr>';
        $html .= '<th>No</th><th>Tanggal</th><th>NISN</th><th>Nama</th><th>Kelas</th><th>Hari</th><th>Jam</th><th>Mata Pelajaran</th><th>Guru</th><th>Status</th><th>Keterangan</th>';
        $html .= '</tr></thead><tbody>';

        foreach ($rows as $index => $row) {
            $jam = trim(substr($row['jam_mulai'] ?? '', 0, 5) . ' - ' . substr($row['jam_selesai'] ?? '', 0, 5), ' -');
            $html .= '<tr>';
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . esc(date('d/m/Y', strtotime($row['tanggal']))) . '</td>';
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

        if ($rows === []) {
            $html .= '<tr><td colspan="11">Tidak ada data</td></tr>';
        }

        $html .= '</tbody></table></body></html>';

        return $html;
    }
}
