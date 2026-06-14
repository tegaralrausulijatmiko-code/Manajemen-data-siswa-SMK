<?php

namespace App\Controllers;

use App\Models\GuruModel;

class Guru extends BaseController
{
    protected GuruModel $model;

    public function __construct()
    {
        $this->model = new GuruModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('q');
        $paged = $this->paginateArray($keyword ? $this->model->search($keyword) : $this->model->findAll(), 10);

        return view('MasterGuru/master-data-guru', [
            'guru'       => $paged['items'],
            'keyword'    => $keyword,
            'pagination' => $paged['pagination'],
        ]);
    }

    public function tambah()
    {
        return view('MasterGuru/input-guru');
    }

    public function simpan()
    {
        $rules = [
            'nip'          => 'required|max_length[20]|is_unique[tbl_guru.nip]',
            'nama_guru'    => 'required|max_length[100]',
            'jenis_kelamin'=> 'required|in_list[L,P]',
        ];

        if (! $this->validate($rules)) {
            return view('MasterGuru/input-guru', [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $this->model->insert([
            'nip'          => $this->request->getPost('nip'),
            'nama_guru'    => $this->request->getPost('nama_guru'),
            'jenis_kelamin'=> $this->request->getPost('jenis_kelamin'),
            'no_hp'        => $this->request->getPost('no_hp'),
            'alamat'       => $this->request->getPost('alamat'),
        ]);

        return redirect()->to(base_url('guru'))->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $guru = $this->model->find($id);
        if (! $guru) {
            return redirect()->to(base_url('guru'))->with('error', 'Data tidak ditemukan.');
        }

        return view('MasterGuru/edit-guru', ['guru' => $guru]);
    }

    public function update($id)
    {
        $rules = [
            'nip'          => "required|max_length[20]|is_unique[tbl_guru.nip,id_guru,$id]",
            'nama_guru'    => 'required|max_length[100]',
            'jenis_kelamin'=> 'required|in_list[L,P]',
        ];

        if (! $this->validate($rules)) {
            $guru = $this->model->find($id);
            return view('MasterGuru/edit-guru', [
                'guru'   => $guru,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $this->model->update($id, [
            'nip'          => $this->request->getPost('nip'),
            'nama_guru'    => $this->request->getPost('nama_guru'),
            'jenis_kelamin'=> $this->request->getPost('jenis_kelamin'),
            'no_hp'        => $this->request->getPost('no_hp'),
            'alamat'       => $this->request->getPost('alamat'),
        ]);

        return redirect()->to(base_url('guru'))->with('success', 'Data guru berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('guru'))->with('success', 'Guru berhasil dihapus.');
    }
}
