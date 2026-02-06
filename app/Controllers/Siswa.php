<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\KelasModel;

class Siswa extends BaseController
{
    protected $siswaModel;
    protected $kelasModel;

    public function __construct()
    {
        // Cek apakah user sudah login
        if (!session()->get('isLogin')) {
            return redirect()->to(base_url('login'))->send();
            exit();
        }

        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
    }

    // Menampilkan daftar siswa
    public function index()
    {
        $data = [
            'title' => 'Data Siswa',
            'siswa' => $this->siswaModel->getSiswaWithKelas()
        ];

        return view('siswa/index', $data);
    }

    // Form tambah siswa
    public function create()
    {
        $data = [
            'title' => 'Tambah Siswa',
            'kelas' => $this->kelasModel->findAll()
        ];

        return view('siswa/create', $data);
    }

    // Proses tambah siswa
    public function store()
    {
        // Validasi
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nis' => 'required|is_unique[siswa.nis]|max_length[20]',
            'nama' => 'required|max_length[100]',
            'alamat' => 'required',
            'no_telp' => 'required|max_length[15]',
            'id_kelas' => 'required|integer'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Insert data
        $data = [
            'nis' => $this->request->getPost('nis'),
            'nama' => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
            'no_telp' => $this->request->getPost('no_telp'),
            'id_kelas' => $this->request->getPost('id_kelas')
        ];

        if ($this->siswaModel->insert($data)) {
            return redirect()->to(base_url('siswa'))->with('success', 'Data siswa berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan data siswa');
    }

    // Form edit siswa
    public function edit($id)
    {
        $siswa = $this->siswaModel->find($id);

        if (!$siswa) {
            return redirect()->to(base_url('siswa'))->with('error', 'Data siswa tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Siswa',
            'siswa' => $siswa,
            'kelas' => $this->kelasModel->findAll()
        ];

        return view('siswa/edit', $data);
    }

    // Proses update siswa
    public function update($id)
    {
        // Validasi
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nis' => "required|max_length[20]|is_unique[siswa.nis,id,$id]",
            'nama' => 'required|max_length[100]',
            'alamat' => 'required',
            'no_telp' => 'required|max_length[15]',
            'id_kelas' => 'required|integer'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Update data
        $data = [
            'nis' => $this->request->getPost('nis'),
            'nama' => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
            'no_telp' => $this->request->getPost('no_telp'),
            'id_kelas' => $this->request->getPost('id_kelas')
        ];

        if ($this->siswaModel->update($id, $data)) {
            return redirect()->to(base_url('siswa'))->with('success', 'Data siswa berhasil diupdate');
        }

        return redirect()->back()->with('error', 'Gagal mengupdate data siswa');
    }

    // Hapus siswa
    public function delete($id)
    {
        if ($this->siswaModel->delete($id)) {
            return redirect()->to(base_url('siswa'))->with('success', 'Data siswa berhasil dihapus');
        }

        return redirect()->to(base_url('siswa'))->with('error', 'Gagal menghapus data siswa');
    }
}