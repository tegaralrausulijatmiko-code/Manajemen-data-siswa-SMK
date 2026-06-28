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
            'nama_mapel'   => $this->request->getPost('nama_mapel'),
            'status'     => $this->request->getPost('status'),
        ]);
    }

    public function simpan()
    {
        $rules = [
            'nama_mapel' => 'required|max_length[100]',
            'status'     => 'required|in_list[Produktif,Umum]',
        ];

        if (! $this->validate($rules)) {
            return view('MasterMapel/input-mapel', [
                'errors'     => $this->validator->getErrors(),
                'nama_mapel'   => $this->request->getPost('nama_mapel'),
                'status'     => $this->request->getPost('status'),
            ]);
        }


        $this->model->insert([
            'nama_mapel' => $this->request->getPost('nama_mapel'),
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
        ]);
    }

    public function update($id)
    {
        $rules = [
            'nama_mapel' => 'required|max_length[100]',
            'status'     => 'required|in_list[Produktif,Umum]',
        ];

        if (! $this->validate($rules)) {
            $mapel = $this->model->find($id);
            return view('MasterMapel/edit-mapel', [
                'mapel'      => $mapel,
                'errors'     => $this->validator->getErrors(),
            ]);
        }

        $this->model->update($id, [
            'nama_mapel' => $this->request->getPost('nama_mapel'),
            'status'     => $this->request->getPost('status'),
        ]);

        return redirect()->to(base_url('mapel'))->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function hapus($id)
    {
        return $this->deleteEntityByTable(
            'tbl_mata_pelajaran',
            $id,
            base_url('mapel'),
            'Mata pelajaran berhasil dihapus.'
        );
    }
}
