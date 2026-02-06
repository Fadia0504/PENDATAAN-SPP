<?php

namespace App\Controllers;

use App\Models\PembayaranModel;
use App\Models\KelasModel;
use App\Models\SppModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanPembayaranSpp extends BaseController
{
    protected $pembayaranModel;
    protected $kelasModel;
    protected $sppModel;

    public function __construct()
    {
        $this->pembayaranModel = new PembayaranModel();
        $this->kelasModel = new KelasModel();
        $this->sppModel = new SppModel();
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
            'status_verifikasi' => $this->request->getGet('status_verifikasi'),
            'tanggal_mulai' => $this->request->getGet('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getGet('tanggal_selesai'),
            'id_kelas' => $this->request->getGet('id_kelas'),
            'bulan' => $this->request->getGet('bulan'),
            'tahun' => $this->request->getGet('tahun')
        ];

        // Ambil data dengan filter
        $pembayaran = $this->getFilteredData($filters);

        $data = [
            'title' => 'Laporan Pembayaran SPP',
            'pembayaran' => $pembayaran,
            'kelas_list' => $this->kelasModel->findAll(),
            'filters' => $filters,
            'statistik' => $this->getStatistikLaporan($pembayaran)
        ];

        return view('laporan/spp', $data);
    }

    /**
     * Get Data dengan Filter
     */
    private function getFilteredData($filters)
    {
        $builder = $this->pembayaranModel->db->table('pembayaran');
        
        $builder->select('
                pembayaran.*,
                siswa.nama as nama_siswa,
                siswa.nis,
                kelas.nama_kelas,
                spp.nominal,
                spp.tahun as tahun_ajaran
            ', false)
            ->join('siswa', 'siswa.id = pembayaran.id_siswa')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('spp', 'spp.id = pembayaran.id_spp');

        // Filter Status Verifikasi
        if (!empty($filters['status_verifikasi'])) {
            $builder->where('pembayaran.status_verifikasi', $filters['status_verifikasi']);
        }

        // Filter Tanggal Bayar
        if (!empty($filters['tanggal_mulai'])) {
            $builder->where('DATE(pembayaran.tanggal_bayar) >=', $filters['tanggal_mulai']);
        }
        if (!empty($filters['tanggal_selesai'])) {
            $builder->where('DATE(pembayaran.tanggal_bayar) <=', $filters['tanggal_selesai']);
        }

        // Filter Kelas
        if (!empty($filters['id_kelas'])) {
            $builder->where('siswa.id_kelas', $filters['id_kelas']);
        }

        // Filter Bulan
        if (!empty($filters['bulan'])) {
            $builder->where('pembayaran.bulan_dibayar', $filters['bulan']);
        }

        // Filter Tahun
        if (!empty($filters['tahun'])) {
            $builder->where('pembayaran.tahun_dibayar', $filters['tahun']);
        }

        $builder->orderBy('pembayaran.tanggal_bayar', 'DESC');

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
            'verified' => 0,
            'rejected' => 0,
            'total_nominal' => 0,
            'nominal_verified' => 0
        ];

        foreach ($data as $row) {
            $stats[$row['status_verifikasi']]++;
            $stats['total_nominal'] += $row['jumlah_bayar'];
            
            if ($row['status_verifikasi'] == 'verified') {
                $stats['nominal_verified'] += $row['jumlah_bayar'];
            }
        }

        return $stats;
    }

    /**
     * Export Excel
     */
    public function exportExcel()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        // Ambil filter
        $filters = [
            'status_verifikasi' => $this->request->getGet('status_verifikasi'),
            'tanggal_mulai' => $this->request->getGet('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getGet('tanggal_selesai'),
            'id_kelas' => $this->request->getGet('id_kelas'),
            'bulan' => $this->request->getGet('bulan'),
            'tahun' => $this->request->getGet('tahun')
        ];

        $data = $this->getFilteredData($filters);
        $stats = $this->getStatistikLaporan($data);

        // Buat Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header Info
        $sheet->setCellValue('A1', 'LAPORAN PEMBAYARAN SPP');
        $sheet->mergeCells('A1:K1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Periode: ' . ($filters['tanggal_mulai'] ?? 'Semua') . ' s/d ' . ($filters['tanggal_selesai'] ?? 'Semua'));
        $sheet->mergeCells('A2:K2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Statistik
        $row = 4;
        $sheet->setCellValue('A' . $row, 'Total Pembayaran:');
        $sheet->setCellValue('B' . $row, $stats['total']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Pending:');
        $sheet->setCellValue('B' . $row, $stats['pending']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Terverifikasi:');
        $sheet->setCellValue('B' . $row, $stats['verified']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Ditolak:');
        $sheet->setCellValue('B' . $row, $stats['rejected']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Total Nominal:');
        $sheet->setCellValue('B' . $row, 'Rp ' . number_format($stats['total_nominal'], 0, ',', '.'));
        $row++;
        $sheet->setCellValue('A' . $row, 'Total Terverifikasi:');
        $sheet->setCellValue('B' . $row, 'Rp ' . number_format($stats['nominal_verified'], 0, ',', '.'));
        $row += 2;

        // Header Tabel
        $headers = ['No', 'Tanggal Bayar', 'NIS', 'Nama Siswa', 'Kelas', 'Bulan', 'Tahun', 'Nominal SPP', 'Jumlah Bayar', 'Status', 'Keterangan'];
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
            $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($item['tanggal_bayar'])));
            $sheet->setCellValue('C' . $row, $item['nis']);
            $sheet->setCellValue('D' . $row, $item['nama_siswa']);
            $sheet->setCellValue('E' . $row, $item['nama_kelas'] ?? '-');
            
            // Format bulan
            $bulan_indo = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            $sheet->setCellValue('F' . $row, $bulan_indo[$item['bulan_dibayar']] ?? $item['bulan_dibayar']);
            $sheet->setCellValue('G' . $row, $item['tahun_dibayar']);
            $sheet->setCellValue('H' . $row, 'Rp ' . number_format($item['nominal'], 0, ',', '.'));
            $sheet->setCellValue('I' . $row, 'Rp ' . number_format($item['jumlah_bayar'], 0, ',', '.'));
            
            $status_text = $item['status_verifikasi'] == 'pending' ? 'Pending' : 
                          ($item['status_verifikasi'] == 'verified' ? 'Terverifikasi' : 'Ditolak');
            $sheet->setCellValue('J' . $row, $status_text);
            $sheet->setCellValue('K' . $row, $item['keterangan'] ?? '-');
            
            $row++;
        }

        // Auto Size Columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border untuk tabel
        $sheet->getStyle('A' . ($row - count($data) - 1) . ':K' . ($row - 1))
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Download
        $filename = 'Laporan_SPP_' . date('YmdHis') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Export CSV
     */
    public function exportCsv()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== null) return $authCheck;

        // Ambil filter
        $filters = [
            'status_verifikasi' => $this->request->getGet('status_verifikasi'),
            'tanggal_mulai' => $this->request->getGet('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getGet('tanggal_selesai'),
            'id_kelas' => $this->request->getGet('id_kelas'),
            'bulan' => $this->request->getGet('bulan'),
            'tahun' => $this->request->getGet('tahun')
        ];

        $data = $this->getFilteredData($filters);
        $stats = $this->getStatistikLaporan($data);

        // Set header untuk CSV
        $filename = 'Laporan_SPP_' . date('YmdHis') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Output stream
        $output = fopen('php://output', 'w');
        
        // BOM untuk Excel agar UTF-8 terbaca dengan benar
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header Info
        fputcsv($output, ['LAPORAN PEMBAYARAN SPP'], ';');
        fputcsv($output, ['Periode: ' . ($filters['tanggal_mulai'] ?? 'Semua') . ' s/d ' . ($filters['tanggal_selesai'] ?? 'Semua')], ';');
        fputcsv($output, [], ';');
        
        // Statistik
        fputcsv($output, ['Total Pembayaran', $stats['total']], ';');
        fputcsv($output, ['Pending', $stats['pending']], ';');
        fputcsv($output, ['Terverifikasi', $stats['verified']], ';');
        fputcsv($output, ['Ditolak', $stats['rejected']], ';');
        fputcsv($output, ['Total Nominal', 'Rp ' . number_format($stats['total_nominal'], 0, ',', '.')], ';');
        fputcsv($output, ['Total Terverifikasi', 'Rp ' . number_format($stats['nominal_verified'], 0, ',', '.')], ';');
        fputcsv($output, [], ';');

        // Header Tabel
        fputcsv($output, [
            'No', 'Tanggal Bayar', 'NIS', 'Nama Siswa', 'Kelas', 
            'Bulan', 'Tahun', 'Nominal SPP', 'Jumlah Bayar', 'Status', 'Keterangan'
        ], ';');

        // Data
        $no = 1;
        $bulan_indo = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        
        foreach ($data as $item) {
            fputcsv($output, [
                $no++,
                date('d/m/Y', strtotime($item['tanggal_bayar'])),
                $item['nis'],
                $item['nama_siswa'],
                $item['nama_kelas'] ?? '-',
                $bulan_indo[$item['bulan_dibayar']] ?? $item['bulan_dibayar'],
                $item['tahun_dibayar'],
                'Rp ' . number_format($item['nominal'], 0, ',', '.'),
                'Rp ' . number_format($item['jumlah_bayar'], 0, ',', '.'),
                $item['status_verifikasi'] == 'pending' ? 'Pending' : 
                    ($item['status_verifikasi'] == 'verified' ? 'Terverifikasi' : 'Ditolak'),
                $item['keterangan'] ?? '-'
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
            'status_verifikasi' => $this->request->getGet('status_verifikasi'),
            'tanggal_mulai' => $this->request->getGet('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getGet('tanggal_selesai'),
            'id_kelas' => $this->request->getGet('id_kelas'),
            'bulan' => $this->request->getGet('bulan'),
            'tahun' => $this->request->getGet('tahun')
        ];

        $data = $this->getFilteredData($filters);
        $stats = $this->getStatistikLaporan($data);

        $html = view('laporan/spp_pdf', [
            'data' => $data,
            'filters' => $filters,
            'stats' => $stats
        ]);

        // Gunakan Dompdf
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        $filename = 'Laporan_SPP_' . date('YmdHis') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
    }
}