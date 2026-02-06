<?php

namespace App\Controllers;

use App\Models\JenisPembayaranModel;
use App\Models\TagihanPembayaranLainModel;
use App\Models\SiswaModel;
use App\Models\KelasModel;

class JenisPembayaranLain extends BaseController
{
    protected $jenisPembayaranModel;
    protected $tagihanModel;
    protected $siswaModel;
    protected $kelasModel;

    public function __construct()
    {
        $this->jenisPembayaranModel = new JenisPembayaranModel();
        $this->tagihanModel = new TagihanPembayaranLainModel();
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
    }

    private function checkAuth()
    {
        if (!session()->get('isLogin') || session()->get('role') != 'admin') {
            return redirect()->to(base_url('login'));
        }
        return null;
    }

    // ========== MASTER JENIS PEMBAYARAN ==========
    
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $data = [
            'title' => 'Jenis Pembayaran Lain',
            'jenis_pembayaran' => $this->jenisPembayaranModel->findAll()
        ];

        return view('jenis_pembayaran_lain/index', $data);
    }

    public function create()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $data = ['title' => 'Tambah Jenis Pembayaran'];
        return view('jenis_pembayaran_lain/create', $data);
    }

    public function store()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_pembayaran' => 'required',
            'nominal' => 'required|numeric',
            'kategori' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama_pembayaran' => $this->request->getPost('nama_pembayaran'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'nominal' => $this->request->getPost('nominal'),
            'kategori' => $this->request->getPost('kategori'),
            'status' => 'aktif'
        ];

        if ($this->jenisPembayaranModel->insert($data)) {
            return redirect()->to(base_url('jenis-pembayaran-lain'))->with('success', 'Jenis pembayaran berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan jenis pembayaran');
    }

    public function edit($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $jenis = $this->jenisPembayaranModel->find($id);
        if (!$jenis) {
            return redirect()->to(base_url('jenis-pembayaran-lain'))->with('error', 'Data tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Jenis Pembayaran',
            'jenis' => $jenis
        ];

        return view('jenis_pembayaran_lain/edit', $data);
    }

    public function update($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_pembayaran' => 'required',
            'nominal' => 'required|numeric',
            'kategori' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama_pembayaran' => $this->request->getPost('nama_pembayaran'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'nominal' => $this->request->getPost('nominal'),
            'kategori' => $this->request->getPost('kategori'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->jenisPembayaranModel->update($id, $data)) {
            return redirect()->to(base_url('jenis-pembayaran-lain'))->with('success', 'Jenis pembayaran berhasil diupdate');
        }

        return redirect()->back()->with('error', 'Gagal mengupdate jenis pembayaran');
    }

    public function delete($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        if ($this->jenisPembayaranModel->delete($id)) {
            return redirect()->to(base_url('jenis-pembayaran-lain'))->with('success', 'Jenis pembayaran berhasil dihapus');
        }

        return redirect()->to(base_url('jenis-pembayaran-lain'))->with('error', 'Gagal menghapus jenis pembayaran');
    }

    // ========== TAGIHAN ==========
    
    public function tagihan()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $data = [
            'title' => 'Tagihan Pembayaran Lain',
            'tagihan' => $this->tagihanModel->getTagihanWithDetails()
        ];

        return view('jenis_pembayaran_lain/tagihan', $data);
    }

    public function kirimTagihan()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $data = [
            'title' => 'Kirim Tagihan Pembayaran Lain',
            'jenis_pembayaran' => $this->jenisPembayaranModel->where('status', 'aktif')->findAll(),
            'siswa' => $this->siswaModel->getSiswaWithKelas(),
            'kelas' => $this->kelasModel->findAll()
        ];

        return view('jenis_pembayaran_lain/create_tagihan', $data);
    }

    public function prosesKirimTagihan()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $tipe = $this->request->getPost('tipe_tagihan');
        $id_jenis_pembayaran = $this->request->getPost('id_jenis_pembayaran');
        $jatuh_tempo = $this->request->getPost('tanggal_jatuh_tempo');

        $jenis = $this->jenisPembayaranModel->find($id_jenis_pembayaran);
        if (!$jenis) {
            return redirect()->back()->with('error', 'Jenis pembayaran tidak ditemukan');
        }

        $success = 0;
        $failed = 0;

        if ($tipe == 'individu') {
            $id_siswa = $this->request->getPost('id_siswa');
            
            $data = [
                'id_siswa' => $id_siswa,
                'id_jenis_pembayaran' => $id_jenis_pembayaran,
                'jumlah_tagihan' => $jenis['nominal'],
                'tanggal_jatuh_tempo' => $jatuh_tempo,
                'status_bayar' => 'belum_bayar'
            ];

            if ($this->tagihanModel->insert($data)) {
                $success++;
            } else {
                $failed++;
            }

        } elseif ($tipe == 'kelas') {
            $id_kelas = $this->request->getPost('id_kelas');
            $siswaList = $this->siswaModel->where('id_kelas', $id_kelas)->findAll();

            foreach ($siswaList as $siswa) {
                $data = [
                    'id_siswa' => $siswa['id'],
                    'id_jenis_pembayaran' => $id_jenis_pembayaran,
                    'jumlah_tagihan' => $jenis['nominal'],
                    'tanggal_jatuh_tempo' => $jatuh_tempo,
                    'status_bayar' => 'belum_bayar'
                ];

                if ($this->tagihanModel->insert($data)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
        } else {
            // Semua siswa
            $siswaList = $this->siswaModel->findAll();

            foreach ($siswaList as $siswa) {
                $data = [
                    'id_siswa' => $siswa['id'],
                    'id_jenis_pembayaran' => $id_jenis_pembayaran,
                    'jumlah_tagihan' => $jenis['nominal'],
                    'tanggal_jatuh_tempo' => $jatuh_tempo,
                    'status_bayar' => 'belum_bayar'
                ];

                if ($this->tagihanModel->insert($data)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
        }

        if ($success > 0) {
            return redirect()->to(base_url('jenis-pembayaran-lain/tagihan'))->with('success', "Berhasil membuat $success tagihan");
        }

        return redirect()->back()->with('error', 'Gagal membuat tagihan');
    }

    public function deleteTagihan($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        if ($this->tagihanModel->delete($id)) {
            return redirect()->to(base_url('jenis-pembayaran-lain/tagihan'))->with('success', 'Tagihan berhasil dihapus');
        }

        return redirect()->to(base_url('jenis-pembayaran-lain/tagihan'))->with('error', 'Gagal menghapus tagihan');
    }
}