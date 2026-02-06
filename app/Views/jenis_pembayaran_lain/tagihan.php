<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Tagihan Pembayaran Lain<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tagihan Pembayaran Lain</h1>
    <a href="<?= base_url('jenis-pembayaran-lain/create-tagihan') ?>" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50">
            <i class="fas fa-paper-plane"></i>
        </span>
        <span class="text">Kirim Tagihan</span>
    </a>
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

<!-- Tabs Menu -->
<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('jenis-pembayaran-lain') ?>">
            <i class="fas fa-list"></i> Jenis Pembayaran
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="<?= base_url('jenis-pembayaran-lain/tagihan') ?>">
            <i class="fas fa-paper-plane"></i> Tagihan
        </a>
    </li>
</ul>

<!-- Filter Tabs -->
<ul class="nav nav-tabs mb-3" id="statusTab">
    <li class="nav-item">
        <a class="nav-link active" href="#" data-status="all">
            Semua <span class="badge badge-primary"><?= count($tagihan) ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-status="belum_bayar">
            Belum Bayar <span class="badge badge-danger"><?= count(array_filter($tagihan, fn($t) => $t['status_bayar'] == 'belum_bayar')) ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-status="menunggu_verifikasi">
            Menunggu <span class="badge badge-warning"><?= count(array_filter($tagihan, fn($t) => $t['status_bayar'] == 'menunggu_verifikasi')) ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-status="lunas">
            Lunas <span class="badge badge-success"><?= count(array_filter($tagihan, fn($t) => $t['status_bayar'] == 'lunas')) ?></span>
        </a>
    </li>
</ul>

<!-- Data Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Tagihan</h6>
        <input type="text" id="searchInput" class="form-control" placeholder="Cari..." style="width: 250px;">
    </div>
    <div class="card-body">
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
                        <th>Jumlah</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php $no = 1; ?>
                    <?php foreach ($tagihan as $t): ?>
                    <tr data-status="<?= $t['status_bayar'] ?>">
                        <td><?= $no++ ?></td>
                        <td><?= $t['nis'] ?></td>
                        <td><?= $t['nama'] ?></td>
                        <td><?= $t['nama_kelas'] ?? '-' ?></td>
                        <td><strong><?= $t['nama_pembayaran'] ?></strong></td>
                        <td><span class="badge badge-info"><?= ucfirst($t['kategori']) ?></span></td>
                        <td>Rp <?= number_format($t['jumlah_tagihan'], 0, ',', '.') ?></td>
                        <td><?= date('d/m/Y', strtotime($t['tanggal_jatuh_tempo'])) ?></td>
                        <td>
                            <?php if ($t['status_bayar'] == 'belum_bayar'): ?>
                                <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Belum Bayar</span>
                            <?php elseif ($t['status_bayar'] == 'menunggu_verifikasi'): ?>
                                <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu</span>
                            <?php else: ?>
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Lunas</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($t['status_bayar'] != 'lunas'): ?>
                                <a href="<?= base_url('jenis-pembayaran-lain/delete-tagihan/' . $t['id']) ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Yakin hapus?')">
                                    <i class="fas fa-trash"></i>
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