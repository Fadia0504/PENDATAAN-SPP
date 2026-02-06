<?php

namespace App\Controllers;

use App\Models\PembayaranModel;
use App\Models\SppModel;
use App\Models\TagihanSppModel;
use App\Models\TagihanPembayaranLainModel;
use App\Models\PembayaranLainModel;

class DashboardSiswa extends BaseController
{
    protected $pembayaranModel;
    protected $sppModel;
    protected $tagihanModel;
    protected $tagihanLainModel;
    protected $pembayaranLainModel;

    public function __construct()
    {
        $this->pembayaranModel = new PembayaranModel();
        $this->sppModel = new SppModel();
        $this->tagihanModel = new TagihanSppModel();
        $this->tagihanLainModel = new TagihanPembayaranLainModel();
        $this->pembayaranLainModel = new PembayaranLainModel();
    }

    private function checkAuth()
    {
        if (!session()->get('isLogin') || session()->get('role') != 'siswa') {
            return redirect()->to(base_url('login'));
        }
        return null;
    }

    // Dashboard Siswa
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $id_siswa = session()->get('id_siswa');
        
        // Get tagihan SPP siswa
        $tagihan_spp = $this->tagihanModel->getTagihanBySiswa($id_siswa);
        
        // Get tagihan pembayaran lain
        $tagihan_lain = $this->tagihanLainModel->getTagihanBySiswa($id_siswa);
        
        // Get SPP aktif
        $spp = $this->sppModel->orderBy('id', 'DESC')->first();
        
        // Get riwayat pembayaran SPP
        $pembayaran = $this->pembayaranModel->getPembayaranBySiswa($id_siswa);
        
        // Hitung statistik untuk dashboard
        $total_dibayar = 0;
        $total_verified = 0;
        $total_pending = 0;
        
        foreach ($pembayaran as $p) {
            if ($p['status_verifikasi'] == 'verified') {
                $total_dibayar += $p['jumlah_bayar'];
                $total_verified++;
            } elseif ($p['status_verifikasi'] == 'pending') {
                $total_pending++;
            }
        }
        
        // Hitung statistik SPP
        $total_tagihan_spp = 0;
        $total_belum_bayar_spp = 0;
        $total_menunggu_spp = 0;
        $total_lunas_spp = 0;
        
        foreach ($tagihan_spp as $t) {
            $total_tagihan_spp += $t['jumlah_tagihan'];
            if ($t['status_bayar'] == 'belum_bayar') {
                $total_belum_bayar_spp++;
            } elseif ($t['status_bayar'] == 'menunggu_verifikasi') {
                $total_menunggu_spp++;
            } elseif ($t['status_bayar'] == 'lunas') {
                $total_lunas_spp++;
            }
        }

        // Hitung statistik Pembayaran Lain
        $total_tagihan_lain = 0;
        $total_belum_bayar_lain = 0;
        $total_menunggu_lain = 0;
        $total_lunas_lain = 0;
        
        foreach ($tagihan_lain as $t) {
            $total_tagihan_lain += $t['jumlah_tagihan'];
            if ($t['status_bayar'] == 'belum_bayar') {
                $total_belum_bayar_lain++;
            } elseif ($t['status_bayar'] == 'menunggu_verifikasi') {
                $total_menunggu_lain++;
            } elseif ($t['status_bayar'] == 'lunas') {
                $total_lunas_lain++;
            }
        }

        $data = [
            'title' => 'Dashboard Siswa',
            'tagihan_spp' => $tagihan_spp,
            'tagihan_lain' => $tagihan_lain,
            'spp' => $spp ?? [],
            'pembayaran' => $pembayaran,
            'total_dibayar' => $total_dibayar,
            'total_verified' => $total_verified,
            'total_pending' => $total_pending,
            'total_tagihan_spp' => $total_tagihan_spp,
            'total_belum_bayar_spp' => $total_belum_bayar_spp,
            'total_menunggu_spp' => $total_menunggu_spp,
            'total_lunas_spp' => $total_lunas_spp,
            'total_tagihan_lain' => $total_tagihan_lain,
            'total_belum_bayar_lain' => $total_belum_bayar_lain,
            'total_menunggu_lain' => $total_menunggu_lain,
            'total_lunas_lain' => $total_lunas_lain,
        ];

