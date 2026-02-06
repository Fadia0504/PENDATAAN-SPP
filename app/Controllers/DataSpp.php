<?php

namespace App\Controllers;

use App\Models\SppModel;
use App\Models\TagihanSppModel;
use App\Models\SiswaModel;
use App\Models\KelasModel;

class DataSpp extends BaseController
{
    protected $sppModel;
    protected $tagihanModel;
    protected $siswaModel;
    protected $kelasModel;

    public function __construct()
    {
        $this->sppModel = new SppModel();
        $this->tagihanModel = new TagihanSppModel();
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

    // Menampilkan data SPP + Tagihan
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $data = [
            'title' => 'Data SPP',
            'spp' => $this->sppModel->orderBy('id', 'DESC')->findAll(),
            'tagihan' => $this->tagihanModel->getTagihanWithDetails()
        ];

        return view('data_spp/index', $data);
    }

    // Form tambah SPP baru
    public function create()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $data = [
            'title' => 'Tambah Data SPP'
        ];

        return view('data_spp/create', $data);
    }

    // Proses tambah SPP
    public function store()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $validation = \Config\Services::validation();
        $validation->setRules([
            'tahun' => 'required',
            'nominal' => 'required|numeric'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'tahun' => $this->request->getPost('tahun'),
            'nominal' => $this->request->getPost('nominal')
        ];

        if ($this->sppModel->insert($data)) {
            return redirect()->to(base_url('data-spp'))->with('success', 'Data SPP berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan data SPP');
    }

    // Form edit SPP
    public function edit($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $spp = $this->sppModel->find($id);

        if (!$spp) {
            return redirect()->to(base_url('data-spp'))->with('error', 'Data SPP tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Data SPP',
            'spp' => $spp
        ];

        return view('data_spp/edit', $data);
    }

    // Proses update SPP
    public function update($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $validation = \Config\Services::validation();
        $validation->setRules([
            'tahun' => 'required',
            'nominal' => 'required|numeric'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'tahun' => $this->request->getPost('tahun'),
            'nominal' => $this->request->getPost('nominal')
        ];

        if ($this->sppModel->update($id, $data)) {
            return redirect()->to(base_url('data-spp'))->with('success', 'Data SPP berhasil diupdate');
        }

        return redirect()->back()->with('error', 'Gagal mengupdate data SPP');
    }

    // Hapus SPP
    public function delete($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        if ($this->sppModel->delete($id)) {
            return redirect()->to(base_url('data-spp'))->with('success', 'Data SPP berhasil dihapus');
        }

        return redirect()->to(base_url('data-spp'))->with('error', 'Gagal menghapus data SPP');
    }

    // ========== KIRIM TAGIHAN ==========

    // Form kirim tagihan
    public function kirimTagihan()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $data = [
            'title' => 'Kirim Tagihan SPP',
            'siswa' => $this->siswaModel->getSiswaWithKelas(),
            'kelas' => $this->kelasModel->findAll(),
            'spp' => $this->sppModel->orderBy('id', 'DESC')->first()
        ];

        return view('data_spp/kirim_tagihan', $data);
    }

    // Proses kirim tagihan
    public function prosesKirimTagihan()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $tipe = $this->request->getPost('tipe_tagihan');
        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');
        $jatuh_tempo = $this->request->getPost('tanggal_jatuh_tempo');
        $id_spp = $this->request->getPost('id_spp');

        // Get data SPP
        $spp = $this->sppModel->find($id_spp);
        if (!$spp) {
            return redirect()->back()->with('error', 'Data SPP tidak ditemukan');
        }

        $jumlah = $spp['nominal'];

        if (empty($bulan) || empty($tahun) || empty($jatuh_tempo)) {
            return redirect()->back()->with('error', 'Semua field harus diisi');
        }

        $success = 0;
        $failed = 0;

        if ($tipe == 'individu') {
            // Tagihan ke 1 siswa
            $id_siswa = $this->request->getPost('id_siswa');
            
            $existing = $this->tagihanModel->where([
                'id_siswa' => $id_siswa,
                'bulan' => $bulan,
                'tahun' => $tahun
            ])->first();

            if ($existing) {
                return redirect()->back()->with('error', 'Tagihan untuk bulan ini sudah pernah dikirim');
            }
            
            $data = [
                'id_siswa' => $id_siswa,
                'id_spp' => $id_spp,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jumlah_tagihan' => $jumlah,
                'tanggal_jatuh_tempo' => $jatuh_tempo,
                'status_bayar' => 'belum_bayar'
            ];

            if ($this->tagihanModel->insert($data)) {
                $success++;
            } else {
                $failed++;
            }

        } elseif ($tipe == 'kelas') {
            // Tagihan ke 1 kelas
            $id_kelas = $this->request->getPost('id_kelas');
            $siswaList = $this->siswaModel->where('id_kelas', $id_kelas)->findAll();

            if (empty($siswaList)) {
                return redirect()->back()->with('error', 'Tidak ada siswa di kelas ini');
            }

            foreach ($siswaList as $siswa) {
                $existing = $this->tagihanModel->where([
                    'id_siswa' => $siswa['id'],
                    'bulan' => $bulan,
                    'tahun' => $tahun
                ])->first();

                if ($existing) {
                    $failed++;
                    continue;
                }

                $data = [
                    'id_siswa' => $siswa['id'],
                    'id_spp' => $id_spp,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'jumlah_tagihan' => $jumlah,
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
            // Tagihan ke SEMUA siswa
            $siswaList = $this->siswaModel->findAll();

            foreach ($siswaList as $siswa) {
                $existing = $this->tagihanModel->where([
                    'id_siswa' => $siswa['id'],
                    'bulan' => $bulan,
                    'tahun' => $tahun
                ])->first();

                if ($existing) {
                    $failed++;
                    continue;
                }

                $data = [
                    'id_siswa' => $siswa['id'],
                    'id_spp' => $id_spp,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'jumlah_tagihan' => $jumlah,
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
            $message = "Berhasil mengirim tagihan ke $success siswa";
            if ($failed > 0) {
                $message .= " ($failed siswa sudah punya tagihan bulan ini)";
            }
            return redirect()->to(base_url('data-spp'))->with('success', $message);
        }

        return redirect()->back()->with('error', 'Gagal mengirim tagihan');
    }

    // Hapus tagihan
    public function deleteTagihan($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $tagihan = $this->tagihanModel->find($id);
        if ($tagihan && $tagihan['status_bayar'] == 'lunas') {
            return redirect()->to(base_url('data-spp'))->with('error', 'Tagihan yang sudah lunas tidak bisa dihapus');
        }

        if ($this->tagihanModel->delete($id)) {
            return redirect()->to(base_url('data-spp'))->with('success', 'Tagihan berhasil dihapus');
        }

        return redirect()->to(base_url('data-spp'))->with('error', 'Gagal menghapus tagihan');
    }
}