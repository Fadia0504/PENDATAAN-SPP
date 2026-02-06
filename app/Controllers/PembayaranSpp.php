<?php

namespace App\Controllers;

use App\Models\PembayaranModel;
use App\Models\TagihanSppModel;

class PembayaranSpp extends BaseController
{
    protected $pembayaranModel;
    protected $tagihanModel;

    public function __construct()
    {
        $this->pembayaranModel = new PembayaranModel();
        $this->tagihanModel = new TagihanSppModel();
    }

    private function checkAuth()
    {
        if (!session()->get('isLogin') || session()->get('role') != 'admin') {
            return redirect()->to(base_url('login'));
        }
        return null;
    }

    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $data = [
            'title' => 'Verifikasi Pembayaran SPP',
            'pembayaran' => $this->pembayaranModel->getPembayaranWithDetails()
        ];

        return view('pembayaran_spp/index', $data);
    }

    public function verifikasi($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $pembayaran = $this->pembayaranModel->find($id);

        if (!$pembayaran) {
            return redirect()->to(base_url('pembayaran-spp'))
                ->with('error', 'Data tidak ditemukan');
        }

        // 1. update pembayaran
        $this->pembayaranModel->update($id, [
            'status_verifikasi' => 'verified'
        ]);

        // 2. update TAGIHAN (INI YANG BIKIN STATUS SISWA BERUBAH)
        $this->tagihanModel
            ->where('id_siswa', $pembayaran['id_siswa'])
            ->where('bulan', $pembayaran['bulan_dibayar'])
            ->where('tahun', $pembayaran['tahun_dibayar'])
            ->set(['status_bayar' => 'lunas'])
            ->update();

        return redirect()->to(base_url('pembayaran-spp'))
            ->with('success', 'Pembayaran berhasil diverifikasi');
    }


    public function tolak($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $pembayaran = $this->pembayaranModel->find($id);

        if (!$pembayaran) {
            return redirect()->to(base_url('pembayaran-spp'))
                ->with('error', 'Data tidak ditemukan');
        }

        // 1. update pembayaran
        $this->pembayaranModel->update($id, [
            'status_verifikasi' => 'rejected',
            'keterangan' => $this->request->getPost('keterangan') ?? 'Bukti pembayaran tidak valid'
        ]);

        // 2. KEMBALIKAN STATUS TAGIHAN
        $this->tagihanModel
            ->where('id_siswa', $pembayaran['id_siswa'])
            ->where('bulan', $pembayaran['bulan_dibayar'])
            ->where('tahun', $pembayaran['tahun_dibayar'])
            ->set(['status_bayar' => 'belum_bayar'])
            ->update();

        return redirect()->to(base_url('pembayaran-spp'))
            ->with('success', 'Pembayaran ditolak');
    }


    public function delete($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        if ($this->pembayaranModel->delete($id)) {
            return redirect()->to(base_url('pembayaran-spp'))->with('success', 'Data berhasil dihapus');
        }

        return redirect()->to(base_url('pembayaran-spp'))->with('error', 'Gagal menghapus data');
    }
}