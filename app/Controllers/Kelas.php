<?php

namespace App\Controllers;

use App\Models\KelasModel;

class Kelas extends BaseController
{
    protected $kelasModel;

    public function __construct()
    {
        // Cek apakah user sudah login
        if (!session()->get('isLogin')) {
            return redirect()->to(base_url('login'))->send();
            exit();
        }

        $this->kelasModel = new KelasModel();
    }

    // Menampilkan daftar kelas
    public function index()
    {
        $data = [
            'title' => 'Data Kelas',
            'kelas' => $this->kelasModel->findAll()
        ];

        return view('kelas/index', $data);
    }

    // Form tambah kelas
    public function create()
    {
        $data = [
            'title' => 'Tambah Kelas'
        ];

        return view('kelas/create', $data);
    }

    // Proses tambah kelas
    public function store()
    {
        // Validasi
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_kelas' => 'required|max_length[50]',
            'kompetensi_keahlian' => 'required|max_length[100]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Insert data
        $data = [
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'kompetensi_keahlian' => $this->request->getPost('kompetensi_keahlian')
        ];

        if ($this->kelasModel->insert($data)) {
            return redirect()->to(base_url('kelas'))->with('success', 'Data kelas berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan data kelas');
    }

    // Form edit kelas
    public function edit($id)
    {
        $kelas = $this->kelasModel->find($id);

        if (!$kelas) {
            return redirect()->to(base_url('kelas'))->with('error', 'Data kelas tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Kelas',
            'kelas' => $kelas
        ];

        return view('kelas/edit', $data);
    }

    // Proses update kelas
    public function update($id)
    {
        // Validasi
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_kelas' => 'required|max_length[50]',
            'kompetensi_keahlian' => 'required|max_length[100]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Update data
        $data = [
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'kompetensi_keahlian' => $this->request->getPost('kompetensi_keahlian')
        ];

        if ($this->kelasModel->update($id, $data)) {
            return redirect()->to(base_url('kelas'))->with('success', 'Data kelas berhasil diupdate');
        }

        return redirect()->back()->with('error', 'Gagal mengupdate data kelas');
    }

    // Hapus kelas
    public function delete($id)
    {
        // Cek apakah kelas masih digunakan oleh siswa
        $siswaModel = new \App\Models\SiswaModel();
        $siswaCount = $siswaModel->where('id_kelas', $id)->countAllResults();

        if ($siswaCount > 0) {
            return redirect()->to(base_url('kelas'))->with('error', 'Kelas tidak dapat dihapus karena masih ada siswa yang terdaftar');
        }

        if ($this->kelasModel->delete($id)) {
            return redirect()->to(base_url('kelas'))->with('success', 'Data kelas berhasil dihapus');
        }

        return redirect()->to(base_url('kelas'))->with('error', 'Gagal menghapus data kelas');
    }
}