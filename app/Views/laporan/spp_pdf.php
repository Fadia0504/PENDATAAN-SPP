<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran SPP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        .subtitle {
            text-align: center;
            font-size: 9pt;
            margin-bottom: 20px;
        }
        .stats-box {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .stats-box table {
            width: 100%;
        }
        .stats-box td {
            padding: 3px 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th {
            background-color: #4CAF50;
            color: white;
            padding: 8px;
            font-weight: bold;
            text-align: center;
        }
        td {
            padding: 6px;
            font-size: 9pt;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8pt;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }
        .badge-verified {
            background-color: #28a745;
            color: #fff;
        }
        .badge-rejected {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>
    <h2>LAPORAN PEMBAYARAN SPP</h2>
    <div class="subtitle">
        Periode: <?= $filters['tanggal_mulai'] ? date('d/m/Y', strtotime($filters['tanggal_mulai'])) : 'Semua' ?> 
        s/d 
        <?= $filters['tanggal_selesai'] ? date('d/m/Y', strtotime($filters['tanggal_selesai'])) : 'Semua' ?>
    </div>

    <div class="stats-box">
        <table style="border: none;">
            <tr>
                <td style="border: none; width: 150px;"><strong>Total Pembayaran:</strong></td>
                <td style="border: none;"><?= $stats['total'] ?></td>
                <td style="border: none; width: 150px;"><strong>Total Nominal:</strong></td>
                <td style="border: none;">Rp <?= number_format($stats['total_nominal'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Pending:</strong></td>
                <td style="border: none;"><?= $stats['pending'] ?></td>
                <td style="border: none;"><strong>Terverifikasi:</strong></td>
                <td style="border: none;">Rp <?= number_format($stats['nominal_verified'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Terverifikasi:</strong></td>
                <td style="border: none;"><?= $stats['verified'] ?></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Ditolak:</strong></td>
                <td style="border: none;"><?= $stats['rejected'] ?></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="70">Tanggal</th>
                <th width="60">NIS</th>
                <th>Nama Siswa</th>
                <th width="60">Kelas</th>
                <th width="70">Bulan</th>
                <th width="50">Tahun</th>
                <th width="80">Nominal SPP</th>
                <th width="80">Jumlah Bayar</th>
                <th width="80">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data</td>
                </tr>
            <?php else: ?>
                <?php 
                $no = 1;
                $bulan_indo = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
                foreach ($data as $item): 
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center"><?= date('d/m/Y', strtotime($item['tanggal_bayar'])) ?></td>
                        <td class="text-center"><?= $item['nis'] ?></td>
                        <td><?= $item['nama_siswa'] ?></td>
                        <td class="text-center"><?= $item['nama_kelas'] ?? '-' ?></td>
                        <td><?= $bulan_indo[$item['bulan_dibayar']] ?? $item['bulan_dibayar'] ?></td>
                        <td class="text-center"><?= $item['tahun_dibayar'] ?></td>
                        <td class="text-right">Rp <?= number_format($item['nominal'], 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format($item['jumlah_bayar'], 0, ',', '.') ?></td>
                        <td class="text-center">
                            <?php if ($item['status_verifikasi'] == 'pending'): ?>
                                <span class="badge badge-pending">Pending</span>
                            <?php elseif ($item['status_verifikasi'] == 'verified'): ?>
                                <span class="badge badge-verified">Terverifikasi</span>
                            <?php else: ?>
                                <span class="badge badge-rejected">Ditolak</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right; font-size: 9pt;">
        <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
    </div>
</body>
</html>