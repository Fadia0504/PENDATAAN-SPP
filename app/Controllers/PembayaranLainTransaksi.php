<?php

namespace App\Controllers;

use App\Models\PembayaranLainModel;
use App\Models\TagihanPembayaranLainModel;

class PembayaranLainTransaksi extends BaseController
{
    protected $pembayaranModel;
    protected $tagihanModel;

    public function __construct()
    {
        $this->pembayaranModel = new PembayaranLainModel();
        $this->tagihanModel = new TagihanPembayaranLainModel();
    }

    private function checkAuth()
    {
        if (!session()->get('isLogin') || session()->get('role') != 'admin') {
            return redirect()->to(base_url('login'));
        }
        return null;
    }

    /**
     * Index - List Pembayaran untuk Verifikasi
     */
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $data = [
            'title' => 'Verifikasi Pembayaran Lain',
            'pembayaran' => $this->pembayaranModel->getPembayaranWithDetails()
        ];

        return view('pembayaran_lain_transaksi/index', $data);
    }

    /**
     * Verifikasi - Approve Pembayaran
     */
    public function verifikasi($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $pembayaran = $this->pembayaranModel->find($id);
        
        if (!$pembayaran) {
            return redirect()->to(base_url('pembayaran-lain-transaksi'))
                ->with('error', 'Data pembayaran tidak ditemukan');
        }

        if ($pembayaran['status_verifikasi'] != 'pending') {
            return redirect()->to(base_url('pembayaran-lain-transaksi'))
                ->with('error', 'Pembayaran sudah diverifikasi sebelumnya');
        }

        // Update status pembayaran
        $updatePembayaran = $this->pembayaranModel->update($id, [
            'status_verifikasi' => 'verified',
            'verified_by' => session()->get('user_id'),
            'verified_at' => date('Y-m-d H:i:s')
        ]);

        if ($updatePembayaran) {
            // Update status tagihan
            $this->tagihanModel->update($pembayaran['id_tagihan_pembayaran_lain'], [
                'status_bayar' => 'lunas'
            ]);

            return redirect()->to(base_url('pembayaran-lain-transaksi'))
                ->with('success', 'Pembayaran berhasil diverifikasi');
        }

        return redirect()->to(base_url('pembayaran-lain-transaksi'))
            ->with('error', 'Gagal memverifikasi pembayaran');
    }

    /**
     * Tolak - Reject Pembayaran
     */
    public function tolak($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $pembayaran = $this->pembayaranModel->find($id);
        
        if (!$pembayaran) {
            return redirect()->to(base_url('pembayaran-lain-transaksi'))
                ->with('error', 'Data pembayaran tidak ditemukan');
        }

        if ($pembayaran['status_verifikasi'] != 'pending') {
            return redirect()->to(base_url('pembayaran-lain-transaksi'))
                ->with('error', 'Pembayaran sudah diproses sebelumnya');
        }

        $keterangan = $this->request->getPost('keterangan');

        if (empty($keterangan)) {
            return redirect()->back()->with('error', 'Alasan penolakan harus diisi');
        }

        // Update status pembayaran
        $updatePembayaran = $this->pembayaranModel->update($id, [
            'status_verifikasi' => 'rejected',
            'keterangan' => $keterangan,
            'verified_by' => session()->get('user_id'),
            'verified_at' => date('Y-m-d H:i:s')
        ]);

        if ($updatePembayaran) {
            // Update status tagihan kembali ke belum bayar
            $this->tagihanModel->update($pembayaran['id_tagihan_pembayaran_lain'], [
                'status_bayar' => 'belum_bayar'
            ]);

            return redirect()->to(base_url('pembayaran-lain-transaksi'))
                ->with('success', 'Pembayaran ditolak. Siswa dapat mengupload ulang bukti pembayaran.');
        }

        return redirect()->to(base_url('pembayaran-lain-transaksi'))
            ->with('error', 'Gagal menolak pembayaran');
    }

    /**
     * Delete - Hapus Data Pembayaran
     */
    public function delete($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $pembayaran = $this->pembayaranModel->find($id);
        
        if (!$pembayaran) {
            return redirect()->to(base_url('pembayaran-lain-transaksi'))
                ->with('error', 'Data pembayaran tidak ditemukan');
        }

        // Delete bukti pembayaran file if exists
        if (!empty($pembayaran['bukti_pembayaran'])) {
            $filePath = ROOTPATH . 'public/uploads/bukti_pembayaran/' . $pembayaran['bukti_pembayaran'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Update tagihan status jika pembayaran dihapus
        $this->tagihanModel->update($pembayaran['id_tagihan_pembayaran_lain'], [
            'status_bayar' => 'belum_bayar'
        ]);

        if ($this->pembayaranModel->delete($id)) {
            return redirect()->to(base_url('pembayaran-lain-transaksi'))
                ->with('success', 'Data pembayaran berhasil dihapus');
        }

        return redirect()->to(base_url('pembayaran-lain-transaksi'))
            ->with('error', 'Gagal menghapus data pembayaran');
    }

    /**
     * Detail - Lihat Detail Pembayaran
     */
    public function detail($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $pembayaran = $this->pembayaranModel
            ->select('
                pembayaran_lain.*,
                siswa.nama as nama_siswa,
                siswa.nis,
                kelas.nama_kelas,
                jenis_pembayaran.nama_pembayaran,
                jenis_pembayaran.kategori,
                tagihan_pembayaran_lain.jumlah_tagihan
            ')
            ->join('tagihan_pembayaran_lain', 'tagihan_pembayaran_lain.id = pembayaran_lain.id_tagihan_pembayaran_lain')
            ->join('siswa', 'siswa.id = pembayaran_lain.id_siswa')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('jenis_pembayaran', 'jenis_pembayaran.id = pembayaran_lain.id_jenis_pembayaran')
            ->find($id);

        if (!$pembayaran) {
            return redirect()->to(base_url('pembayaran-lain-transaksi'))
                ->with('error', 'Data tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Pembayaran',
            'pembayaran' => $pembayaran
        ];

        return view('pembayaran_lain_transaksi/detail', $data);
    }
}