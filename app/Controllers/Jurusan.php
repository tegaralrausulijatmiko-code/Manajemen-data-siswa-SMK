<?php

namespace App\Controllers;

use App\Models\JurusanModel;
use App\Models\GuruModel;

class Jurusan extends BaseController
{
    protected JurusanModel $model;
    protected GuruModel    $guruModel;

    public function __construct()
    {
        $this->model     = new JurusanModel();
        $this->guruModel = new GuruModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('q');

        $paged = $this->paginateArray($keyword ? $this->model->search($keyword) : $this->model->getAll(), 10);

        return view('MasterJurusan/master-data-jurusan', [
            'jurusan'    => $paged['items'],
            'keyword'    => $keyword,
            'pagination' => $paged['pagination'],
        ]);
    }

    public function tambah()
    {
        return view('MasterJurusan/input-jurusan', [
            'guru_list' => $this->guruModel->findAll(),
        ]);
    }

    public function simpan()
    {
        $rules = [
            'kode_jurusan' => 'required|max_length[10]|is_unique[tbl_jurusan.kode_jurusan]',
            'nama_jurusan' => 'required|max_length[100]',
            'id_kaprog'    => 'permit_empty|integer',
        ];

        if (! $this->validate($rules)) {
            return view('MasterJurusan/input-jurusan', [
                'errors'    => $this->validator->getErrors(),
                'guru_list' => $this->guruModel->findAll(),
            ]);
        }

        $this->model->insert([
            'kode_jurusan' => strtoupper($this->request->getPost('kode_jurusan')),
            'nama_jurusan' => $this->request->getPost('nama_jurusan'),
            'id_kaprog'    => $this->request->getPost('id_kaprog') ?: null,
        ]);

        return redirect()->to(base_url('jurusan'))->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jurusan = $this->model->find($id);
        if (! $jurusan) {
            return redirect()->to(base_url('jurusan'))->with('error', 'Data tidak ditemukan.');
        }

        return view('MasterJurusan/edit-jurusan', [
            'jurusan'   => $jurusan,
            'guru_list' => $this->guruModel->findAll(),
        ]);
    }

    public function update($id)
    {
        $rules = [
            'kode_jurusan' => "required|max_length[10]|is_unique[tbl_jurusan.kode_jurusan,id_jurusan,$id]",
            'nama_jurusan' => 'required|max_length[100]',
            'id_kaprog'    => 'permit_empty|integer',
        ];

        if (! $this->validate($rules)) {
            $jurusan = $this->model->find($id);
            return view('MasterJurusan/edit-jurusan', [
                'jurusan'   => $jurusan,
                'errors'    => $this->validator->getErrors(),
                'guru_list' => $this->guruModel->findAll(),
            ]);
        }

        $this->model->update($id, [
            'kode_jurusan' => strtoupper($this->request->getPost('kode_jurusan')),
            'nama_jurusan' => $this->request->getPost('nama_jurusan'),
            'id_kaprog'    => $this->request->getPost('id_kaprog') ?: null,
        ]);

        return redirect()->to(base_url('jurusan'))->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('jurusan'))->with('success', 'Jurusan berhasil dihapus.');
    }
}
