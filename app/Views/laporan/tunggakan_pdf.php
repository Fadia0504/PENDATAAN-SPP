<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Tunggakan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        .subtitle {
            text-align: center;
            margin-bottom: 20px;
            font-size: 10px;
        }
        .info-box {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 3px 5px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table.data th {
            background-color: #4CAF50;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        table.data td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        table.data tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-pending {
            background: #FFC107;
            color: #000;
        }
        .badge-approved {
            background: #4CAF50;
            color: #fff;
        }
        .badge-rejected {
            background: #f44336;
            color: #fff;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <h2>LAPORAN PENGAJUAN TUNGGAKAN PEMBAYARAN</h2>
    <div class="subtitle">
        Periode: <?= !empty($filters['tanggal_mulai']) ? date('d/m/Y', strtotime($filters['tanggal_mulai'])) : 'Semua' ?> 
        s/d 
        <?= !empty($filters['tanggal_selesai']) ? date('d/m/Y', strtotime($filters['tanggal_selesai'])) : 'Semua' ?>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td width="25%"><strong>Total Pengajuan:</strong></td>
                <td width="25%"><?= $stats['total'] ?></td>
                <td width="25%"><strong>Total Nominal:</strong></td>
                <td width="25%">Rp <?= number_format($stats['total_nominal'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td><strong>Pending:</strong></td>
                <td><?= $stats['pending'] ?></td>
                <td><strong>Nominal Disetujui:</strong></td>
                <td>Rp <?= number_format($stats['nominal_approved'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td><strong>Disetujui:</strong></td>
                <td><?= $stats['approved'] ?></td>
                <td><strong>Ditolak:</strong></td>
                <td><?= $stats['rejected'] ?></td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Tanggal</th>
                <th width="8%">NIS</th>
                <th width="15%">Nama Siswa</th>
                <th width="8%">Kelas</th>
                <th width="8%">Jenis</th>
                <th width="18%">Pembayaran</th>
                <th width="12%">Nominal</th>
                <th width="8%">Status</th>
                <th width="7%">Proses</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($data as $item): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= date('d/m/Y', strtotime($item['created_at'])) ?></td>
                        <td><?= $item['nis'] ?></td>
                        <td><?= $item['nama_siswa'] ?></td>
                        <td><?= $item['nama_kelas'] ?? '-' ?></td>
                        <td><?= $item['jenis_tagihan'] ?></td>
                        <td><?= $item['nama_pembayaran'] ?></td>
                        <td class="text-right">Rp <?= number_format($item['jumlah_tagihan'], 0, ',', '.') ?></td>
                        <td class="text-center">
                            <?php if ($item['status'] == 'pending'): ?>
                                <span class="badge badge-pending">Pending</span>
                            <?php elseif ($item['status'] == 'approved'): ?>
                                <span class="badge badge-approved">Setuju</span>
                            <?php else: ?>
                                <span class="badge badge-rejected">Tolak</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $item['diproses_oleh_nama'] ?? '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
        <p>Oleh: <?= session()->get('username') ?></p>
    </div>
</body>
</html>