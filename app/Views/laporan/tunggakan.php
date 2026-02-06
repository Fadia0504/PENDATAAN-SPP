<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📊 Laporan Tunggakan</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Laporan Tunggakan</li>
        </ol>
    </nav>
</div>

<!-- Filter Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">🔍 Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form method="get" action="<?= base_url('laporan-tunggakan') ?>">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold">Status</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        <option value="pending" <?= ($filters['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= ($filters['status'] ?? '') == 'approved' ? 'selected' : '' ?>>Disetujui</option>
                        <option value="rejected" <?= ($filters['status'] ?? '') == 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold">Jenis Pembayaran</label>
                    <select name="jenis" class="form-control form-control-sm">
                        <option value="">Semua Jenis</option>
                        <option value="spp" <?= ($filters['jenis'] ?? '') == 'spp' ? 'selected' : '' ?>>SPP</option>
                        <option value="lain" <?= ($filters['jenis'] ?? '') == 'lain' ? 'selected' : '' ?>>Pembayaran Lain</option>
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
                <div class="col-md-2 mb-3">
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
                <a href="<?= base_url('laporan-tunggakan') ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-sync-alt fa-sm"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Total Pengajuan Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Pengajuan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $statistik['total'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
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
                        <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Disetujui Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Disetujui</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $statistik['approved'] ?></div>
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
                    Nominal Disetujui: <strong class="text-success">Rp <?= number_format($statistik['nominal_approved'], 0, ',', '.') ?></strong>
                </p>
            </div>
            <div class="col-md-5 text-md-right">
                <?php $queryString = http_build_query($filters); ?>
                <a href="<?= base_url('laporan-tunggakan/export-csv?' . $queryString) ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-file-csv"></i> CSV
                </a>
                <a href="<?= base_url('laporan-tunggakan/export-excel?' . $queryString) ?>" class="btn btn-info btn-sm">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="<?= base_url('laporan-tunggakan/export-pdf?' . $queryString) ?>" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>
    </div>
</div>

<!-- DataTales -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">📋 Data Pengajuan Tunggakan</h6>
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
                        <th>Jenis</th>
                        <th>Pembayaran</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Diproses Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tunggakan)): ?>
                        <tr>
                            <td colspan="10" class="text-center">
                                <div class="py-5">
                                    <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-gray-600 mb-0">Tidak ada data tunggakan</p>
                                    <small class="text-muted">Silakan ubah filter untuk melihat data lainnya</small>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($tunggakan as $item): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></td>
                                <td><?= $item['nis'] ?></td>
                                <td><?= $item['nama_siswa'] ?></td>
                                <td><?= $item['nama_kelas'] ?? '-' ?></td>
                                <td>
                                    <?php if ($item['jenis_tagihan'] == 'SPP'): ?>
                                        <span class="badge badge-primary">SPP</span>
                                    <?php else: ?>
                                        <span class="badge badge-info">Lainnya</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $item['nama_pembayaran'] ?></td>
                                <td>Rp <?= number_format($item['jumlah_tagihan'], 0, ',', '.') ?></td>
                                <td>
                                    <?php if ($item['status'] == 'pending'): ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php elseif ($item['status'] == 'approved'): ?>
                                        <span class="badge badge-success">Disetujui</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $item['diproses_oleh_nama'] ?? '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>