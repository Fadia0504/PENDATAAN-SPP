<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisPembayaranModel extends Model
{
    protected $table = 'jenis_pembayaran';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_pembayaran', 'deskripsi', 'nominal', 'kategori', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}