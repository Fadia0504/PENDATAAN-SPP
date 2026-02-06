<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Verifikasi Pembayaran Lain<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Verifikasi Pembayaran Lain</h1>
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

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- Filter Tabs -->
<ul class="nav nav-tabs mb-3" id="statusTab">
    <li class="nav-item">
        <a class="nav-link active" href="#" data-status="all">
            Semua <span class="badge badge-primary"><?= count($pembayaran) ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-status="pending">
            Pending <span class="badge badge-warning"><?= count(array_filter($pembayaran, fn($p) => $p['status_verifikasi'] == 'pending')) ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-status="verified">
            Verified <span class="badge badge-success"><?= count(array_filter($pembayaran, fn($p) => $p['status_verifikasi'] == 'verified')) ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-status="rejected">
            Rejected <span class="badge badge-danger"><?= count(array_filter($pembayaran, fn($p) => $p['status_verifikasi'] == 'rejected')) ?></span>
        </a>
    </li>
</ul>

<!-- Data Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Pembayaran</h6>
        <input type="text" id="searchInput" class="form-control" placeholder="Cari..." style="width: 250px;">
    </div>
    <div class="card-body">
        <?php if (empty($pembayaran)): ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                <p class="text-gray-600">Belum ada pembayaran yang diupload</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Pembayaran</th>
                            <th>Kategori</th>
                            <th>Tanggal Bayar</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php $no = 1; ?>
                        <?php foreach ($pembayaran as $p): ?>
                        <tr data-status="<?= $p['status_verifikasi'] ?>">
                            <td><?= $no++ ?></td>
                            <td><?= esc($p['nis'] ?? '-') ?></td>
                            <td><?= esc($p['nama_siswa'] ?? $p['nama'] ?? 'N/A') ?></td>
                            <td><?= esc($p['nama_kelas'] ?? '-') ?></td>
                            <td><strong><?= esc($p['nama_pembayaran'] ?? '-') ?></strong></td>
                            <td><span class="badge badge-info"><?= ucfirst($p['kategori'] ?? '-') ?></span></td>
                            <td><?= isset($p['tanggal_bayar']) ? date('d/m/Y', strtotime($p['tanggal_bayar'])) : '-' ?></td>
                            <td>Rp <?= number_format($p['jumlah_bayar'] ?? 0, 0, ',', '.') ?></td>
                            <td>
                                <?php if ($p['status_verifikasi'] == 'verified'): ?>
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Verified</span>
                                <?php elseif ($p['status_verifikasi'] == 'pending'): ?>
                                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>
                                <?php else: ?>
                                    <span class="badge badge-danger"><i class="fas fa-times"></i> Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($p['bukti_pembayaran'])): ?>
                                    <a href="<?= base_url('uploads/bukti_pembayaran/' . $p['bukti_pembayaran']) ?>" 
                                       target="_blank" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['status_verifikasi'] == 'pending'): ?>
                                    <a href="<?= base_url('pembayaran-lain-transaksi/verifikasi/' . $p['id']) ?>" 
                                       class="btn btn-success btn-sm" 
                                       onclick="return confirm('Verifikasi pembayaran ini?')">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" 
                                            data-target="#tolakModal<?= $p['id'] ?>">
                                        <i class="fas fa-times"></i>
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- Modal Tolak -->
                        <div class="modal fade" id="tolakModal<?= $p['id'] ?>" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tolak Pembayaran</h5>
                                        <button class="close" type="button" data-dismiss="modal">
                                            <span>×</span>
                                        </button>
                                    </div>
                                    <form method="post" action="<?= base_url('pembayaran-lain-transaksi/tolak/' . $p['id']) ?>">
                                        <?= csrf_field() ?>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Alasan Penolakan:</label>
                                                <textarea name="keterangan" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <button class="btn btn-danger" type="submit">Tolak</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Filter by status
document.querySelectorAll('#statusTab a').forEach(tab => {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        const status = this.getAttribute('data-status');
        const rows = document.querySelectorAll('#tableBody tr');
        
        rows.forEach(row => {
            if (status === 'all') {
                row.style.display = '';
            } else {
                row.style.display = row.getAttribute('data-status') === status ? '' : 'none';
            }
        });
        
        document.querySelectorAll('#statusTab a').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
    });
});

// Search
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#tableBody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});

// Auto dismiss
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        alert.querySelector('.close')?.click();
    });
}, 3000);
</script>
<?= $this->endSection() ?>