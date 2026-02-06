<?= $this->extend('layout/siswa') ?>

<?= $this->section('title') ?>Dashboard Siswa<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<!-- Alert Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- Info Siswa Card -->
<div class="card shadow mb-4 border-left-success">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success">Informasi Siswa</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>NIS</strong></td>
                        <td>: <?= session()->get('nis') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>: <?= session()->get('nama') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Kelas</strong></td>
                        <td>: <?= session()->get('nama_kelas') ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <strong>Nominal SPP per bulan:</strong><br>
                    <h4 class="mb-0">Rp <?= number_format($spp['nominal'] ?? 0, 0, ',', '.') ?></h4>
                    <small>Tahun Ajaran: <?= $spp['tahun'] ?? '-' ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Total Dibayar Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Dibayar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp <?= number_format($total_dibayar, 0, ',', '.') ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pembayaran Terverifikasi Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Terverifikasi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_verified ?> Bulan</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menunggu Verifikasi Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Menunggu Verifikasi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_pending ?> Bulan</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Riwayat Pembayaran Terbaru -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-success">Riwayat Pembayaran Terbaru</h6>
        <a href="<?= base_url('siswa/pembayaran') ?>" class="btn btn-success btn-sm">
            <i class="fas fa-list"></i> Lihat Semua
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($pembayaran)): ?>
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                <p class="text-gray-600">Belum ada riwayat pembayaran</p>
                <a href="<?= base_url('siswa/upload-bukti') ?>" class="btn btn-success">
                    <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Bulan</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($pembayaran, 0, 5) as $p): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($p['tanggal_bayar'])) ?></td>
                            <td><?= $p['bulan_dibayar'] ?> <?= $p['tahun_dibayar'] ?></td>
                            <td>Rp <?= number_format($p['jumlah_bayar'], 0, ',', '.') ?></td>
                            <td>
                                <?php if ($p['status_verifikasi'] == 'verified'): ?>
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Terverifikasi</span>
                                <?php elseif ($p['status_verifikasi'] == 'pending'): ?>
                                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>
                                <?php else: ?>
                                    <span class="badge badge-danger"><i class="fas fa-times"></i> Ditolak</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4 border-left-info">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="<?= base_url('siswa/upload-bukti') ?>" class="btn btn-success btn-block">
                            <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="<?= base_url('siswa/pembayaran') ?>" class="btn btn-info btn-block">
                            <i class="fas fa-history"></i> Riwayat Pembayaran
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="<?= base_url('siswa/profil') ?>" class="btn btn-primary btn-block">
                            <i class="fas fa-user"></i> Lihat Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Auto-dismiss alerts after 3 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const closeButton = alert.querySelector('.close');
        if (closeButton) {
            closeButton.click();
        }
    });
}, 3000);
</script>
<?= $this->endSection() ?>