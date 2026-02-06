<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password', 'role', 'id_siswa'];
    
    // MATIKAN timestamps
    protected $useTimestamps = false; // Ubah dari true ke false
    
    // Get user dengan data siswa (untuk role siswa)
    public function getUserWithSiswa($username)
    {
        return $this->select('users.*, siswa.nis, siswa.nama, siswa.alamat, siswa.no_telp, siswa.id_kelas, kelas.nama_kelas')
                    ->join('siswa', 'siswa.id = users.id_siswa', 'left')
                    ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
                    ->where('users.username', $username)
                    ->first();
    }
}