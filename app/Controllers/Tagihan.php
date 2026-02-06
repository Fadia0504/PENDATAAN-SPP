<?php

namespace App\Controllers;

use App\Models\TagihanSppModel;
use App\Models\SiswaModel;
use App\Models\SppModel;
use App\Models\KelasModel;

class Tagihan extends BaseController
{
    protected $tagihanModel;
    protected $siswaModel;
    protected $sppModel;
    protected $kelasModel;

    public function __construct()
    {
        $this->tagihanModel = new TagihanSppModel();
        $this->siswaModel = new SiswaModel();
        $this->sppModel = new SppModel();
        $this->kelasModel = new KelasModel();
    }

    private function checkAuth()
    {
        if (!session()->get('isLogin') || session()->get('role') != 'admin') {
            return redirect()->to(base_url('login'));
        }
        return null;
    }

    // Menampilkan daftar tagihan SPP
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $data = [
            'title' => 'Tagihan SPP',
            'tagihan' => $this->tagihanModel->getTagihanWithDetails()
        ];

        return view('tagihan/index', $data);
    }

    // Form buat tagihan SPP
    public function create()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $data = [
            'title' => 'Buat Tagihan SPP',
            'siswa' => $this->siswaModel->getSiswaWithKelas(),
            'kelas' => $this->kelasModel->findAll(),
            'spp' => $this->sppModel->orderBy('id', 'DESC')->first()
        ];

        return view('tagihan/create', $data);
    }

    // Proses buat tagihan SPP
    public function store()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $tipe = $this->request->getPost('tipe_tagihan'); // 'individu' atau 'kelas'
        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');
        $jatuh_tempo = $this->request->getPost('tanggal_jatuh_tempo');
        $id_spp = $this->request->getPost('id_spp');
        $jumlah = $this->request->getPost('jumlah_tagihan');

        // Validasi
        if (empty($bulan) || empty($tahun) || empty($jatuh_tempo) || empty($jumlah)) {
            return redirect()->back()->with('error', 'Semua field harus diisi');
        }

        $success = 0;
        $failed = 0;

        if ($tipe == 'individu') {
            // Tagihan per siswa
            $id_siswa = $this->request->getPost('id_siswa');
            
            // Cek apakah tagihan sudah ada
            $existing = $this->tagihanModel->where([
                'id_siswa' => $id_siswa,
                'bulan' => $bulan,
                'tahun' => $tahun
            ])->first();

            if ($existing) {
                return redirect()->back()->with('error', 'Tagihan untuk bulan ini sudah ada');
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

        } else {
            // Tagihan per kelas
            $id_kelas = $this->request->getPost('id_kelas');
            $siswaList = $this->siswaModel->where('id_kelas', $id_kelas)->findAll();

            foreach ($siswaList as $siswa) {
                // Cek apakah tagihan sudah ada
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
            $message = "Berhasil membuat $success tagihan";
            if ($failed > 0) {
                $message .= ", $failed gagal (mungkin sudah ada)";
            }
            return redirect()->to(base_url('tagihan'))->with('success', $message);
        }

        return redirect()->back()->with('error', 'Gagal membuat tagihan');
    }

    // Hapus tagihan
    public function delete($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        // Cek apakah tagihan sudah dibayar
        $tagihan = $this->tagihanModel->find($id);
        if ($tagihan && $tagihan['status_bayar'] == 'lunas') {
            return redirect()->to(base_url('tagihan'))->with('error', 'Tagihan yang sudah lunas tidak bisa dihapus');
        }

        if ($this->tagihanModel->delete($id)) {
            return redirect()->to(base_url('tagihan'))->with('success', 'Tagihan berhasil dihapus');
        }

        return redirect()->to(base_url('tagihan'))->with('error', 'Gagal menghapus tagihan');
    }
}