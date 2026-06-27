<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\JadwalModel;
use App\Models\KelasModel;
use App\Models\MapelModel;

class Jadwal extends BaseController
{
    protected JadwalModel $model;
    protected KelasModel $kelasModel;
    protected MapelModel $mapelModel;
    protected GuruModel $guruModel;

    public function __construct()
    {
        $this->model      = new JadwalModel();
        $this->kelasModel = new KelasModel();
        $this->mapelModel = new MapelModel();
        $this->guruModel  = new GuruModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('q');
        $kelas   = $this->request->getGet('kelas');

        $paged = $this->paginateArray($this->model->getAll($keyword, $kelas), 10);

        return view('MasterJadwal/master-data-jadwal', [
            'jadwal'       => $paged['items'],
            'kelas_list'   => $this->kelasModel->getKelasWithJurusan(),
            'keyword'      => $keyword,
            'filter_kelas' => $kelas,
            'pagination'   => $paged['pagination'],
        ]);
    }

    public function tambah()
    {
        return view('MasterJadwal/input-jadwal', $this->getFormData());
    }

    public function simpan()
    {
        if (! $this->validate($this->rules())) {
            return view('MasterJadwal/input-jadwal', $this->getFormData([
                'errors' => $this->validator->getErrors(),
            ]));
        }

        $this->model->insert($this->payload());
        return redirect()->to(base_url('jadwal'))->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal = $this->model->find($id);
        if (! $jadwal) {
            return redirect()->to(base_url('jadwal'))->with('error', 'Data tidak ditemukan.');
        }

        return view('MasterJadwal/edit-jadwal', $this->getFormData(['jadwal' => $jadwal]));
    }

    public function update($id)
    {
        if (! $this->validate($this->rules())) {
            return view('MasterJadwal/edit-jadwal', $this->getFormData([
                'jadwal' => $this->model->find($id),
                'errors' => $this->validator->getErrors(),
            ]));
        }

        $this->model->update($id, $this->payload());
        return redirect()->to(base_url('jadwal'))->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('jadwal'))->with('success', 'Jadwal berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'id_kelas'    => 'required|integer',
            'id_mapel'    => 'required|integer',
            'id_guru'     => 'required|integer',
            'hari'        => 'required|in_list[Senin,Selasa,Rabu,Kamis,Jumat,Sabtu]',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
        ];
    }

    private function payload(): array
    {
        return [
            'id_kelas'    => $this->request->getPost('id_kelas'),
            'id_mapel'    => $this->request->getPost('id_mapel'),
            'id_guru'     => $this->request->getPost('id_guru'),
            'hari'        => $this->request->getPost('hari'),
            'jam_mulai'   => $this->request->getPost('jam_mulai'),
            'jam_selesai' => $this->request->getPost('jam_selesai'),
        ];
    }

    private function getFormData(array $extra = []): array
    {
        return array_merge([
            'kelas_list' => $this->kelasModel->getKelasWithJurusan(),
            'mapel_list' => $this->mapelModel->findAll(),
            'guru_list'  => $this->guruModel->findAll(),
            'hari_list'  => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
        ], $extra);
    }
}
