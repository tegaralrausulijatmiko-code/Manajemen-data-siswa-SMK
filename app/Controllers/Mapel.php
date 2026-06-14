<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\KelasModel;
use App\Models\MapelModel;

class Mapel extends BaseController
{
    protected MapelModel $model;
    protected KelasModel $kelasModel;
    protected GuruModel $guruModel;

    public function __construct()
    {
        $this->model      = new MapelModel();
        $this->kelasModel = new KelasModel();
        $this->guruModel  = new GuruModel();
    }

    public function index()
    {
        $keyword       = $this->request->getGet('q');
        $filter_status = $this->request->getGet('status');

        $paged = $this->paginateArray($this->model->getAll($keyword, $filter_status), 10);

        return view('MasterMapel/master-data-mapel', [
            'mapel'         => $paged['items'],
            'keyword'       => $keyword,
            'filter_status' => $filter_status,
            'pagination'    => $paged['pagination'],
        ]);
    }

    public function tambah()
    {
        return view('MasterMapel/input-mapel', [
            'kelas_list' => $this->kelasModel->getKelasWithJurusan(),
            'guru_list'  => $this->guruModel->findAll(),
            'next_kode'  => $this->model->getNextKodeMapel(),
        ]);
    }

    public function simpan()
    {
        $rules = [
            'kode_mapel' => 'required|max_length[10]|is_unique[tbl_mata_pelajaran.kode_mapel]',
            'nama_mapel' => 'required|max_length[100]',
            'id_kelas'   => 'required|integer',
            'id_guru'    => 'required|integer',
            'status'     => 'required|in_list[Produktif,Non Produktif]',
        ];

        if (! $this->validate($rules)) {
            return view('MasterMapel/input-mapel', [
                'errors'     => $this->validator->getErrors(),
                'kelas_list' => $this->kelasModel->getKelasWithJurusan(),
                'guru_list'  => $this->guruModel->findAll(),
                'next_kode'  => $this->model->getNextKodeMapel(),
            ]);
        }

        $kodeMapel = $this->request->getPost('kode_mapel') ?: $this->model->getNextKodeMapel();

        $this->model->insert([
            'kode_mapel' => $kodeMapel,
            'nama_mapel' => $this->request->getPost('nama_mapel'),
            'id_kelas'   => $this->request->getPost('id_kelas'),
            'id_guru'    => $this->request->getPost('id_guru'),
            'status'     => $this->request->getPost('status'),
        ]);

        return redirect()->to(base_url('mapel'))->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mapel = $this->model->find($id);
        if (! $mapel) {
            return redirect()->to(base_url('mapel'))->with('error', 'Data tidak ditemukan.');
        }

        return view('MasterMapel/edit-mapel', [
            'mapel'      => $mapel,
            'kelas_list' => $this->kelasModel->getKelasWithJurusan(),
            'guru_list'  => $this->guruModel->findAll(),
        ]);
    }

    public function update($id)
    {
        $rules = [
            'kode_mapel' => "required|max_length[10]|is_unique[tbl_mata_pelajaran.kode_mapel,id_mapel,$id]",
            'nama_mapel' => 'required|max_length[100]',
            'id_kelas'   => 'required|integer',
            'id_guru'    => 'required|integer',
            'status'     => 'required|in_list[Produktif,Non Produktif]',
        ];

        if (! $this->validate($rules)) {
            $mapel = $this->model->find($id);
            return view('MasterMapel/edit-mapel', [
                'mapel'      => $mapel,
                'errors'     => $this->validator->getErrors(),
                'kelas_list' => $this->kelasModel->getKelasWithJurusan(),
                'guru_list'  => $this->guruModel->findAll(),
            ]);
        }

        $this->model->update($id, [
            'kode_mapel' => $this->request->getPost('kode_mapel'),
            'nama_mapel' => $this->request->getPost('nama_mapel'),
            'id_kelas'   => $this->request->getPost('id_kelas'),
            'id_guru'    => $this->request->getPost('id_guru'),
            'status'     => $this->request->getPost('status'),
        ]);

        return redirect()->to(base_url('mapel'))->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('mapel'))->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
