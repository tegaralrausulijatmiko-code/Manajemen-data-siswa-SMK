<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\JurusanModel;
use App\Models\GuruModel;

class Kelas extends BaseController
{
    protected KelasModel   $model;
    protected JurusanModel $jurusanModel;
    protected GuruModel    $guruModel;

    public function __construct()
    {
        $this->model        = new KelasModel();
        $this->jurusanModel = new JurusanModel();
        $this->guruModel    = new GuruModel();
    }

    public function index()
    {
        $keyword       = $this->request->getGet('q');
        $filter_tingkat = $this->request->getGet('tingkat');

        $paged = $this->paginateArray($this->model->getAll($keyword, $filter_tingkat), 10);

        return view('MasterKelas/master-data-kelas', [
            'kelas'          => $paged['items'],
            'keyword'        => $keyword,
            'filter_tingkat' => $filter_tingkat,
            'pagination'     => $paged['pagination'],
        ]);
    }

    public function tambah()
    {
        return view('MasterKelas/input-kelas', [
            'jurusan_list' => $this->jurusanModel->findAll(),
            'guru_list'    => $this->guruModel->findAll(),
        ]);
    }

    public function simpan()
    {
        $rules = [
            'nama_kelas' => 'required|max_length[50]',
            'tingkat'    => 'required|in_list[X,XI,XII]',
            'id_jurusan' => 'required|integer',
            'id_wali_kelas' => 'permit_empty|integer',
            'jumlah_siswa'  => 'permit_empty|integer',
        ];

        if (! $this->validate($rules)) {
            return view('MasterKelas/input-kelas', [
                'errors'       => $this->validator->getErrors(),
                'jurusan_list' => $this->jurusanModel->findAll(),
                'guru_list'    => $this->guruModel->findAll(),
            ]);
        }

        $this->model->insert([
            'id_jurusan'    => $this->request->getPost('id_jurusan'),
            'nama_kelas'    => $this->request->getPost('nama_kelas'),
            'tingkat'       => $this->request->getPost('tingkat'),
            'id_wali_kelas' => $this->request->getPost('id_wali_kelas') ?: null,
            'jumlah_siswa'  => $this->request->getPost('jumlah_siswa') ?: 0,
        ]);

        return redirect()->to(base_url('kelas'))->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kelas = $this->model->find($id);
        if (! $kelas) {
            return redirect()->to(base_url('kelas'))->with('error', 'Data tidak ditemukan.');
        }

        return view('MasterKelas/edit-kelas', [
            'kelas'        => $kelas,
            'jurusan_list' => $this->jurusanModel->findAll(),
            'guru_list'    => $this->guruModel->findAll(),
        ]);
    }

    public function update($id)
    {
        $rules = [
            'nama_kelas'     => 'required|max_length[50]',
            'tingkat'        => 'required|in_list[X,XI,XII]',
            'id_jurusan'     => 'required|integer',
            'id_wali_kelas'  => 'permit_empty|integer',
            'jumlah_siswa'   => 'permit_empty|integer',
        ];

        if (! $this->validate($rules)) {
            return view('MasterKelas/edit-kelas', [
                'kelas'        => $this->model->find($id),
                'errors'       => $this->validator->getErrors(),
                'jurusan_list' => $this->jurusanModel->findAll(),
                'guru_list'    => $this->guruModel->findAll(),
            ]);
        }

        $this->model->update($id, [
            'id_jurusan'    => $this->request->getPost('id_jurusan'),
            'nama_kelas'    => $this->request->getPost('nama_kelas'),
            'tingkat'       => $this->request->getPost('tingkat'),
            'id_wali_kelas' => $this->request->getPost('id_wali_kelas') ?: null,
            'jumlah_siswa'  => $this->request->getPost('jumlah_siswa') ?: 0,
        ]);

        return redirect()->to(base_url('kelas'))->with('success', 'Kelas berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('kelas'))->with('success', 'Kelas berhasil dihapus.');
    }
}