        return view('siswa_dashboard/index', $data);
    }

    // Tagihan SPP
    public function tagihanSpp()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $id_siswa = session()->get('id_siswa');
        $tagihan = $this->tagihanModel->getTagihanBySiswa($id_siswa);
        
        // Hitung statistik
        $total_belum_bayar = $this->tagihanModel->countByStatus($id_siswa, 'belum_bayar');
        $total_menunggu = $this->tagihanModel->countByStatus($id_siswa, 'menunggu_verifikasi');
        $total_lunas = $this->tagihanModel->countByStatus($id_siswa, 'lunas');
        
        $data = [
            'title' => 'Tagihan SPP',
            'tagihan' => $tagihan,
            'total_belum_bayar' => $total_belum_bayar,
            'total_menunggu' => $total_menunggu,
            'total_lunas' => $total_lunas
        ];

        return view('siswa_dashboard/tagihan_spp', $data);
    }

    // Tagihan Pembayaran Lain
    public function tagihanLain()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $id_siswa = session()->get('id_siswa');
        $tagihan = $this->tagihanLainModel->getTagihanBySiswa($id_siswa);
        
        // Hitung statistik
        $total_belum_bayar = 0;
        $total_menunggu = 0;
        $total_lunas = 0;
        
        foreach ($tagihan as $t) {
            if ($t['status_bayar'] == 'belum_bayar') $total_belum_bayar++;
            if ($t['status_bayar'] == 'menunggu_verifikasi') $total_menunggu++;
            if ($t['status_bayar'] == 'lunas') $total_lunas++;
        }
        
        $data = [
            'title' => 'Tagihan Pembayaran Lain',
            'tagihan' => $tagihan,
            'total_belum_bayar' => $total_belum_bayar,
            'total_menunggu' => $total_menunggu,
            'total_lunas' => $total_lunas
        ];

        return view('siswa_dashboard/tagihan_lain', $data);
    }

    // Upload Bukti Bayar
    public function uploadBukti()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $id_siswa = session()->get('id_siswa');
        
        // Get tagihan belum bayar
        $tagihan_spp = $this->tagihanModel->getTagihanBelumBayar($id_siswa);
        $tagihan_lain = $this->tagihanLainModel->getTagihanBelumBayar($id_siswa);
        
        // Cek parameter dari URL
        $id_tagihan = $this->request->getGet('tagihan');
        $type = $this->request->getGet('type');
        $selected_tagihan = null;
        $tipe_tagihan = null;
        
        if ($id_tagihan) {
            // Cek type parameter dulu
            if ($type == 'lain') {
                // Langsung cek di tagihan lain
                $selected_tagihan = $this->tagihanLainModel->find($id_tagihan);
                if ($selected_tagihan) {
                    $tipe_tagihan = 'lain';
                }
            } else {
                // Default ke SPP atau cek otomatis
                $selected_tagihan = $this->tagihanModel->find($id_tagihan);
                if ($selected_tagihan) {
                    $tipe_tagihan = 'spp';
                } else {
                    // Jika tidak ketemu di SPP, cek di tagihan lain
                    $selected_tagihan = $this->tagihanLainModel->find($id_tagihan);
                    if ($selected_tagihan) {
                        $tipe_tagihan = 'lain';
                    }
                }
            }
        }
        
        $data = [
            'title' => 'Upload Bukti Pembayaran',
            'tagihan_spp' => $tagihan_spp,
            'tagihan_lain' => $tagihan_lain,
            'selected_tagihan' => $selected_tagihan,
            'tipe_tagihan' => $tipe_tagihan
        ];

        return view('siswa_dashboard/upload_bukti', $data);
    }

    // Proses Upload Bukti SPP
    public function prosesUploadSpp()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $id_siswa = session()->get('id_siswa');
        
        // Validasi
        $validation = \Config\Services::validation();
        $validation->setRules([
            'id_tagihan' => 'required',
            'tanggal_bayar' => 'required',
            'bukti_pembayaran' => 'uploaded[bukti_pembayaran]|max_size[bukti_pembayaran,2048]|ext_in[bukti_pembayaran,jpg,jpeg,png,pdf]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Get tagihan
        $id_tagihan = $this->request->getPost('id_tagihan');
        $tagihan = $this->tagihanModel->find($id_tagihan);

        if (!$tagihan) {
            return redirect()->back()->with('error', 'Tagihan tidak ditemukan');
        }

        // Upload file
        $file = $this->request->getFile('bukti_pembayaran');
        $fileName = $file->getRandomName();
        $file->move('uploads/bukti_pembayaran', $fileName);

        // Insert pembayaran
        $data_pembayaran = [
            'id_siswa' => $id_siswa,
            'id_tagihan' => $id_tagihan,
            'id_spp' => $tagihan['id_spp'],
            'bulan_dibayar' => $tagihan['bulan'],
            'tahun_dibayar' => $tagihan['tahun'],
            'tanggal_bayar' => $this->request->getPost('tanggal_bayar'),
            'jumlah_bayar' => $tagihan['jumlah_tagihan'],
            'bukti_pembayaran' => $fileName,
            'status_verifikasi' => 'pending',
            'keterangan' => $this->request->getPost('keterangan')
        ];

        if ($this->pembayaranModel->insert($data_pembayaran)) {
            // Update status tagihan
            $this->tagihanModel->update($id_tagihan, ['status_bayar' => 'menunggu_verifikasi']);
            
            return redirect()->to(base_url('siswa/dashboard'))->with('success', 'Bukti pembayaran SPP berhasil diupload. Menunggu verifikasi admin.');
        }

        return redirect()->back()->with('error', 'Gagal upload bukti pembayaran');
    }

    // Proses Upload Bukti Pembayaran Lain
    public function prosesUploadLain()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $id_siswa = session()->get('id_siswa');
        
        // Validasi
        $validation = \Config\Services::validation();
        $validation->setRules([
            'id_tagihan' => 'required',
            'tanggal_bayar' => 'required',
            'bukti_pembayaran' => 'uploaded[bukti_pembayaran]|max_size[bukti_pembayaran,2048]|ext_in[bukti_pembayaran,jpg,jpeg,png,pdf]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Get tagihan
        $id_tagihan = $this->request->getPost('id_tagihan');
        $tagihan = $this->tagihanLainModel->find($id_tagihan);

        if (!$tagihan) {
            return redirect()->back()->with('error', 'Tagihan tidak ditemukan');
        }

        // Upload file
        $file = $this->request->getFile('bukti_pembayaran');
        $fileName = $file->getRandomName();
        $file->move('uploads/bukti_pembayaran', $fileName);

        // Insert pembayaran lain
        $data_pembayaran = [
            'id_siswa' => $id_siswa,
            'id_tagihan_pembayaran_lain' => $id_tagihan,
            'id_jenis_pembayaran' => $tagihan['id_jenis_pembayaran'],
            'tanggal_bayar' => $this->request->getPost('tanggal_bayar'),
            'jumlah_bayar' => $tagihan['jumlah_tagihan'],
            'bukti_pembayaran' => $fileName,
            'status_verifikasi' => 'pending',
            'keterangan' => $this->request->getPost('keterangan')
        ];

        if ($this->pembayaranLainModel->insert($data_pembayaran)) {
            // Update status tagihan
            $this->tagihanLainModel->update($id_tagihan, ['status_bayar' => 'menunggu_verifikasi']);
            
            return redirect()->to(base_url('siswa/dashboard'))->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
        }

        return redirect()->back()->with('error', 'Gagal upload bukti pembayaran');
    }

    // Riwayat Pembayaran
    public function riwayatPembayaran()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $id_siswa = session()->get('id_siswa');
        
        // Get pembayaran SPP
        $pembayaran_spp = $this->pembayaranModel->getPembayaranBySiswa($id_siswa);
        
        // Get pembayaran lain
        $pembayaran_lain = $this->pembayaranLainModel->getPembayaranBySiswa($id_siswa);
        
        $data = [
            'title' => 'Riwayat Pembayaran',
            'pembayaran_spp' => $pembayaran_spp,
            'pembayaran_lain' => $pembayaran_lain
        ];

        return view('siswa_dashboard/riwayat_pembayaran', $data);
    }

    // Profil
    public function profil()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $data = [
            'title' => 'Profil Saya'
        ];

        return view('siswa_dashboard/profil', $data);
    }
}