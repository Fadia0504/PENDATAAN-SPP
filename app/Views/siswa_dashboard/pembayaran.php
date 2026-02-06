<?= $this->extend('layout/siswa') ?>

<?= $this->section('title') ?>Riwayat Pembayaran<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Riwayat Pembayaran</h1>
    <a href="<?= base_url('siswa/upload-bukti') ?>" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50">
            <i class="fas fa-upload"></i>
        </span>
        <span class="text">Upload Bukti Baru</span>
    </a>
</div>

<!-- DataTales -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success">Tabel Riwayat Pembayaran</h6>
    </div>
    <div class="card-body">
        <?php if (empty($pembayaran)): ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                <p class="text-gray-600 mb-3">Belum ada riwayat pembayaran</p>
                <a href="<?= base_url('siswa/upload-bukti') ?>" class="btn btn-success">
                    <i class="fas fa-upload"></i> Upload Bukti Pembayaran Pertama
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Bayar</th>
                            <th>Bulan</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Bukti</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($pembayaran as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d/m/Y', strtotime($p['tanggal_bayar'])) ?></td>
                            <td><?= $p['bulan_dibayar'] ?> <?= $p['tahun_dibayar'] ?></td>
                            <td>Rp <?= number_format($p['jumlah_bayar'], 0, ',', '.') ?></td>
                            <td>
                                <?php if ($p['status_verifikasi'] == 'verified'): ?>
                                    <span class="badge badge-success badge-lg">
                                        <i class="fas fa-check-circle"></i> Terverifikasi
                                    </span>
                                <?php elseif ($p['status_verifikasi'] == 'pending'): ?>
                                    <span class="badge badge-warning badge-lg">
                                        <i class="fas fa-clock"></i> Menunggu Verifikasi
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-danger badge-lg">
                                        <i class="fas fa-times-circle"></i> Ditolak
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['bukti_pembayaran']): ?>
                                    <a href="<?= base_url('uploads/bukti_pembayaran/' . $p['bukti_pembayaran']) ?>" 
                                       target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['status_verifikasi'] == 'rejected' && $p['keterangan']): ?>
                                    <small class="text-danger"><?= $p['keterangan'] ?></small>
                                <?php elseif ($p['keterangan']): ?>
                                    <small><?= $p['keterangan'] ?></small>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
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

<!-- Informasi Status -->
<div class="card shadow mb-4 border-left-info">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info">Keterangan Status</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <span class="badge badge-success badge-lg mb-2">
                    <i class="fas fa-check-circle"></i> Terverifikasi
                </span>
                <p class="small mb-0">Pembayaran telah dikonfirmasi oleh admin dan tercatat dalam sistem.</p>
            </div>
            <div class="col-md-4">
                <span class="badge badge-warning badge-lg mb-2">
                    <i class="fas fa-clock"></i> Menunggu Verifikasi
                </span>
                <p class="small mb-0">Bukti pembayaran sedang dalam proses verifikasi oleh admin (maks. 2x24 jam).</p>
            </div>
            <div class="col-md-4">
                <span class="badge badge-danger badge-lg mb-2">
                    <i class="fas fa-times-circle"></i> Ditolak
                </span>
                <p class="small mb-0">Bukti pembayaran ditolak. Silakan hubungi admin atau upload ulang bukti yang valid.</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>