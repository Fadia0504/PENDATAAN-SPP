<?php

namespace App\Controllers;

use App\Models\GuruModel;

class Guru extends BaseController
{
    protected $guruModel;

    public function __construct()
    {
        $this->guruModel = new GuruModel();
        helper(['form', 'url']);
    }

    private function checkAuth()
    {
        if (!session()->get('isLogin') || session()->get('role') != 'admin') {
            return redirect()->to(base_url('login'));
        }
        return null;
    }

    /**
     * Index - Daftar Guru
     */
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $keyword = $this->request->getGet('search');
        
        if ($keyword) {
            $guru = $this->guruModel->searchGuru($keyword);
        } else {
            $guru = $this->guruModel->orderBy('nama', 'ASC')->findAll();
        }

        $data = [
            'title' => 'Data Guru',
            'guru' => $guru,
            'statistik' => $this->guruModel->getStatistik()
        ];

        return view('guru/index', $data);
    }

    /**
     * Create - Form Tambah Guru
     */
    public function create()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $data = [
            'title' => 'Tambah Data Guru',
            'validation' => \Config\Services::validation()
        ];

        return view('guru/create', $data);
    }

    /**
     * Store - Simpan Data Guru
     */
    public function store()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        // Validation rules
        $rules = [
            'nip' => 'required|is_unique[guru.nip]|min_length[10]|max_length[20]',
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'permit_empty|valid_email|is_unique[guru.email]',
            'no_telepon' => 'permit_empty|numeric|min_length[10]|max_length[15]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'foto' => 'permit_empty|uploaded[foto]|max_size[foto,2048]|is_image[foto]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Handle foto upload
        $fotoName = null;
        $foto = $this->request->getFile('foto');
        
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $fotoName = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/foto_guru', $fotoName);
        }

        // Prepare data
        $data = [
            'nip' => $this->request->getPost('nip'),
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'alamat' => $this->request->getPost('alamat'),
            'foto' => $fotoName,
            'mata_pelajaran' => $this->request->getPost('mata_pelajaran'),
            'status' => $this->request->getPost('status') ?: 'aktif',
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir'),
            'gelar' => $this->request->getPost('gelar')
        ];

        if ($this->guruModel->insert($data)) {
            return redirect()->to(base_url('guru'))
                ->with('success', 'Data guru berhasil ditambahkan');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal menambahkan data guru');
    }

    /**
     * Edit - Form Edit Guru
     */
    public function edit($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $guru = $this->guruModel->find($id);
        
        if (!$guru) {
            return redirect()->to(base_url('guru'))
                ->with('error', 'Data guru tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Data Guru',
            'guru' => $guru,
            'validation' => \Config\Services::validation()
        ];

        return view('guru/edit', $data);
    }

    /**
     * Update - Update Data Guru
     */
    public function update($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $guru = $this->guruModel->find($id);
        
        if (!$guru) {
            return redirect()->to(base_url('guru'))
                ->with('error', 'Data guru tidak ditemukan');
        }

        // Validation rules
        $rules = [
            'nip' => "required|is_unique[guru.nip,id,{$id}]|min_length[10]|max_length[20]",
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => "permit_empty|valid_email|is_unique[guru.email,id,{$id}]",
            'no_telepon' => 'permit_empty|numeric|min_length[10]|max_length[15]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'foto' => 'permit_empty|uploaded[foto]|max_size[foto,2048]|is_image[foto]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Handle foto upload
        $fotoName = $guru['foto']; // Keep old foto if not updated
        $foto = $this->request->getFile('foto');
        
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Delete old foto
            if (!empty($guru['foto'])) {
                $oldFoto = ROOTPATH . 'public/uploads/foto_guru/' . $guru['foto'];
                if (file_exists($oldFoto)) {
                    unlink($oldFoto);
                }
            }
            
            // Upload new foto
            $fotoName = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/foto_guru', $fotoName);
        }

        // Prepare data
        $data = [
            'nip' => $this->request->getPost('nip'),
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'alamat' => $this->request->getPost('alamat'),
            'foto' => $fotoName,
            'mata_pelajaran' => $this->request->getPost('mata_pelajaran'),
            'status' => $this->request->getPost('status'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir'),
            'gelar' => $this->request->getPost('gelar')
        ];

        if ($this->guruModel->update($id, $data)) {
            return redirect()->to(base_url('guru'))
                ->with('success', 'Data guru berhasil diupdate');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal mengupdate data guru');
    }

    /**
     * Delete - Hapus Data Guru
     */
    public function delete($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $guru = $this->guruModel->find($id);
        
        if (!$guru) {
            return redirect()->to(base_url('guru'))
                ->with('error', 'Data guru tidak ditemukan');
        }

        // Delete foto file
        if (!empty($guru['foto'])) {
            $fotoPath = ROOTPATH . 'public/uploads/foto_guru/' . $guru['foto'];
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
        }

        if ($this->guruModel->delete($id)) {
            return redirect()->to(base_url('guru'))
                ->with('success', 'Data guru berhasil dihapus');
        }

        return redirect()->to(base_url('guru'))
            ->with('error', 'Gagal menghapus data guru');
    }

    /**
     * Detail Guru
     */
    public function detail($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $guru = $this->guruModel->find($id);
        
        if (!$guru) {
            return redirect()->to(base_url('guru'))
                ->with('error', 'Data guru tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Guru',
            'guru' => $guru
        ];

        return view('guru/detail', $data);
    }
}