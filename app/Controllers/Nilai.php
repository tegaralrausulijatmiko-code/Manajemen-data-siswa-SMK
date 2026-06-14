<?php

namespace App\Controllers;

use App\Models\MapelModel;
use App\Models\NilaiModel;
use App\Models\SiswaModel;
use App\Models\TahunAjaranModel;

class Nilai extends BaseController
{
    protected NilaiModel $model;
    protected SiswaModel $siswaModel;
    protected MapelModel $mapelModel;
    protected TahunAjaranModel $tahunModel;

    public function __construct()
    {
        $this->model      = new NilaiModel();
        $this->siswaModel = new SiswaModel();
        $this->mapelModel = new MapelModel();
        $this->tahunModel = new TahunAjaranModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('q');
        $tahun   = $this->request->getGet('tahun');

        $paged = $this->paginateArray($this->model->getAll($keyword, $tahun), 10);

        return view('MasterNilai/master-data-nilai', [
            'nilai'       => $paged['items'],
            'tahun_list'  => $this->tahunModel->orderBy('tahun_ajaran', 'DESC')->findAll(),
            'keyword'     => $keyword,
            'filter_tahun'=> $tahun,
            'pagination'  => $paged['pagination'],
        ]);
    }

    public function tambah()
    {
        return view('MasterNilai/input-nilai', $this->getFormData());
    }

    public function simpan()
    {
        if (! $this->validate($this->rules())) {
            return view('MasterNilai/input-nilai', $this->getFormData([
                'errors' => $this->validator->getErrors(),
            ]));
        }

        $this->model->insert($this->payload());
        return redirect()->to(base_url('nilai'))->with('success', 'Nilai berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $nilai = $this->model->find($id);
        if (! $nilai) {
            return redirect()->to(base_url('nilai'))->with('error', 'Data tidak ditemukan.');
        }

        return view('MasterNilai/edit-nilai', $this->getFormData(['nilai' => $nilai]));
    }

    public function update($id)
    {
        if (! $this->validate($this->rules())) {
            return view('MasterNilai/edit-nilai', $this->getFormData([
                'nilai'  => $this->model->find($id),
                'errors' => $this->validator->getErrors(),
            ]));
        }

        $this->model->update($id, $this->payload());
        return redirect()->to(base_url('nilai'))->with('success', 'Nilai berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('nilai'))->with('success', 'Nilai berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'id_siswa'        => 'required|integer',
            'id_mapel'        => 'required|integer',
            'id_tahun_ajaran' => 'required|integer',
            'nilai_tugas'     => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
            'nilai_uts'       => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
            'nilai_uas'       => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];
    }

    private function payload(): array
    {
        $tugas = (float) $this->request->getPost('nilai_tugas');
        $uts   = (float) $this->request->getPost('nilai_uts');
        $uas   = (float) $this->request->getPost('nilai_uas');
        $akhir = round(($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4), 2);

        return [
            'id_siswa'        => $this->request->getPost('id_siswa'),
            'id_mapel'        => $this->request->getPost('id_mapel'),
            'id_tahun_ajaran' => $this->request->getPost('id_tahun_ajaran'),
            'nilai_tugas'     => $tugas,
            'nilai_uts'       => $uts,
            'nilai_uas'       => $uas,
            'nilai_akhir'     => $akhir,
            'predikat'        => $this->predikat($akhir),
        ];
    }

    private function predikat(float $nilai): string
    {
        if ($nilai >= 90) return 'A';
        if ($nilai >= 80) return 'B';
        if ($nilai >= 70) return 'C';
        if ($nilai >= 60) return 'D';
        return 'E';
    }

    private function getFormData(array $extra = []): array
    {
        return array_merge([
            'siswa_list' => $this->siswaModel->getAll(),
            'mapel_list' => $this->mapelModel->findAll(),
            'tahun_list' => $this->tahunModel->orderBy('tahun_ajaran', 'DESC')->findAll(),
        ], $extra);
    }
}
