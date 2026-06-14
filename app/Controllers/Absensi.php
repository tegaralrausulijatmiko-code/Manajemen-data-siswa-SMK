<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use App\Models\TahunAjaranModel;

class Absensi extends BaseController
{
    protected AbsensiModel $model;
    protected SiswaModel $siswaModel;
    protected KelasModel $kelasModel;
    protected TahunAjaranModel $tahunModel;

    public function __construct()
    {
        $this->model      = new AbsensiModel();
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
        $this->tahunModel = new TahunAjaranModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('q');
        $tanggal = $this->request->getGet('tanggal');
        $kelas   = $this->request->getGet('kelas');

        $paged = $this->paginateArray($this->model->getAll($keyword, $tanggal, $kelas), 10);

        return view('MasterAbsensi/master-data-absensi', [
            'absensi'      => $paged['items'],
            'kelas_list'   => $this->kelasModel->getKelasWithJurusan(),
            'keyword'      => $keyword,
            'filter_tanggal' => $tanggal,
            'filter_kelas' => $kelas,
            'pagination'   => $paged['pagination'],
        ]);
    }

    public function tambah()
    {
        return view('MasterAbsensi/input-absensi', $this->getFormData());
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
            'id_tahun_ajaran' => 'required|integer',
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
            'id_tahun_ajaran' => $this->request->getPost('id_tahun_ajaran'),
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
            'tahun_list' => $this->tahunModel->orderBy('tahun_ajaran', 'DESC')->findAll(),
            'status_list'=> ['Hadir', 'Izin', 'Sakit', 'Alpa'],
        ], $extra);
    }
}
