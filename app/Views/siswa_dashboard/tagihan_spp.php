<?= $this->extend('layout/siswa') ?>

<?= $this->section('title') ?>Tagihan SPP<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tagihan SPP Saya</h1>
</div>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Belum Bayar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_belum_bayar ?> Bulan</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Verifikasi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_menunggu ?> Bulan</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Lunas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_lunas ?> Bulan</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Tagihan -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success">Daftar Tagihan SPP</h6>
    </div>
    <div class="card-body">
        <?php if (empty($tagihan)): ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                <p class="text-gray-600">Belum ada tagihan SPP</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Jumlah</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($tagihan as $t): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $t['bulan'] ?></td>
                            <td><?= $t['tahun'] ?></td>
                            <td>Rp <?= number_format($t['jumlah_tagihan'], 0, ',', '.') ?></td>
                            <td><?= date('d/m/Y', strtotime($t['tanggal_jatuh_tempo'])) ?></td>
                            <td>
                                <?php if ($t['status_bayar'] == 'belum_bayar'): ?>
                                    <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Belum Bayar</span>
                                <?php elseif ($t['status_bayar'] == 'menunggu_verifikasi'): ?>
                                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Verifikasi</span>
                                <?php else: ?>
                                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> Lunas</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($t['status_bayar'] == 'belum_bayar'): ?>
                                    <a href="<?= base_url('siswa/upload-bukti?tagihan=' . $t['id']) ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-upload"></i> Bayar
                                    </a>
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

<?= $this->endSection() ?>