<?php

namespace App\Controllers;

use App\Models\PengajuanTunggakanModel;
use App\Models\TagihanPembayaranLainModel;
use App\Models\TagihanSppModel;

class PengajuanTunggakan extends BaseController
{
    protected $tunggakanModel;
    protected $tagihanModel;
    protected $sppModel;


    public function __construct()
    {
        $this->sppModel = new TagihanSppModel();
        $this->tunggakanModel = new PengajuanTunggakanModel();
        $this->tagihanModel = new TagihanPembayaranLainModel();
    }

    private function checkAuth()
    {
        if (!session()->get('isLogin') || session()->get('role') != 'siswa') {
            return redirect()->to(base_url('login'));
        }
        return null;
    }

    private function getSiswaId()
    {
        // Coba berbagai kemungkinan nama session
        $id = session()->get('id_siswa') ?? session()->get('siswa_id') ?? session()->get('user_id');
        return $id;
    }

    /**
     * Index - List Pengajuan Tunggakan Siswa
     */
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $id_siswa = $this->getSiswaId();

        $data = [
            'title' => 'Pengajuan Tunggakan Saya',
            'tunggakan' => $this->tunggakanModel->getTunggakanBySiswa($id_siswa)
        ];

        return view('siswa/tunggakan/index', $data);
    }

    /**
     * Form - Form Pengajuan Tunggakan
     */
    public function form()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $id_siswa = $this->getSiswaId();

        // ======================
        // TAGIHAN SPP
        // ======================
        $spp = $this->sppModel->getTagihanBelumBayar($id_siswa);

        $spp = array_map(function ($row) {
            return [
                'id' => $row['id'],
                'jenis' => 'spp',
                'kategori' => 'SPP',
                'nama_pembayaran' =>
                    'SPP ' . $row['bulan'] . ' ' . $row['tahun'],
                'jumlah_tagihan' => $row['jumlah_tagihan'],
                'jatuh_tempo' => $row['tanggal_jatuh_tempo']
            ];
        }, $spp);

        // ======================
        // TAGIHAN PEMBAYARAN LAIN
        // ======================
        $lain = $this->tagihanModel
            ->select('
                tagihan_pembayaran_lain.id,
                jenis_pembayaran.nama_pembayaran,
                tagihan_pembayaran_lain.jumlah_tagihan,
                tagihan_pembayaran_lain.tanggal_jatuh_tempo
            ')
            ->join(
                'jenis_pembayaran',
                'jenis_pembayaran.id = tagihan_pembayaran_lain.id_jenis_pembayaran'
            )
            ->where([
                'tagihan_pembayaran_lain.id_siswa' => $id_siswa,
                'tagihan_pembayaran_lain.status_bayar' => 'belum_bayar'
            ])
            ->findAll();

        $lain = array_map(function ($row) {
            return [
                'id' => $row['id'],
                'jenis' => 'lain',
                'kategori' => 'Lainnya',
                'nama_pembayaran' => $row['nama_pembayaran'],
                'jumlah_tagihan' => $row['jumlah_tagihan'], // ✅ DISAMAKAN
                'jatuh_tempo' => $row['tanggal_jatuh_tempo']
            ];
        }, $lain);

        // ======================
        // GABUNG
        // ======================
        $tagihan = array_merge($spp, $lain);

        return view('siswa/tunggakan/form', [
            'title' => 'Ajukan Tunggakan',
            'tagihan' => $tagihan
        ]);
    }


    /**
     * Submit - Proses Pengajuan Tunggakan
     */
    public function submit()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $id_siswa = $this->getSiswaId();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'tagihan' => 'required',
            'alasan' => 'required|min_length[20]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // ===============================
        // PECAH JENIS TAGIHAN (AMAN)
        // ===============================
        $tagihanInput = $this->request->getPost('tagihan');
        $pecah = explode('_', $tagihanInput);

        if (count($pecah) !== 2) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Format tagihan tidak valid');
        }

        [$jenis, $id_tagihan] = $pecah;

        // ===============================
        // UPLOAD BUKTI
        // ===============================
        $buktiFile = $this->request->getFile('bukti_pendukung');
        $buktiFilename = null;

        if ($buktiFile && $buktiFile->isValid() && !$buktiFile->hasMoved()) {
            $buktiFilename = time() . '_' . $buktiFile->getRandomName();
            $buktiFile->move(ROOTPATH . 'public/uploads/bukti_tunggakan', $buktiFilename);
        }

        // ===============================
        // TAGIHAN SPP
        // ===============================
        if ($jenis === 'spp') {

            $tagihan = $this->sppModel->where([
                'id' => $id_tagihan,
                'id_siswa' => $id_siswa,
                'status_bayar' => 'belum_bayar'
            ])->first();

            if (!$tagihan) {
                return redirect()->back()->with('error', 'Tagihan SPP tidak valid');
            }

            $dataTunggakan = [
                'id_siswa' => $id_siswa,
                'id_tagihan_spp' => $id_tagihan,
                'id_tagihan_pembayaran_lain' => null,
                'tanggal_jatuh_tempo_lama' => $tagihan['tanggal_jatuh_tempo'],
                'alasan' => $this->request->getPost('alasan'),
                'bukti_pendukung' => $buktiFilename,
                'status' => 'pending'
            ];
        }

        // ===============================
        // TAGIHAN PEMBAYARAN LAIN
        // ===============================
        elseif ($jenis === 'lain') {

            $tagihan = $this->tagihanModel->where([
                'id' => $id_tagihan,
                'id_siswa' => $id_siswa,
                'status_bayar' => 'belum_bayar'
            ])->first();

            if (!$tagihan) {
                return redirect()->back()->with('error', 'Tagihan tidak valid');
            }

            $dataTunggakan = [
                'id_siswa' => $id_siswa,
                'id_tagihan_spp' => null,
                'id_tagihan_pembayaran_lain' => $id_tagihan,
                'tanggal_jatuh_tempo_lama' => $tagihan['tanggal_jatuh_tempo'],
                'alasan' => $this->request->getPost('alasan'),
                'bukti_pendukung' => $buktiFilename,
                'status' => 'pending'
            ];
        }

        // ===============================
        // SIMPAN
        // ===============================
        $this->tunggakanModel->insert($dataTunggakan);

        return redirect()->to(base_url('siswa/tunggakan'))
            ->with('success', 'Pengajuan tunggakan berhasil dikirim');
    }

    /**
     * Detail - Lihat Detail Pengajuan
     */
    public function detail($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $id_siswa = $this->getSiswaId();

        $builder = $this->tunggakanModel->db->table('pengajuan_tunggakan');
        
        $tunggakan = $builder->select('
                pengajuan_tunggakan.*,
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
                CASE 
                    WHEN pengajuan_tunggakan.id_tagihan_spp IS NOT NULL 
                    THEN tagihan_spp.tanggal_jatuh_tempo
                    ELSE tagihan_pembayaran_lain.tanggal_jatuh_tempo
                END as tanggal_jatuh_tempo,
                users.username as nama_admin
            ', false)
            ->join('tagihan_spp', 'tagihan_spp.id = pengajuan_tunggakan.id_tagihan_spp', 'left')
            ->join('tagihan_pembayaran_lain', 'tagihan_pembayaran_lain.id = pengajuan_tunggakan.id_tagihan_pembayaran_lain', 'left')
            ->join('jenis_pembayaran', 'jenis_pembayaran.id = tagihan_pembayaran_lain.id_jenis_pembayaran', 'left')
            ->join('users', 'users.id = pengajuan_tunggakan.diproses_oleh', 'left')
            ->where('pengajuan_tunggakan.id', $id)
            ->where('pengajuan_tunggakan.id_siswa', $id_siswa)
            ->get()
            ->getRowArray();

        if (!$tunggakan) {
            return redirect()->to(base_url('siswa/tunggakan'))
                ->with('error', 'Data tidak ditemukan');
        }

        return view('siswa/tunggakan/detail', [
            'title' => 'Detail Pengajuan Tunggakan',
            'tunggakan' => $tunggakan
        ]);
    }

    /**
     * Cancel - Batalkan Pengajuan (hanya untuk pending)
     */
    public function cancel($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        $id_siswa = $this->getSiswaId();

        $tunggakan = $this->tunggakanModel->where([
            'id' => $id,
            'id_siswa' => $id_siswa,
            'status' => 'pending'
        ])->first();

        if (!$tunggakan) {
            return redirect()->to(base_url('siswa/tunggakan'))
                ->with('error', 'Data tidak ditemukan atau tidak dapat dibatalkan');
        }

        // Hapus file bukti jika ada
        if (!empty($tunggakan['bukti_pendukung'])) {
            $filePath = ROOTPATH . 'public/uploads/bukti_tunggakan/' . $tunggakan['bukti_pendukung'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        if ($this->tunggakanModel->delete($id)) {
            return redirect()->to(base_url('siswa/tunggakan'))
                ->with('success', 'Pengajuan tunggakan berhasil dibatalkan');
        }

        return redirect()->back()
            ->with('error', 'Gagal membatalkan pengajuan');
    }
}