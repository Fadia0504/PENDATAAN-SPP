<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_siswa', 'id_spp', 'bulan_dibayar', 'tahun_dibayar', 'tanggal_bayar', 'jumlah_bayar', 'bukti_pembayaran', 'status_verifikasi', 'keterangan'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get pembayaran dengan join siswa dan spp
    public function getPembayaranWithDetails()
    {
        return $this->select('pembayaran.*, siswa.nis, siswa.nama, siswa.id_kelas, kelas.nama_kelas, spp.nominal, spp.tahun')
                    ->join('siswa', 'siswa.id = pembayaran.id_siswa')
                    ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
                    ->join('spp', 'spp.id = pembayaran.id_spp')
                    ->orderBy('pembayaran.created_at', 'DESC')
                    ->findAll();
    }

    // Get pembayaran by siswa
    public function getPembayaranBySiswa($id_siswa)
    {
        return $this->select('pembayaran.*, spp.nominal, spp.tahun')
                    ->join('spp', 'spp.id = pembayaran.id_spp')
                    ->where('pembayaran.id_siswa', $id_siswa)
                    ->orderBy('pembayaran.created_at', 'DESC')
                    ->findAll();
    }
}