<?php

namespace App\Models;

use CodeIgniter\Model;

class SppModel extends Model
{
    protected $table = 'spp';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tahun', 'nominal'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}