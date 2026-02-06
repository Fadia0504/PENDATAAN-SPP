<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nis', 'nama', 'alamat', 'no_telp', 'id_kelas'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get siswa dengan join kelas
    public function getSiswaWithKelas()
    {
        return $this->select('siswa.*, kelas.nama_kelas, kelas.kompetensi_keahlian')
                    ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
                    ->findAll();
    }

    // Get siswa by ID dengan join kelas
    public function getSiswaById($id)
    {
        return $this->select('siswa.*, kelas.nama_kelas, kelas.kompetensi_keahlian')
                    ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
                    ->where('siswa.id', $id)
                    ->first();
    }
}