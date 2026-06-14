<?php

namespace App\Controllers;

use App\Models\TahunAjaranModel;

class TahunAjaran extends BaseController
{
    protected TahunAjaranModel $model;

    public function __construct()
    {
        $this->model = new TahunAjaranModel();
    }

    public function index()
    {
        $paged = $this->paginateArray($this->model->orderBy('tahun_ajaran', 'DESC')->orderBy('semester')->findAll(), 10);

        return view('MasterTahunAjaran/master-data-tahun-ajaran', [
            'tahun_ajaran' => $paged['items'],
            'pagination'   => $paged['pagination'],
        ]);
    }

    public function tambah()
    {
        return view('MasterTahunAjaran/input-tahun-ajaran');
    }

    public function simpan()
    {
        if (! $this->validate($this->rules())) {
            return view('MasterTahunAjaran/input-tahun-ajaran', ['errors' => $this->validator->getErrors()]);
        }

        if ($this->request->getPost('status') === 'Aktif') {
            $this->model->where('status', 'Aktif')->set(['status' => 'Nonaktif'])->update();
        }

        $this->model->insert($this->payload());
        return redirect()->to(base_url('tahun-ajaran'))->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $tahun = $this->model->find($id);
        if (! $tahun) {
            return redirect()->to(base_url('tahun-ajaran'))->with('error', 'Data tidak ditemukan.');
        }

        return view('MasterTahunAjaran/edit-tahun-ajaran', ['tahun' => $tahun]);
    }

    public function update($id)
    {
        if (! $this->validate($this->rules())) {
            return view('MasterTahunAjaran/edit-tahun-ajaran', [
                'tahun'  => $this->model->find($id),
                'errors' => $this->validator->getErrors(),
            ]);
        }

        if ($this->request->getPost('status') === 'Aktif') {
            $this->model->where('id_tahun_ajaran !=', $id)->set(['status' => 'Nonaktif'])->update();
        }

        $this->model->update($id, $this->payload());
        return redirect()->to(base_url('tahun-ajaran'))->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('tahun-ajaran'))->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'tahun_ajaran' => 'required|max_length[9]',
            'semester'     => 'required|in_list[Ganjil,Genap]',
            'status'       => 'required|in_list[Aktif,Nonaktif]',
        ];
    }

    private function payload(): array
    {
        return [
            'tahun_ajaran' => $this->request->getPost('tahun_ajaran'),
            'semester'     => $this->request->getPost('semester'),
            'status'       => $this->request->getPost('status'),
        ];
    }
}
