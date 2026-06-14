<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('auth/login');
    }

    public function proses()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return view('auth/login', [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $user = $this->userModel
            ->where('username', $this->request->getPost('username'))
            ->where('status', 'aktif')
            ->first();

        if (! $user || ! password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        session()->set([
            'is_logged_in' => true,
            'id_user'      => $user['id_user'],
            'nama'         => $user['nama'],
            'username'     => $user['username'],
            'role'         => $user['role'],
            'id_guru'      => $user['id_guru'],
            'id_siswa'     => $user['id_siswa'],
        ]);

        return redirect()->to(base_url('dashboard'))->with('success', 'Login berhasil.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'Anda berhasil logout.');
    }
}
