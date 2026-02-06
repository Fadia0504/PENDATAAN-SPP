<?php

namespace App\Models;

use CodeIgniter\Model;

class TagihanPembayaranLainModel extends Model
{
    protected $table = 'tagihan_pembayaran_lain';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_siswa', 'id_jenis_pembayaran', 'jumlah_tagihan', 'status_bayar', 'tanggal_jatuh_tempo'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get tagihan dengan join siswa dan jenis pembayaran
    public function getTagihanWithDetails()
    {
        return $this->select('tagihan_pembayaran_lain.*, siswa.nis, siswa.nama, siswa.id_kelas, kelas.nama_kelas, jenis_pembayaran.nama_pembayaran, jenis_pembayaran.kategori')
                    ->join('siswa', 'siswa.id = tagihan_pembayaran_lain.id_siswa')
                    ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
                    ->join('jenis_pembayaran', 'jenis_pembayaran.id = tagihan_pembayaran_lain.id_jenis_pembayaran')
                    ->orderBy('tagihan_pembayaran_lain.created_at', 'DESC')
                    ->findAll();
    }

    // Get tagihan by siswa
    public function getTagihanBySiswa($id_siswa)
    {
        return $this->select('tagihan_pembayaran_lain.*, jenis_pembayaran.nama_pembayaran, jenis_pembayaran.deskripsi, jenis_pembayaran.kategori')
                    ->join('jenis_pembayaran', 'jenis_pembayaran.id = tagihan_pembayaran_lain.id_jenis_pembayaran')
                    ->where('tagihan_pembayaran_lain.id_siswa', $id_siswa)
                    ->orderBy('tagihan_pembayaran_lain.created_at', 'DESC')
                    ->findAll();
    }

    // Get tagihan belum bayar by siswa
    public function getTagihanBelumBayar($id_siswa)
    {
        return $this->select('tagihan_pembayaran_lain.*, jenis_pembayaran.nama_pembayaran')
                    ->join('jenis_pembayaran', 'jenis_pembayaran.id = tagihan_pembayaran_lain.id_jenis_pembayaran')
                    ->where('tagihan_pembayaran_lain.id_siswa', $id_siswa)
                    ->where('tagihan_pembayaran_lain.status_bayar', 'belum_bayar')
                    ->findAll();
    }
}