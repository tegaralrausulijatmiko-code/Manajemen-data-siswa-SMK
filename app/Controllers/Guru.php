<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\UserModel;

class Guru extends BaseController
{
    protected GuruModel $model;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->model = new GuruModel();
        $this->userModel = new UserModel();
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
            'nip'          => 'required|regex_match[/^\\d{18}$/]|is_unique[tbl_guru.nip]',
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
            'nip'          => "required|regex_match[/^\\d{18}$/]|is_unique[tbl_guru.nip,id_guru,$id]",
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

    public function buatUser($id)
    {
        $guru = $this->model->find($id);
        if (! $guru) {
            return redirect()->to(base_url('guru'))->with('error', 'Data guru tidak ditemukan.');
        }

        $user = trim((string) ($guru['nama_guru'] ?? ''));
        if ($user === '') {
            return redirect()->to(base_url('guru'))->with('error', 'Nama guru belum tersedia.');
            }

        $nip = trim((string) ($guru['nip'] ?? ''));
        if ($nip === '') {
            return redirect()->to(base_url('guru'))->with('error', 'NIP guru belum tersedia.');
            }
        
        $existingUsername = $this->userModel->where('username', $nip)->first();
        if ($existingUsername && (int) ($existingUsername['id_guru'] ?? 0) !== (int) $id) {
            return redirect()->to(base_url('guru'))->with('error', 'NIP sudah dipakai sebagai username akun lain.');
        }

        $payload = [
            'nama'     => $guru['nama_guru'],
            'username' => $nip,
            'password' => password_hash($nip, PASSWORD_DEFAULT),
            'role'     => 'guru',
            'status'   => 'aktif',
            'id_guru'  => $id,
            'id_siswa' => null,
        ];

        $existingGuruUser = $this->userModel->where('id_guru', $id)->where('role', 'guru')->first();
        if ($existingGuruUser) {
            $this->userModel->update($existingGuruUser['id_user'], $payload);
        } else {
            $this->userModel->insert($payload);
        }

        return redirect()->to(base_url('guru'))
            ->with('success', 'Akun guru berhasil dibuat. Username dan password menggunakan NIP: ' . $nip);
    }

    public function hapus($id)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('guru'))->with('success', 'Guru berhasil dihapus.');
    }
}
