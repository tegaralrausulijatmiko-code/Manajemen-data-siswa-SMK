<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\KelasModel;

class Siswa extends BaseController
{
    protected SiswaModel $model;
    protected KelasModel $kelasModel;

    public function __construct()
    {
        $this->model      = new SiswaModel();
        $this->kelasModel = new KelasModel();
    }

    public function index()
    {
        $keyword      = $this->request->getGet('q');
        $filter_kelas = $this->request->getGet('kelas');
        $filter_jk    = $this->request->getGet('jk');

        $paged = $this->paginateArray($this->model->getAll($keyword, $filter_kelas, $filter_jk), 10);

        return view('MasterSiswa/master-data-siswa', [
            'siswa'        => $paged['items'],
            'kelas_list'   => $this->kelasModel->findAll(),
            'total'        => $paged['pagination']['total'],
            'pagination'   => $paged['pagination'],
            'keyword'      => $keyword,
            'filter_kelas' => $filter_kelas,
            'filter_jk'    => $filter_jk,
        ]);
    }

    public function tambah()
    {
        return view('MasterSiswa/input-siswa', [
            'kelas_list' => $this->kelasModel->getKelasWithJurusan(),
        ]);
    }

    public function simpan()
    {
        $nisnRule = 'required|regex_match[/^\\d{10}$/]|is_unique[tbl_siswa.nisn]';

        $rules = [
            'nisn'         => $nisnRule,
            'nama_siswa'   => 'required|max_length[100]',
            'id_kelas'     => 'required|integer',
            'jenis_kelamin'=> 'required|in_list[L,P]',
            'foto'         => 'permit_empty|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]|max_size[foto,2048]',
        ];

        if (! $this->validate($rules)) {
            return view('MasterSiswa/input-siswa', [
                'errors'     => $this->validator->getErrors(),
                'kelas_list' => $this->kelasModel->getKelasWithJurusan(),
            ]);
        }

        $fotoName = null;
        $foto     = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && ! $foto->hasMoved()) {
            $fotoName = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads', $fotoName);
        }

        $this->model->insert([
            'id_kelas'      => $this->request->getPost('id_kelas'),
            'nisn'          => $this->request->getPost('nisn'),
            'nama_siswa'    => $this->request->getPost('nama_siswa'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'alamat'        => $this->request->getPost('alamat'),
            'no_hp'         => $this->request->getPost('no_hp'),
            'foto'          => $fotoName,
        ]);

        return redirect()->to(base_url('siswa'))->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $siswa = $this->model->find($id);
        if (! $siswa) {
            return redirect()->to(base_url('siswa'))->with('error', 'Data tidak ditemukan.');
        }

        return view('MasterSiswa/edit-siswa', [
            'siswa'      => $siswa,
            'kelas_list' => $this->kelasModel->getKelasWithJurusan(),
        ]);
    }

    public function update($id)
    {
        $siswa = $this->model->find($id);
        $nisnRule = "required|regex_match[/^\\d{10}$/]|is_unique[tbl_siswa.nisn,id_siswa,$id]";

        $rules = [
            'nisn'         => $nisnRule,
            'nama_siswa'   => 'required|max_length[100]',
            'id_kelas'     => 'required|integer',
            'jenis_kelamin'=> 'required|in_list[L,P]',
            'foto'         => 'permit_empty|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]|max_size[foto,2048]',
        ];

        if (! $this->validate($rules)) {
            return view('MasterSiswa/edit-siswa', [
                'siswa'      => $siswa,
                'errors'     => $this->validator->getErrors(),
                'kelas_list' => $this->kelasModel->getKelasWithJurusan(),
            ]);
        }

        $fotoName = $siswa['foto'];
        $foto     = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && ! $foto->hasMoved()) {
            // Hapus foto lama
            if ($fotoName && file_exists(ROOTPATH . 'public/uploads/' . $fotoName)) {
                unlink(ROOTPATH . 'public/uploads/' . $fotoName);
            }
            $fotoName = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads', $fotoName);
        }

        $this->model->update($id, [
            'id_kelas'      => $this->request->getPost('id_kelas'),
            'nisn'          => $this->request->getPost('nisn'),
            'nama_siswa'    => $this->request->getPost('nama_siswa'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'alamat'        => $this->request->getPost('alamat'),
            'no_hp'         => $this->request->getPost('no_hp'),
            'foto'          => $fotoName,
        ]);

        return redirect()->to(base_url('siswa'))->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $siswa = $this->model->find($id);
        if ($siswa && ! empty($siswa['foto'])) {
            $path = ROOTPATH . 'public/uploads/' . $siswa['foto'];
            if (file_exists($path)) unlink($path);
        }
        $this->model->delete($id);
        return redirect()->to(base_url('siswa'))->with('success', 'Siswa berhasil dihapus.');
    }
}
