<?php

namespace App\Controllers;

use App\Models\PengajuanTunggakanModel;
use App\Models\TagihanPembayaranLainModel;
use App\Models\TagihanSppModel;

class TunggakanTransaksi extends BaseController
{
    protected $tunggakanModel;
    protected $tagihanModel;
    protected $sppModel;

    public function __construct()
    {
        $this->tunggakanModel = new PengajuanTunggakanModel();
        $this->tagihanModel = new TagihanPembayaranLainModel();
        $this->sppModel = new TagihanSppModel(); // ← TAMBAH INI
    }

    private function checkAuth()
    {
        if (!session()->get('isLogin') || session()->get('role') != 'admin') {
            return redirect()->to(base_url('login'));
        }
        return null;
    }

    /**
     * Index - List Semua Pengajuan Tunggakan
     */
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $filter = $this->request->getGet('status');
        $statistik = $this->tunggakanModel->getStatistik();

        $data = [
            'title' => 'Transaksi Tunggakan Pembayaran',
            'tunggakan' => $this->tunggakanModel->getTunggakanWithDetails($filter),
            'statistik' => $statistik,
            'filter' => $filter
        ];

        return view('tunggakan_transaksi/index', $data);
    }

    /**
     * Detail - Lihat Detail Pengajuan Tunggakan
     */
    public function detail($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $builder = $this->tunggakanModel->db->table('pengajuan_tunggakan');
        
        $tunggakan = $builder->select('
                pengajuan_tunggakan.*,
                siswa.nama as nama_siswa,
                siswa.nis,
                siswa.no_telp as telp_siswa,
                kelas.nama_kelas,
                CASE 
                    WHEN pengajuan_tunggakan.id_tagihan_spp IS NOT NULL 
                    THEN CONCAT("SPP ", tagihan_spp.bulan, " ", tagihan_spp.tahun)
                    ELSE jenis_pembayaran.nama_pembayaran
                END as nama_pembayaran,
                CASE 
                    WHEN pengajuan_tunggakan.id_tagihan_spp IS NOT NULL 
                    THEN "SPP"
                    ELSE jenis_pembayaran.kategori
                END as kategori,
                CASE 
                    WHEN pengajuan_tunggakan.id_tagihan_spp IS NOT NULL 
                    THEN tagihan_spp.jumlah_tagihan
                    ELSE tagihan_pembayaran_lain.jumlah_tagihan
                END as jumlah_tagihan,
                CASE 
                    WHEN pengajuan_tunggakan.id_tagihan_spp IS NOT NULL 
                    THEN tagihan_spp.status_bayar
                    ELSE tagihan_pembayaran_lain.status_bayar
                END as status_bayar,
                users.username as nama_admin
            ', false)
            ->join('siswa', 'siswa.id = pengajuan_tunggakan.id_siswa')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('tagihan_spp', 'tagihan_spp.id = pengajuan_tunggakan.id_tagihan_spp', 'left')
            ->join('tagihan_pembayaran_lain', 'tagihan_pembayaran_lain.id = pengajuan_tunggakan.id_tagihan_pembayaran_lain', 'left')
            ->join('jenis_pembayaran', 'jenis_pembayaran.id = tagihan_pembayaran_lain.id_jenis_pembayaran', 'left')
            ->join('users', 'users.id = pengajuan_tunggakan.diproses_oleh', 'left')
            ->where('pengajuan_tunggakan.id', $id)
            ->get()
            ->getRowArray();

        if (!$tunggakan) {
            return redirect()->to(base_url('tunggakan-transaksi'))
                ->with('error', 'Data tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Pengajuan Tunggakan',
            'tunggakan' => $tunggakan
        ];

        return view('tunggakan_transaksi/detail', $data);
    }

    /**
     * Approve - Setujui Pengajuan Tunggakan
     */
    public function approve($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $tunggakan = $this->tunggakanModel->find($id);
        
        if (!$tunggakan) {
            return redirect()->to(base_url('tunggakan-transaksi'))
                ->with('error', 'Data tidak ditemukan');
        }

        if ($tunggakan['status'] != 'pending') {
            return redirect()->to(base_url('tunggakan-transaksi'))
                ->with('error', 'Pengajuan sudah diproses sebelumnya');
        }

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'tanggal_jatuh_tempo_baru' => 'required|valid_date',
            'catatan_admin' => 'permit_empty|max_length[500]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $tanggalBaru = $this->request->getPost('tanggal_jatuh_tempo_baru');
        $catatanAdmin = $this->request->getPost('catatan_admin');

        // Update pengajuan tunggakan
        $updateTunggakan = $this->tunggakanModel->update($id, [
            'status' => 'approved',
            'tanggal_jatuh_tempo_baru' => $tanggalBaru,
            'catatan_admin' => $catatanAdmin,
            'diproses_oleh' => session()->get('user_id'),
            'tanggal_diproses' => date('Y-m-d H:i:s')
        ]);

        if ($updateTunggakan) {
            // Update tanggal jatuh tempo di tagihan yang sesuai
            if (!empty($tunggakan['id_tagihan_spp'])) {
                // Update SPP
                $this->sppModel->update($tunggakan['id_tagihan_spp'], [
                    'tanggal_jatuh_tempo' => $tanggalBaru
                ]);
            } elseif (!empty($tunggakan['id_tagihan_pembayaran_lain'])) {
                // Update Pembayaran Lain
                $this->tagihanModel->update($tunggakan['id_tagihan_pembayaran_lain'], [
                    'tanggal_jatuh_tempo' => $tanggalBaru
                ]);
            }

            return redirect()->to(base_url('tunggakan-transaksi'))
                ->with('success', 'Pengajuan tunggakan disetujui. Jatuh tempo telah diperbarui.');
        }

        return redirect()->to(base_url('tunggakan-transaksi'))
            ->with('error', 'Gagal menyetujui pengajuan');
    }

    /**
     * Reject - Tolak Pengajuan Tunggakan
     */
    public function reject($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $tunggakan = $this->tunggakanModel->find($id);
        
        if (!$tunggakan) {
            return redirect()->to(base_url('tunggakan-transaksi'))
                ->with('error', 'Data tidak ditemukan');
        }

        if ($tunggakan['status'] != 'pending') {
            return redirect()->to(base_url('tunggakan-transaksi'))
                ->with('error', 'Pengajuan sudah diproses sebelumnya');
        }

        $catatanAdmin = $this->request->getPost('catatan_admin');

        if (empty($catatanAdmin)) {
            return redirect()->back()
                ->with('error', 'Alasan penolakan harus diisi');
        }

        // Update pengajuan tunggakan
        $updateTunggakan = $this->tunggakanModel->update($id, [
            'status' => 'rejected',
            'catatan_admin' => $catatanAdmin,
            'diproses_oleh' => session()->get('user_id'),
            'tanggal_diproses' => date('Y-m-d H:i:s')
        ]);

        if ($updateTunggakan) {
            return redirect()->to(base_url('tunggakan-transaksi'))
                ->with('success', 'Pengajuan tunggakan ditolak.');
        }

        return redirect()->to(base_url('tunggakan-transaksi'))
            ->with('error', 'Gagal menolak pengajuan');
    }

    /**
     * Edit - Update Jatuh Tempo (untuk approved)
     */
    public function editJatuhTempo($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $tunggakan = $this->tunggakanModel->find($id);
        
        if (!$tunggakan) {
            return redirect()->to(base_url('tunggakan-transaksi'))
                ->with('error', 'Data tidak ditemukan');
        }

        if ($tunggakan['status'] != 'approved') {
            return redirect()->to(base_url('tunggakan-transaksi'))
                ->with('error', 'Hanya pengajuan yang sudah disetujui yang bisa diubah jatuh temponya');
        }

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'tanggal_jatuh_tempo_baru' => 'required|valid_date'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->with('error', 'Tanggal jatuh tempo tidak valid');
        }

        $tanggalBaru = $this->request->getPost('tanggal_jatuh_tempo_baru');

        // Update tanggal di pengajuan tunggakan
        $updateTunggakan = $this->tunggakanModel->update($id, [
            'tanggal_jatuh_tempo_baru' => $tanggalBaru
        ]);

        if ($updateTunggakan) {
            // Update tanggal jatuh tempo di tagihan yang sesuai
            if (!empty($tunggakan['id_tagihan_spp'])) {
                // Update SPP
                $this->sppModel->update($tunggakan['id_tagihan_spp'], [
                    'tanggal_jatuh_tempo' => $tanggalBaru
                ]);
            } elseif (!empty($tunggakan['id_tagihan_pembayaran_lain'])) {
                // Update Pembayaran Lain
                $this->tagihanModel->update($tunggakan['id_tagihan_pembayaran_lain'], [
                    'tanggal_jatuh_tempo' => $tanggalBaru
                ]);
            }

            return redirect()->to(base_url('tunggakan-transaksi/detail/' . $id))
                ->with('success', 'Jatuh tempo berhasil diperbarui');
        }

        return redirect()->back()
            ->with('error', 'Gagal memperbarui jatuh tempo');
    }

    /**
     * Delete - Hapus Pengajuan Tunggakan
     */
    public function delete($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $tunggakan = $this->tunggakanModel->find($id);
        
        if (!$tunggakan) {
            return redirect()->to(base_url('tunggakan-transaksi'))
                ->with('error', 'Data tidak ditemukan');
        }

        // Hapus file bukti jika ada
        if (!empty($tunggakan['bukti_pendukung'])) {
            $filePath = ROOTPATH . 'public/uploads/bukti_tunggakan/' . $tunggakan['bukti_pendukung'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        if ($this->tunggakanModel->delete($id)) {
            return redirect()->to(base_url('tunggakan-transaksi'))
                ->with('success', 'Data pengajuan berhasil dihapus');
        }

        return redirect()->to(base_url('tunggakan-transaksi'))
            ->with('error', 'Gagal menghapus data');
    }
}