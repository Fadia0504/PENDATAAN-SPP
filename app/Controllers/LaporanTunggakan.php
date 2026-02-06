<?php

namespace App\Controllers;

use App\Models\PengajuanTunggakanModel;
use App\Models\KelasModel;
// Jika pakai PHPExcel (alternatif)
// use PHPExcel;
// use PHPExcel_IOFactory;
// use PHPExcel_Style_Alignment;
// use PHPExcel_Style_Fill;
// use PHPExcel_Style_Border;

// Jika pakai PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanTunggakan extends BaseController
{
    protected $tunggakanModel;
    protected $kelasModel;

    public function __construct()
    {
        $this->tunggakanModel = new PengajuanTunggakanModel();
        $this->kelasModel = new KelasModel();
    }

    private function checkAuth()
    {
        if (!session()->get('isLogin') || session()->get('role') != 'admin') {
            return redirect()->to(base_url('login'));
        }
        return null;
    }

    /**
     * Index - Halaman Laporan dengan Filter
     */
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        // Ambil parameter filter
        $filters = [
            'status' => $this->request->getGet('status'),
            'tanggal_mulai' => $this->request->getGet('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getGet('tanggal_selesai'),
            'id_kelas' => $this->request->getGet('id_kelas'),
            'jenis' => $this->request->getGet('jenis') // spp atau lain
        ];

        // Ambil data dengan filter
        $tunggakan = $this->getFilteredData($filters);

        $data = [
            'title' => 'Laporan Tunggakan',
            'tunggakan' => $tunggakan,
            'kelas_list' => $this->kelasModel->findAll(),
            'filters' => $filters,
            'statistik' => $this->getStatistikLaporan($tunggakan)
        ];

        return view('laporan/tunggakan', $data);
    }

    /**
     * Get Data dengan Filter
     */
    private function getFilteredData($filters)
    {
        $builder = $this->tunggakanModel->db->table('pengajuan_tunggakan');
        
        $builder->select('
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
                END as jenis_tagihan,
                CASE 
                    WHEN pengajuan_tunggakan.id_tagihan_spp IS NOT NULL 
                    THEN tagihan_spp.jumlah_tagihan
                    ELSE tagihan_pembayaran_lain.jumlah_tagihan
                END as jumlah_tagihan,
                users.username as diproses_oleh_nama
            ', false)
            ->join('siswa', 'siswa.id = pengajuan_tunggakan.id_siswa')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('tagihan_spp', 'tagihan_spp.id = pengajuan_tunggakan.id_tagihan_spp', 'left')
            ->join('tagihan_pembayaran_lain', 'tagihan_pembayaran_lain.id = pengajuan_tunggakan.id_tagihan_pembayaran_lain', 'left')
            ->join('jenis_pembayaran', 'jenis_pembayaran.id = tagihan_pembayaran_lain.id_jenis_pembayaran', 'left')
            ->join('users', 'users.id = pengajuan_tunggakan.diproses_oleh', 'left');

        // Filter Status
        if (!empty($filters['status'])) {
            $builder->where('pengajuan_tunggakan.status', $filters['status']);
        }

        // Filter Tanggal
        if (!empty($filters['tanggal_mulai'])) {
            $builder->where('DATE(pengajuan_tunggakan.created_at) >=', $filters['tanggal_mulai']);
        }
        if (!empty($filters['tanggal_selesai'])) {
            $builder->where('DATE(pengajuan_tunggakan.created_at) <=', $filters['tanggal_selesai']);
        }

        // Filter Kelas
        if (!empty($filters['id_kelas'])) {
            $builder->where('siswa.id_kelas', $filters['id_kelas']);
        }

        // Filter Jenis (SPP atau Lain)
        if (!empty($filters['jenis'])) {
            if ($filters['jenis'] == 'spp') {
                $builder->where('pengajuan_tunggakan.id_tagihan_spp IS NOT NULL');
            } elseif ($filters['jenis'] == 'lain') {
                $builder->where('pengajuan_tunggakan.id_tagihan_pembayaran_lain IS NOT NULL');
            }
        }

        $builder->orderBy('pengajuan_tunggakan.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Statistik Laporan
     */
    private function getStatistikLaporan($data)
    {
        $stats = [
            'total' => count($data),
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'total_nominal' => 0,
            'nominal_approved' => 0
        ];

        foreach ($data as $row) {
            $stats[$row['status']]++;
            $stats['total_nominal'] += $row['jumlah_tagihan'];
            
            if ($row['status'] == 'approved') {
                $stats['nominal_approved'] += $row['jumlah_tagihan'];
            }
        }

        return $stats;
    }

    /**
     * Export Excel (menggunakan PhpSpreadsheet)
     */
    public function exportExcel()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        // Ambil filter
        $filters = [
            'status' => $this->request->getGet('status'),
            'tanggal_mulai' => $this->request->getGet('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getGet('tanggal_selesai'),
            'id_kelas' => $this->request->getGet('id_kelas'),
            'jenis' => $this->request->getGet('jenis')
        ];

        $data = $this->getFilteredData($filters);
        $stats = $this->getStatistikLaporan($data);

        // Buat Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header Info
        $sheet->setCellValue('A1', 'LAPORAN PENGAJUAN TUNGGAKAN PEMBAYARAN');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Periode: ' . ($filters['tanggal_mulai'] ?? 'Semua') . ' s/d ' . ($filters['tanggal_selesai'] ?? 'Semua'));
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Statistik
        $row = 4;
        $sheet->setCellValue('A' . $row, 'Total Pengajuan:');
        $sheet->setCellValue('B' . $row, $stats['total']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Pending:');
        $sheet->setCellValue('B' . $row, $stats['pending']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Disetujui:');
        $sheet->setCellValue('B' . $row, $stats['approved']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Ditolak:');
        $sheet->setCellValue('B' . $row, $stats['rejected']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Total Nominal:');
        $sheet->setCellValue('B' . $row, 'Rp ' . number_format($stats['total_nominal'], 0, ',', '.'));
        $row += 2;

        // Header Tabel
        $headers = ['No', 'Tanggal', 'NIS', 'Nama Siswa', 'Kelas', 'Jenis', 'Pembayaran', 'Nominal', 'Status', 'Diproses Oleh'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $sheet->getStyle($col . $row)->getFont()->setBold(true);
            $sheet->getStyle($col . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF4CAF50');
            $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $col++;
        }

        // Data
        $row++;
        $no = 1;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, date('d/m/Y H:i', strtotime($item['created_at'])));
            $sheet->setCellValue('C' . $row, $item['nis']);
            $sheet->setCellValue('D' . $row, $item['nama_siswa']);
            $sheet->setCellValue('E' . $row, $item['nama_kelas'] ?? '-');
            $sheet->setCellValue('F' . $row, $item['jenis_tagihan']);
            $sheet->setCellValue('G' . $row, $item['nama_pembayaran']);
            $sheet->setCellValue('H' . $row, 'Rp ' . number_format($item['jumlah_tagihan'], 0, ',', '.'));
            
            $status_text = $item['status'] == 'pending' ? 'Pending' : 
                          ($item['status'] == 'approved' ? 'Disetujui' : 'Ditolak');
            $sheet->setCellValue('I' . $row, $status_text);
            $sheet->setCellValue('J' . $row, $item['diproses_oleh_nama'] ?? '-');
            
            $row++;
        }

        // Auto Size Columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border untuk tabel
        $sheet->getStyle('A' . ($row - count($data) - 1) . ':J' . ($row - 1))
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Download
        $filename = 'Laporan_Tunggakan_' . date('YmdHis') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Export CSV (Alternatif tanpa library - langsung jalan)
     */
    public function exportCsv()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        // Ambil filter
        $filters = [
            'status' => $this->request->getGet('status'),
            'tanggal_mulai' => $this->request->getGet('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getGet('tanggal_selesai'),
            'id_kelas' => $this->request->getGet('id_kelas'),
            'jenis' => $this->request->getGet('jenis')
        ];

        $data = $this->getFilteredData($filters);
        $stats = $this->getStatistikLaporan($data);

        // Set header untuk CSV
        $filename = 'Laporan_Tunggakan_' . date('YmdHis') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Output stream
        $output = fopen('php://output', 'w');
        
        // BOM untuk Excel agar UTF-8 terbaca dengan benar
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header Info
        fputcsv($output, ['LAPORAN PENGAJUAN TUNGGAKAN PEMBAYARAN'], ';');
        fputcsv($output, ['Periode: ' . ($filters['tanggal_mulai'] ?? 'Semua') . ' s/d ' . ($filters['tanggal_selesai'] ?? 'Semua')], ';');
        fputcsv($output, [], ';');
        
        // Statistik
        fputcsv($output, ['Total Pengajuan', $stats['total']], ';');
        fputcsv($output, ['Pending', $stats['pending']], ';');
        fputcsv($output, ['Disetujui', $stats['approved']], ';');
        fputcsv($output, ['Ditolak', $stats['rejected']], ';');
        fputcsv($output, ['Total Nominal', 'Rp ' . number_format($stats['total_nominal'], 0, ',', '.')], ';');
        fputcsv($output, [], ';');

        // Header Tabel
        fputcsv($output, [
            'No', 'Tanggal', 'NIS', 'Nama Siswa', 'Kelas', 
            'Jenis', 'Pembayaran', 'Nominal', 'Status', 'Diproses Oleh'
        ], ';');

        // Data
        $no = 1;
        foreach ($data as $item) {
            fputcsv($output, [
                $no++,
                date('d/m/Y H:i', strtotime($item['created_at'])),
                $item['nis'],
                $item['nama_siswa'],
                $item['nama_kelas'] ?? '-',
                $item['jenis_tagihan'],
                $item['nama_pembayaran'],
                'Rp ' . number_format($item['jumlah_tagihan'], 0, ',', '.'),
                $item['status'] == 'pending' ? 'Pending' : 
                    ($item['status'] == 'approved' ? 'Disetujui' : 'Ditolak'),
                $item['diproses_oleh_nama'] ?? '-'
            ], ';');
        }

        fclose($output);
        exit;
    }

    /**
     * Export PDF
     */
    public function exportPdf()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        // Ambil filter
        $filters = [
            'status' => $this->request->getGet('status'),
            'tanggal_mulai' => $this->request->getGet('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getGet('tanggal_selesai'),
            'id_kelas' => $this->request->getGet('id_kelas'),
            'jenis' => $this->request->getGet('jenis')
        ];

        $data = $this->getFilteredData($filters);
        $stats = $this->getStatistikLaporan($data);

        $html = view('laporan/tunggakan_pdf', [
            'data' => $data,
            'filters' => $filters,
            'stats' => $stats
        ]);

        // Gunakan Dompdf
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        $filename = 'Laporan_Tunggakan_' . date('YmdHis') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
    }
}