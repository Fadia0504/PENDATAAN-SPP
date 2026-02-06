<?php

namespace App\Models;

use CodeIgniter\Model;

class TagihanSppModel extends Model
{
    protected $table = 'tagihan_spp';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_siswa', 'id_spp', 'bulan', 'tahun', 'jumlah_tagihan', 'status_bayar', 'tanggal_jatuh_tempo'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Get tagihan dengan join siswa dan spp
    public function getTagihanWithDetails()
    {
        return $this->select('tagihan_spp.*, siswa.nis, siswa.nama, siswa.id_kelas, kelas.nama_kelas, spp.tahun as tahun_ajaran, spp.nominal')
                    ->join('siswa', 'siswa.id = tagihan_spp.id_siswa')
                    ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
                    ->join('spp', 'spp.id = tagihan_spp.id_spp')
                    ->orderBy('tagihan_spp.tahun', 'DESC')
                    ->orderBy("FIELD(tagihan_spp.bulan, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
                    ->findAll();
    }

    // Get tagihan by siswa
    public function getTagihanBySiswa($id_siswa)
    {
        return $this->select('tagihan_spp.*, spp.tahun as tahun_ajaran, spp.nominal')
                    ->join('spp', 'spp.id = tagihan_spp.id_spp')
                    ->where('tagihan_spp.id_siswa', $id_siswa)
                    ->orderBy('tagihan_spp.tahun', 'DESC')
                    ->orderBy("FIELD(tagihan_spp.bulan, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
                    ->findAll();
    }

    // Get tagihan belum bayar by siswa
    public function getTagihanBelumBayar($id_siswa)
    {
        return $this->select('tagihan_spp.*, spp.tahun as tahun_ajaran, spp.nominal')
                    ->join('spp', 'spp.id = tagihan_spp.id_spp')
                    ->where('tagihan_spp.id_siswa', $id_siswa)
                    ->where('tagihan_spp.status_bayar', 'belum_bayar')
                    ->findAll();
    }

    // Hitung total tagihan by siswa
    public function getTotalTagihanBySiswa($id_siswa)
    {
        return $this->selectSum('jumlah_tagihan')
                    ->where('id_siswa', $id_siswa)
                    ->first();
    }

    // Hitung jumlah tagihan berdasarkan status
    public function countByStatus($id_siswa, $status)
    {
        return $this->where('id_siswa', $id_siswa)
                    ->where('status_bayar', $status)
                    ->countAllResults();
    }
}