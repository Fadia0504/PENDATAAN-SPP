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

<!-- Tab Navigation -->
<ul class="nav nav-tabs mb-3" id="pembayaranTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="spp-tab" data-toggle="tab" href="#spp" role="tab">
            <i class="fas fa-money-bill"></i> Pembayaran SPP
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="lain-tab" data-toggle="tab" href="#lain" role="tab">
            <i class="fas fa-file-invoice"></i> Pembayaran Lain
        </a>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="pembayaranTabContent">
    
    <!-- SPP Tab -->
    <div class="tab-pane fade show active" id="spp" role="tabpanel">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Riwayat Pembayaran SPP</h6>
            </div>
            <div class="card-body">
                <?php if (empty($pembayaran_spp)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                        <p class="text-gray-600 mb-3">Belum ada riwayat pembayaran SPP</p>
                        <a href="<?= base_url('siswa/tagihan-spp') ?>" class="btn btn-success">
                            <i class="fas fa-list"></i> Lihat Tagihan SPP
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
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
                                <?php foreach ($pembayaran_spp as $p): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= date('d/m/Y', strtotime($p['tanggal_bayar'])) ?></td>
                                    <td><?= $p['bulan_dibayar'] ?> <?= $p['tahun_dibayar'] ?></td>
                                    <td>Rp <?= number_format($p['jumlah_bayar'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php if ($p['status_verifikasi'] == 'verified'): ?>
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle"></i> Terverifikasi
                                            </span>
                                        <?php elseif ($p['status_verifikasi'] == 'pending'): ?>
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">
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
                                        <?php if ($p['status_verifikasi'] == 'rejected' && isset($p['keterangan']) && $p['keterangan']): ?>
                                            <small class="text-danger"><?= $p['keterangan'] ?></small>
                                        <?php elseif (isset($p['keterangan']) && $p['keterangan']): ?>
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
    </div>

    <!-- Pembayaran Lain Tab -->
    <div class="tab-pane fade" id="lain" role="tabpanel">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Riwayat Pembayaran Lain</h6>
            </div>
            <div class="card-body">
                <?php if (empty($pembayaran_lain)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                        <p class="text-gray-600 mb-3">Belum ada riwayat pembayaran lain</p>
                        <a href="<?= base_url('siswa/tagihan-lain') ?>" class="btn btn-success">
                            <i class="fas fa-list"></i> Lihat Tagihan Pembayaran Lain
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Nama Pembayaran</th>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Bukti</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($pembayaran_lain as $p): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= date('d/m/Y', strtotime($p['tanggal_bayar'])) ?></td>
                                    <td><strong><?= $p['nama_pembayaran'] ?></strong></td>
                                    <td>
                                        <span class="badge badge-info"><?= ucfirst($p['kategori']) ?></span>
                                    </td>
                                    <td>Rp <?= number_format($p['jumlah_bayar'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php if ($p['status_verifikasi'] == 'verified'): ?>
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle"></i> Terverifikasi
                                            </span>
                                        <?php elseif ($p['status_verifikasi'] == 'pending'): ?>
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">
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
                                        <?php if ($p['status_verifikasi'] == 'rejected' && isset($p['keterangan']) && $p['keterangan']): ?>
                                            <small class="text-danger"><?= $p['keterangan'] ?></small>
                                        <?php elseif (isset($p['keterangan']) && $p['keterangan']): ?>
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
                <span class="badge badge-success mb-2">
                    <i class="fas fa-check-circle"></i> Terverifikasi
                </span>
                <p class="small mb-0">Pembayaran telah dikonfirmasi oleh admin dan tercatat dalam sistem.</p>
            </div>
            <div class="col-md-4">
                <span class="badge badge-warning mb-2">
                    <i class="fas fa-clock"></i> Pending
                </span>
                <p class="small mb-0">Bukti pembayaran sedang dalam proses verifikasi oleh admin (maks. 2x24 jam).</p>
            </div>
            <div class="col-md-4">
                <span class="badge badge-danger mb-2">
                    <i class="fas fa-times-circle"></i> Ditolak
                </span>
                <p class="small mb-0">Bukti pembayaran ditolak. Silakan hubungi admin atau upload ulang bukti yang valid.</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>