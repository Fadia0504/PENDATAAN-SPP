<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📊 Laporan Pembayaran Lain</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Laporan Pembayaran Lain</li>
        </ol>
    </nav>
</div>

<!-- Filter Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">🔍 Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form method="get" action="<?= base_url('laporan-pembayaran-lain') ?>">
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label class="small font-weight-bold">Status</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        <option value="pending" <?= ($filters['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="verified" <?= ($filters['status'] ?? '') == 'verified' ? 'selected' : '' ?>>Terverifikasi</option>
                        <option value="rejected" <?= ($filters['status'] ?? '') == 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="small font-weight-bold">Kategori</label>
                    <select name="kategori" class="form-control form-control-sm">
                        <option value="">Semua Kategori</option>
                        <option value="Seragam" <?= ($filters['kategori'] ?? '') == 'Seragam' ? 'selected' : '' ?>>Seragam</option>
                        <option value="Ekstrakulikuler" <?= ($filters['kategori'] ?? '') == 'Ekstrakulikuler' ? 'selected' : '' ?>>Ekstrakulikuler</option>
                        <option value="Kegiatan" <?= ($filters['kategori'] ?? '') == 'Kegiatan' ? 'selected' : '' ?>>Kegiatan</option>
                        <option value="Lainnya" <?= ($filters['kategori'] ?? '') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold">Jenis Pembayaran</label>
                    <select name="id_jenis_pembayaran" class="form-control form-control-sm">
                        <option value="">Semua Jenis</option>
                        <?php foreach ($jenis_list as $jenis): ?>
                            <option value="<?= $jenis['id'] ?>" <?= ($filters['id_jenis_pembayaran'] ?? '') == $jenis['id'] ? 'selected' : '' ?>>
                                <?= $jenis['nama_pembayaran'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="small font-weight-bold">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control form-control-sm" value="<?= $filters['tanggal_mulai'] ?? '' ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="small font-weight-bold">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control form-control-sm" value="<?= $filters['tanggal_selesai'] ?? '' ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold">Kelas</label>
                    <select name="id_kelas" class="form-control form-control-sm">
                        <option value="">Semua Kelas</option>
                        <?php foreach ($kelas_list as $kls): ?>
                            <option value="<?= $kls['id'] ?>" <?= ($filters['id_kelas'] ?? '') == $kls['id'] ? 'selected' : '' ?>>
                                <?= $kls['nama_kelas'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-search fa-sm"></i> Tampilkan
                </button>
                <a href="<?= base_url('laporan-pembayaran-lain') ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-sync-alt fa-sm"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Total Transaksi Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Transaksi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $statistik['total'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $statistik['pending'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terverifikasi Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Terverifikasi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $statistik['verified'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ditolak Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Ditolak</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $statistik['rejected'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Card -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-7 mb-3 mb-md-0">
                <h5 class="font-weight-bold text-gray-800 mb-2">
                    <i class="fas fa-money-bill-wave text-success"></i> 
                    Total Nominal: <span class="text-primary">Rp <?= number_format($statistik['total_nominal'], 0, ',', '.') ?></span>
                </h5>
                <p class="text-muted mb-0 small">
                    <i class="fas fa-check-circle text-success"></i>
                    Nominal Terverifikasi: <strong class="text-success">Rp <?= number_format($statistik['nominal_verified'], 0, ',', '.') ?></strong>
                </p>
            </div>
            <div class="col-md-5 text-md-right">
                <?php $queryString = http_build_query($filters); ?>
                <a href="<?= base_url('laporan-pembayaran-lain/export-csv?' . $queryString) ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-file-csv"></i> CSV
                </a>
                <a href="<?= base_url('laporan-pembayaran-lain/export-excel?' . $queryString) ?>" class="btn btn-info btn-sm">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="<?= base_url('laporan-pembayaran-lain/export-pdf?' . $queryString) ?>" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>
    </div>
</div>

<!-- DataTales -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">📋 Data Pembayaran Lain</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Kategori</th>
                        <th>Pembayaran</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pembayaran)): ?>
                        <tr>
                            <td colspan="10" class="text-center">
                                <div class="py-5">
                                    <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-gray-600 mb-0">Tidak ada data pembayaran</p>
                                    <small class="text-muted">Silakan ubah filter untuk melihat data lainnya</small>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($pembayaran as $item): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d/m/Y', strtotime($item['tanggal_bayar'])) ?></td>
                                <td><?= $item['nis'] ?></td>
                                <td><?= $item['nama_siswa'] ?></td>
                                <td><?= $item['nama_kelas'] ?? '-' ?></td>
                                <td>
                                    <?php
                                    $badgeClass = 'badge-secondary';
                                    if ($item['kategori'] == 'Seragam') $badgeClass = 'badge-primary';
                                    elseif ($item['kategori'] == 'Ekstrakulikuler') $badgeClass = 'badge-info';
                                    elseif ($item['kategori'] == 'Kegiatan') $badgeClass = 'badge-success';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $item['kategori'] ?></span>
                                </td>
                                <td><?= $item['nama_pembayaran'] ?></td>
                                <td>Rp <?= number_format($item['jumlah_bayar'], 0, ',', '.') ?></td>
                                <td>
                                    <?php if ($item['status_verifikasi'] == 'pending'): ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php elseif ($item['status_verifikasi'] == 'verified'): ?>
                                        <span class="badge badge-success">Terverifikasi</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('pembayaran-lain-transaksi') ?>" 
                                       class="btn btn-info btn-circle btn-sm" 
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if (!empty($item['bukti_pembayaran'])): ?>
                                        <a href="<?= base_url('uploads/bukti_pembayaran/' . $item['bukti_pembayaran']) ?>" 
                                           target="_blank" 
                                           class="btn btn-secondary btn-circle btn-sm" 
                                           title="Lihat Bukti">
                                            <i class="fas fa-image"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= base_url('laporan-pembayaran-lain/print/' . $item['id']) ?>" 
                                       target="_blank" 
                                       class="btn btn-primary btn-circle btn-sm" 
                                       title="Print Struk">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>