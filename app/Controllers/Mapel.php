<?php

namespace App\Controllers;

use App\Models\JurusanModel;
use App\Models\KelasModel;
use App\Models\MapelModel;

class Mapel extends BaseController
{
    protected MapelModel $model;
    protected KelasModel $kelasModel;
    protected JurusanModel $jurusanModel;

    public function __construct()
    {
        $this->model        = new MapelModel();
        $this->kelasModel   = new KelasModel();
        $this->jurusanModel = new JurusanModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('q');

        $paged = $this->paginateArray($this->model->getAll($keyword), 10);

        return view('MasterMapel/master-data-mapel', [
            'mapel'      => $paged['items'],
            'keyword'    => $keyword,
            'pagination' => $paged['pagination'],
        ]);
    }

    public function tambah()
    {
        return view('MasterMapel/input-mapel', [
            'jurusan_list' => $this->jurusanModel->findAll(),
        ]);
    }

    public function simpan()
    {
        $rules = [
            'nama_mapel' => 'required|max_length[150]',
            'tingkat'    => 'required|in_list[X,XI,XII]',
            'id_jurusan' => 'permit_empty|integer',
        ];

        if (! $this->validate($rules)) {
            return view('MasterMapel/input-mapel', [
                'errors'        => $this->validator->getErrors(),
                'jurusan_list'  => $this->jurusanModel->findAll(),
            ]);
        }

        $jenis = empty($this->request->getPost('id_jurusan'))
        ? 'Umum'
        : 'Kejuruan';

        $this->model->insert([
            'nama_mapel' => $this->request->getPost('nama_mapel'),
            'tingkat'    => $this->request->getPost('tingkat'),
            'jenis'      => $jenis,
            'id_jurusan' => $this->request->getPost('id_jurusan') ?: null,
        ]);

        return redirect()->to(base_url('mapel'))
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mapel = $this->model->find($id);

        if (! $mapel) {
            return redirect()->to(base_url('mapel'))
                ->with('error', 'Data tidak ditemukan.');
        }

        return view('MasterMapel/edit-mapel', [
            'mapel'         => $mapel,
            'jurusan_list'  => $this->jurusanModel->findAll(),
        ]);
    }

    public function update($id)
    {
        $rules = [
            'nama_mapel' => 'required|max_length[150]',
            'tingkat'    => 'required|in_list[X,XI,XII]',
            'id_jurusan' => 'permit_empty|integer',

        ];

        if (! $this->validate($rules)) {
            return view('MasterMapel/edit-mapel', [
                'mapel'         => $this->model->find($id),
                'errors'        => $this->validator->getErrors(),
                'jurusan_list'  => $this->jurusanModel->findAll(),
            ]);
        }

        $jenis = empty($this->request->getPost('id_jurusan'))
        ? 'Umum'
        : 'Kejuruan';


        $this->model->update($id, [
            'nama_mapel' => $this->request->getPost('nama_mapel'),
            'tingkat'    => $this->request->getPost('tingkat'),
            'jenis'      => $jenis,
            'id_jurusan' => $this->request->getPost('id_jurusan') ?: null,
        ]);

        return redirect()->to(base_url('mapel'))
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $this->model->delete($id);

        return redirect()->to(base_url('mapel'))
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}