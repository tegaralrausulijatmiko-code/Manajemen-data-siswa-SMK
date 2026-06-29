<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    public function index()
    {
        $userId = session()->get('id_user');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to(base_url('logout'));
        }

        return view('profile/index', ['user' => $user]);
    }

    public function updatePassword()
    {
        $userId = session()->get('id_user');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $oldPass = $this->request->getPost('password_lama');
        $newPass = $this->request->getPost('password_baru');
        $confPass = $this->request->getPost('konfirmasi_password');

        if (!password_verify($oldPass, $user['password'])) {
            return redirect()->back()->with('error', 'Password lama yang Anda masukkan salah.');
        }

        if (empty($newPass) || strlen($newPass) < 6) {
            return redirect()->back()->with('error', 'Password baru minimal harus 6 karakter.');
        }

        if ($newPass !== $confPass) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok dengan password baru.');
        }

        $userModel->update($userId, [
            'password' => password_hash($newPass, PASSWORD_DEFAULT)
        ]);

        return redirect()->to(base_url('profile'))->with('success', 'Password berhasil diubah. Silakan gunakan password baru saat login berikutnya.');
    }
}