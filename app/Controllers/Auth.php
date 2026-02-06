<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        // Redirect jika sudah login
        if (session()->get('isLogin')) {
            $role = session()->get('role');
            if ($role == 'admin') {
                return redirect()->to(base_url('dashboard'));
            } else {
                return redirect()->to(base_url('siswa/dashboard'));
            }
        }

        return view('auth/login');
    }

    public function login()
    {
        $userModel = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Cek user dengan join siswa
        $user = $userModel->getUserWithSiswa($username);

        if ($user && $user['password'] === $password) {
            
            // Set session berdasarkan role
            $sessionData = [
                'user_id'  => $user['id'],
                'username' => $user['username'],
                'role'     => $user['role'],
                'isLogin'  => true
            ];

            // Jika siswa, tambah data siswa ke session
            if ($user['role'] == 'siswa') {
                $sessionData['id_siswa'] = $user['id_siswa'];
                $sessionData['nama'] = $user['nama'];
                $sessionData['nis'] = $user['nis'];
                $sessionData['nama_kelas'] = $user['nama_kelas'];
            }

            session()->set($sessionData);

            // Redirect berdasarkan role
            if ($user['role'] == 'admin') {
                return redirect()->to(base_url('dashboard'));
            } else {
                return redirect()->to(base_url('siswa/dashboard'));
            }
        }

        return redirect()->back()->with('error', 'Username atau password salah');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function processRegister()
    {
        $userModel = new UserModel();
        $siswaModel = new \App\Models\SiswaModel();

        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirm_password = $this->request->getPost('confirm_password');

        // Validasi basic
        if (empty($username) || empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Semua field harus diisi');
        }

        if ($password !== $confirm_password) {
            return redirect()->back()->with('error', 'Password dan konfirmasi password tidak sama');
        }

        // Cek username sudah dipakai
        $existingUser = $userModel->where('username', $username)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'Username/NIS sudah digunakan');
        }

        // Cek email sudah dipakai
        $existingEmail = $userModel->where('email', $email)->first();
        if ($existingEmail) {
            return redirect()->back()->with('error', 'Email sudah digunakan');
        }

        // CEK APAKAH USERNAME ADALAH NIS SISWA
        $siswa = $siswaModel->where('nis', $username)->first();
        
        if ($siswa) {
            // Ini SISWA - username nya adalah NIS
            
            // Cek apakah siswa ini sudah punya akun
            $existingSiswa = $userModel->where('id_siswa', $siswa['id'])->first();
            if ($existingSiswa) {
                return redirect()->back()->with('error', 'NIS ini sudah memiliki akun');
            }

            // Buat akun siswa
            $data = [
                'username' => $username, // Username = NIS
                'email' => $email,
                'password' => $password,
                'role' => 'siswa',
                'id_siswa' => $siswa['id']
            ];

            if ($userModel->insert($data)) {
                return redirect()->to(base_url('login'))->with('success', 'Registrasi siswa berhasil! Silakan login dengan NIS Anda.');
            }

        } else {
            // Ini ADMIN - username bukan NIS
            
            $data = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'role' => 'admin'
            ];

            if ($userModel->insert($data)) {
                return redirect()->to(base_url('login'))->with('success', 'Registrasi berhasil! Silakan login.');
            }
        }

        return redirect()->back()->with('error', 'Registrasi gagal');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}