<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanTunggakanModel extends Model
{
    protected $table = 'pengajuan_tunggakan';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_siswa',
        'id_tagihan_spp',
        'id_tagihan_pembayaran_lain',
        'tanggal_jatuh_tempo_lama',
        'tanggal_jatuh_tempo_baru',
        'alasan',
        'bukti_pendukung',
        'status',
        'catatan_admin',
        'diproses_oleh',
        'tanggal_diproses'
    ];


    protected $validationRules = [
        'id_siswa' => 'required|integer',
        'tanggal_jatuh_tempo_lama' => 'required|valid_date',
        'alasan' => 'required|min_length[20]'
    ];


    protected $validationMessages = [
        'alasan' => [
            'required' => 'Alasan pengajuan tunggakan harus diisi',
            'min_length' => 'Alasan minimal 20 karakter'
        ]
    ];

    /**
     * Get pengajuan tunggakan dengan detail siswa dan tagihan
     */
    /**
 * Get pengajuan tunggakan dengan detail siswa dan tagihan
 */
    public function getTunggakanWithDetails($status = null)
    {
        $builder = $this->db->table('pengajuan_tunggakan');
        
        $query = $builder->select('
                pengajuan_tunggakan.*,
                siswa.nama as nama_siswa,
                siswa.nis,
                kelas.nama_kelas,
                CASE 
                    WHEN pengajuan_tunggakan.id_tagihan_spp IS NOT NULL 
                    THEN CONCAT("SPP ", tagihan_spp.bulan, " ", tagihan_spp.tahun)
                    ELSE jenis_pembayaran.nama_pembayaran
                END as nama_pembayaran,
                CASE 
                    WHEN pengajuan_tunggakan.id_tagihan_spp IS NOT NULL 
                    THEN "SPP"
                    ELSE jenis_pembayaran.kategori
                END as jenis_pembayaran,
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
                END as tanggal_jatuh_tempo_asli,
                users.username as nama_admin
            ', false)
            ->join('siswa', 'siswa.id = pengajuan_tunggakan.id_siswa')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('tagihan_spp', 'tagihan_spp.id = pengajuan_tunggakan.id_tagihan_spp', 'left')
            ->join('tagihan_pembayaran_lain', 'tagihan_pembayaran_lain.id = pengajuan_tunggakan.id_tagihan_pembayaran_lain', 'left')
            ->join('jenis_pembayaran', 'jenis_pembayaran.id = tagihan_pembayaran_lain.id_jenis_pembayaran', 'left')
            ->join('users', 'users.id = pengajuan_tunggakan.diproses_oleh', 'left')
            ->orderBy('pengajuan_tunggakan.created_at', 'DESC');

        if ($status !== null) {
            $query->where('pengajuan_tunggakan.status', $status);
        }

        return $query->get()->getResultArray();
    }

    /**
     * Get pengajuan tunggakan berdasarkan siswa
     */
    public function getTunggakanBySiswa($id_siswa)
    {
        $builder = $this->db->table('pengajuan_tunggakan');
        
        $result = $builder->select('
                pengajuan_tunggakan.id,
                pengajuan_tunggakan.id_siswa,
                pengajuan_tunggakan.id_tagihan_spp,
                pengajuan_tunggakan.id_tagihan_pembayaran_lain,
                pengajuan_tunggakan.tanggal_jatuh_tempo_lama,
                pengajuan_tunggakan.alasan,
                pengajuan_tunggakan.bukti_pendukung,
                pengajuan_tunggakan.status,
                pengajuan_tunggakan.catatan_admin,
                pengajuan_tunggakan.created_at,
                CASE 
                    WHEN pengajuan_tunggakan.id_tagihan_spp IS NOT NULL 
                    THEN CONCAT("SPP ", tagihan_spp.bulan, " ", tagihan_spp.tahun)
                    ELSE jenis_pembayaran.nama_pembayaran
                END as nama_pembayaran,
                CASE 
                    WHEN pengajuan_tunggakan.id_tagihan_spp IS NOT NULL 
                    THEN tagihan_spp.jumlah_tagihan
                    ELSE tagihan_pembayaran_lain.jumlah_tagihan
                END as jumlah_tagihan
            ', false)
            ->join('tagihan_spp', 'tagihan_spp.id = pengajuan_tunggakan.id_tagihan_spp', 'left')
            ->join('tagihan_pembayaran_lain', 'tagihan_pembayaran_lain.id = pengajuan_tunggakan.id_tagihan_pembayaran_lain', 'left')
            ->join('jenis_pembayaran', 'jenis_pembayaran.id = tagihan_pembayaran_lain.id_jenis_pembayaran', 'left')
            ->where('pengajuan_tunggakan.id_siswa', $id_siswa)
            ->orderBy('pengajuan_tunggakan.created_at', 'DESC')
            ->get();
        
        return $result->getResultArray();
    }

    /**
     * Cek apakah tagihan sudah pernah diajukan tunggakan
     */
    public function isTagihanSudahDiajukan($id_tagihan, $id_siswa)
    {
        return $this->where([
            'id_tagihan_pembayaran_lain' => $id_tagihan,
            'id_siswa' => $id_siswa,
            'status' => 'pending'
        ])->first() !== null;
    }

    /**
     * Get statistik tunggakan
     */
    public function getStatistik()
    {
        return [
            'pending' => $this->where('status', 'pending')->countAllResults(),
            'approved' => $this->where('status', 'approved')->countAllResults(),
            'rejected' => $this->where('status', 'rejected')->countAllResults(),
            'total' => $this->countAll()
        ];
    }
}