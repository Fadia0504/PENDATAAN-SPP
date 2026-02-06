<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranLainModel extends Model
{
    protected $table = 'pembayaran_lain';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_tagihan_pembayaran_lain',
        'id_siswa',
        'id_jenis_pembayaran',
        'tanggal_bayar',
        'jumlah_bayar',
        'bukti_pembayaran',
        'status_verifikasi',
        'keterangan',
        'verified_by',
        'verified_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get pembayaran with details (siswa, kelas, jenis pembayaran)
     */
    public function getPembayaranWithDetails()
    {
        return $this->select('
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
            ->orderBy('pembayaran_lain.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get pembayaran by siswa
     */
    public function getPembayaranBySiswa($id_siswa)
    {
        return $this->select('
                pembayaran_lain.*,
                jenis_pembayaran.nama_pembayaran,
                jenis_pembayaran.kategori,
                tagihan_pembayaran_lain.jumlah_tagihan
            ')
            ->join('tagihan_pembayaran_lain', 'tagihan_pembayaran_lain.id = pembayaran_lain.id_tagihan_pembayaran_lain')
            ->join('jenis_pembayaran', 'jenis_pembayaran.id = pembayaran_lain.id_jenis_pembayaran')
            ->where('pembayaran_lain.id_siswa', $id_siswa)
            ->orderBy('pembayaran_lain.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get pembayaran pending (belum diverifikasi)
     */
    public function getPembayaranPending()
    {
        return $this->select('
                pembayaran_lain.*,
                siswa.nama as nama_siswa,
                siswa.nisn,
                kelas.nama_kelas,
                jenis_pembayaran_lain.nama_pembayaran
            ')
            ->join('tagihan_pembayaran_lain', 'tagihan_pembayaran_lain.id = pembayaran_lain.id_tagihan_pembayaran_lain')
            ->join('siswa', 'siswa.id = pembayaran_lain.id_siswa')
            ->join('kelas', 'kelas.id = siswa.id_kelas')
            ->join('jenis_pembayaran_lain', 'jenis_pembayaran_lain.id = tagihan_pembayaran_lain.id_jenis_pembayaran')
            ->where('pembayaran_lain.status_verifikasi', 'pending')
            ->orderBy('pembayaran_lain.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get pembayaran verified
     */
    public function getPembayaranVerified()
    {
        return $this->where('status_verifikasi', 'verified')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get pembayaran rejected
     */
    public function getPembayaranRejected()
    {
        return $this->where('status_verifikasi', 'rejected')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get total pembayaran by siswa
     */
    public function getTotalPembayaranBySiswa($id_siswa)
    {
        $result = $this->selectSum('jumlah_bayar', 'total')
            ->where('id_siswa', $id_siswa)
            ->where('status_verifikasi', 'verified')
            ->first();
        
        return $result['total'] ?? 0;
    }

    /**
     * Get statistik pembayaran
     */
    public function getStatistik($tahun = null)
    {
        if ($tahun === null) {
            $tahun = date('Y');
        }

        return [
            'total_pembayaran' => $this->where('YEAR(tanggal_bayar)', $tahun)
                ->where('status_verifikasi', 'verified')
                ->countAllResults(),
            'total_nominal' => $this->selectSum('jumlah_bayar', 'total')
                ->where('YEAR(tanggal_bayar)', $tahun)
                ->where('status_verifikasi', 'verified')
                ->first()['total'] ?? 0,
            'pending' => $this->where('YEAR(tanggal_bayar)', $tahun)
                ->where('status_verifikasi', 'pending')
                ->countAllResults(),
            'verified' => $this->where('YEAR(tanggal_bayar)', $tahun)
                ->where('status_verifikasi', 'verified')
                ->countAllResults(),
            'rejected' => $this->where('YEAR(tanggal_bayar)', $tahun)
                ->where('status_verifikasi', 'rejected')
                ->countAllResults()
        ];
    }
}