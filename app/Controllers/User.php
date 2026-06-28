<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\UserModel;

class User extends BaseController
{
    protected UserModel $model;
    protected GuruModel $guruModel;

    public function __construct()
    {
        $this->model     = new UserModel();
        $this->guruModel = new GuruModel();
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function guruOptions(): array
    {
        return $this->guruModel->orderBy('nama_guru')->findAll();
    }

    // ── CRUD ─────────────────────────────────────────────────────────────────

    public function index()
    {
        $keyword = $this->request->getGet('q');
        $role    = $this->request->getGet('role');

        $builder = $this->model->orderBy('nama');

        if ($keyword) {
            $builder->groupStart()
                ->like('nama', $keyword)
                ->orLike('username', $keyword)
                ->groupEnd();
        }

        if ($role) {
            $builder->where('role', $role);
        }

        $paged = $this->paginateArray($builder->findAll(), 10);

        return view('MasterUser/master-data-user', [
            'users'      => $paged['items'],
            'keyword'    => $keyword,
            'role'       => $role,
            'pagination' => $paged['pagination'],
        ]);
    }

    public function tambah()
    {
        return view('MasterUser/input-user', [
            'guruList' => $this->guruOptions(),
        ]);
    }

    public function simpan()
    {
        $role = $this->request->getPost('role');

        $rules = [
            'nama'     => 'required|max_length[100]',
            'username' => 'required|max_length[100]|is_unique[tbl_users.username]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,guru,bk]',
            'status'   => 'required|in_list[aktif,nonaktif]',
        ];

        if (in_array($role, ['guru', 'bk'])) {
            $rules['id_guru'] = 'required|integer';
        }

        $idGuru = in_array($role, ['guru', 'bk']) ? (int) $this->request->getPost('id_guru') : null;
        $nama = $this->request->getPost('nama');
        $username = $this->request->getPost('username');

        if (in_array($role, ['guru', 'bk']) && $idGuru) {
            $guru = $this->guruModel->find($idGuru);
            if ($guru) {
                $nama = $guru['nama_guru'];
                $username = trim((string) ($guru['nip'] ?? ''));
            }
        }

        $old = $this->request->getPost();
        $old['nama'] = $nama;
        $old['username'] = $username;

        if (! $this->validate($rules)) {
            return view('MasterUser/input-user', [
                'guruList' => $this->guruOptions(),
                'errors'   => $this->validator->getErrors(),
                'old'      => $old,
            ]);
        }

        $this->model->insert([
            'nama'     => $nama,
            'username' => $username,
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $role,
            'status'   => $this->request->getPost('status'),
            'id_guru'  => $idGuru,
            'id_siswa' => null,
        ]);

        return redirect()->to(base_url('user'))->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = $this->model->find($id);
        if (! $user) {
            return redirect()->to(base_url('user'))->with('error', 'User tidak ditemukan.');
        }

        if (in_array($user['role'], ['guru', 'bk']) && ! empty($user['id_guru'])) {
            $guru = $this->guruModel->find($user['id_guru']);
            if ($guru) {
                $user['username'] = trim((string) ($guru['nip'] ?? ''));
            }
        }

        return view('MasterUser/edit-user', [
            'user'     => $user,
            'guruList' => $this->guruOptions(),
        ]);
    }

    public function update($id)
    {
        $user = $this->model->find($id);
        if (! $user) {
            return redirect()->to(base_url('user'))->with('error', 'User tidak ditemukan.');
        }

        $role = $this->request->getPost('role');

        $rules = [
            'nama'     => 'required|max_length[100]',
            'username' => "required|max_length[100]|is_unique[tbl_users.username,id_user,$id]",
            'role'     => 'required|in_list[admin,guru,bk]',
            'status'   => 'required|in_list[aktif,nonaktif]',
        ];

        // Password opsional saat edit — hanya validasi jika diisi
        $newPassword = $this->request->getPost('password');
        if ($newPassword !== '') {
            $rules['password'] = 'min_length[6]';
        }

        if (in_array($role, ['guru', 'bk'])) {
            $rules['id_guru'] = 'required|integer';
        }

        $idGuru = in_array($role, ['guru', 'bk']) ? (int) $this->request->getPost('id_guru') : null;

        $nama = $this->request->getPost('nama');
        $username = $this->request->getPost('username');
        if (in_array($role, ['guru', 'bk']) && $idGuru) {
            $guru = $this->guruModel->find($idGuru);
            if ($guru) {
                $nama = $guru['nama_guru'];
                $username = trim((string) ($guru['nip'] ?? ''));
            }
        }

        $userData = $user;
        $userData['nama'] = $nama;
        $userData['username'] = $username;
        $userData['role'] = $role;
        $userData['status'] = $this->request->getPost('status');
        $userData['id_guru'] = $idGuru;

        if (! $this->validate($rules)) {
            return view('MasterUser/edit-user', [
                'user'     => $userData,
                'guruList' => $this->guruOptions(),
                'errors'   => $this->validator->getErrors(),
            ]);
        }

        $payload = [
            'nama'     => $nama,
            'username' => $username,
            'role'     => $role,
            'status'   => $this->request->getPost('status'),
            'id_guru'  => $idGuru,
        ];

        if ($newPassword !== '') {
            $payload['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $this->model->update($id, $payload);

        return redirect()->to(base_url('user'))->with('success', 'User berhasil diperbarui.');
    }

    public function toggleStatus($id)
    {
        $user = $this->model->find($id);
        if (! $user) {
            return redirect()->to(base_url('user'))->with('error', 'User tidak ditemukan.');
        }

        $newStatus = $user['status'] === 'aktif' ? 'nonaktif' : 'aktif';
        $this->model->update($id, ['status' => $newStatus]);

        $label = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->to(base_url('user'))->with('success', "User berhasil $label.");
    }

    public function resetPassword($id)
    {
        $user = $this->model->find($id);
        if (! $user) {
            return redirect()->to(base_url('user'))->with('error', 'User tidak ditemukan.');
        }

        // Reset ke username sebagai password default
        $defaultPassword = $user['username'];
        $this->model->update($id, [
            'password' => password_hash($defaultPassword, PASSWORD_DEFAULT),
        ]);

        return redirect()->to(base_url('user'))
            ->with('success', "Password berhasil direset. Password baru: <strong>{$defaultPassword}</strong>");
    }

    public function hapus($id)
    {
        return $this->deleteEntityByTable(
            'tbl_users',
            $id,
            base_url('user'),
            'User berhasil dihapus.'
        );
    }
}