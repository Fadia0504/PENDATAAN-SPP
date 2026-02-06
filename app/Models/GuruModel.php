<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruModel extends Model
{
    protected $table = 'guru';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nip',
        'nama',
        'email',
        'no_telepon',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'foto',
        'mata_pelajaran',
        'status',
        'tanggal_masuk',
        'pendidikan_terakhir',
        'gelar'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'nip' => 'required|is_unique[guru.nip,id,{id}]|min_length[10]|max_length[20]',
        'nama' => 'required|min_length[3]|max_length[100]',
        'email' => 'permit_empty|valid_email|is_unique[guru.email,id,{id}]',
        'no_telepon' => 'permit_empty|numeric|min_length[10]|max_length[15]',
        'jenis_kelamin' => 'permit_empty|in_list[L,P]',
        'status' => 'permit_empty|in_list[aktif,nonaktif]'
    ];

    protected $validationMessages = [
        'nip' => [
            'required' => 'NIP harus diisi',
            'is_unique' => 'NIP sudah terdaftar',
            'min_length' => 'NIP minimal 10 karakter',
            'max_length' => 'NIP maksimal 20 karakter'
        ],
        'nama' => [
            'required' => 'Nama harus diisi',
            'min_length' => 'Nama minimal 3 karakter',
            'max_length' => 'Nama maksimal 100 karakter'
        ],
        'email' => [
            'valid_email' => 'Format email tidak valid',
            'is_unique' => 'Email sudah terdaftar'
        ],
        'no_telepon' => [
            'numeric' => 'No telepon harus berupa angka',
            'min_length' => 'No telepon minimal 10 digit',
            'max_length' => 'No telepon maksimal 15 digit'
        ]
    ];

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
    protected $beforeDelete = ['deleteFoto'];
    protected $afterDelete = [];

    /**
     * Get all guru with status aktif
     */
    public function getGuruAktif()
    {
        return $this->where('status', 'aktif')
            ->orderBy('nama', 'ASC')
            ->findAll();
    }

    /**
     * Get guru by NIP
     */
    public function getGuruByNip($nip)
    {
        return $this->where('nip', $nip)->first();
    }

    /**
     * Search guru
     */
    public function searchGuru($keyword)
    {
        return $this->like('nama', $keyword)
            ->orLike('nip', $keyword)
            ->orLike('mata_pelajaran', $keyword)
            ->findAll();
    }

    /**
     * Get statistik guru
     */
    public function getStatistik()
    {
        return [
            'total' => $this->countAll(),
            'aktif' => $this->where('status', 'aktif')->countAllResults(),
            'nonaktif' => $this->where('status', 'nonaktif')->countAllResults(),
            'laki_laki' => $this->where('jenis_kelamin', 'L')->countAllResults(),
            'perempuan' => $this->where('jenis_kelamin', 'P')->countAllResults()
        ];
    }

    /**
     * Get guru by mata pelajaran
     */
    public function getGuruByMapel($mata_pelajaran)
    {
        return $this->where('mata_pelajaran', $mata_pelajaran)
            ->where('status', 'aktif')
            ->findAll();
    }

    /**
     * Delete foto callback
     */
    protected function deleteFoto(array $data)
    {
        if (isset($data['id'])) {
            $guru = $this->find($data['id']);
            if ($guru && !empty($guru['foto'])) {
                $file_path = ROOTPATH . 'public/uploads/foto_guru/' . $guru['foto'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
        }
        return $data;
    }

    /**
     * Update foto guru
     */
    public function updateFoto($id, $filename)
    {
        // Hapus foto lama
        $guru = $this->find($id);
        if ($guru && !empty($guru['foto'])) {
            $old_file = ROOTPATH . 'public/uploads/foto_guru/' . $guru['foto'];
            if (file_exists($old_file)) {
                unlink($old_file);
            }
        }

        // Update dengan foto baru
        return $this->update($id, ['foto' => $filename]);
    }

    /**
     * Get guru dengan pagination
     */
    public function getGuruPaginated($perPage = 10, $page = 1, $search = null)
    {
        $builder = $this->builder();
        
        if ($search) {
            $builder->like('nama', $search)
                   ->orLike('nip', $search)
                   ->orLike('mata_pelajaran', $search);
        }
        
        return $builder->orderBy('nama', 'ASC')
                      ->paginate($perPage, 'default', $page);
    }
}