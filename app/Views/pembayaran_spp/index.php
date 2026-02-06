<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Data Pembayaran<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Pembayaran SPP</h1>
    <div class="d-flex">
        <!-- Search Bar -->
        <input type="text" id="searchInput" class="form-control mr-2" placeholder="Cari pembayaran..." style="width: 250px;">
    </div>
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
<ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab">
            Semua <span class="badge badge-primary"><?= count($pembayaran) ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab">
            Pending <span class="badge badge-warning"><?= count(array_filter($pembayaran, fn($p) => $p['status_verifikasi'] == 'pending')) ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="verified-tab" data-toggle="tab" href="#verified" role="tab">
            Terverifikasi <span class="badge badge-success"><?= count(array_filter($pembayaran, fn($p) => $p['status_verifikasi'] == 'verified')) ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab">
            Ditolak <span class="badge badge-danger"><?= count(array_filter($pembayaran, fn($p) => $p['status_verifikasi'] == 'rejected')) ?></span>
        </a>
    </li>
</ul>

<!-- DataTales -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Tabel Data Pembayaran</h6>
        <span class="badge badge-primary" id="totalPembayaran"><?= count($pembayaran) ?> Data</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Bulan</th>
                        <th>Tanggal Bayar</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="pembayaranTableBody">
                    <?php $no = 1; ?>
                    <?php foreach ($pembayaran as $p): ?>
                    <tr data-status="<?= $p['status_verifikasi'] ?>">
                        <td><?= $no++ ?></td>
                        <td><?= $p['nis'] ?></td>
                        <td><?= $p['nama'] ?></td>
                        <td><?= $p['nama_kelas'] ?? '-' ?></td>
                        <td><?= $p['bulan_dibayar'] ?> <?= $p['tahun_dibayar'] ?></td>
                        <td><?= date('d/m/Y', strtotime($p['tanggal_bayar'])) ?></td>
                        <td>Rp <?= number_format($p['jumlah_bayar'], 0, ',', '.') ?></td>
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
                            <?php if ($p['bukti_pembayaran']): ?>
                                <a href="<?= base_url('uploads/bukti_pembayaran/' . $p['bukti_pembayaran']) ?>" 
                                   target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($p['status_verifikasi'] == 'pending'): ?>
                                <a href="<?= base_url('pembayaran-spp/verifikasi/' . $p['id']) ?>" 
                                   class="btn btn-success btn-sm" 
                                   onclick="return confirm('Verifikasi pembayaran ini?')">
                                    <i class="fas fa-check"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" 
                                        data-target="#tolakModal<?= $p['id'] ?>">
                                    <i class="fas fa-times"></i>
                                </button>
                            <?php endif; ?>
                            <a href="<?= base_url('pembayaran-spp/delete/' . $p['id']) ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Yakin ingin menghapus data ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
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
                                <form method="post" action="<?= base_url('pembayaran-spp/tolak/' . $p['id']) ?>">
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
            
            <!-- Pesan jika tidak ada hasil -->
            <div id="noResultMessage" style="display: none; text-align: center; padding: 20px;">
                <i class="fas fa-search fa-3x text-gray-300 mb-3"></i>
                <p class="text-gray-600">Tidak ada data yang sesuai dengan pencarian</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Tab Filter
document.querySelectorAll('#myTab a').forEach(tab => {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        const target = this.getAttribute('href').replace('#', '');
        const rows = document.querySelectorAll('#pembayaranTableBody tr');
        
        rows.forEach(row => {
            if (target === 'all') {
                row.style.display = '';
            } else {
                const status = row.getAttribute('data-status');
                row.style.display = status === target ? '' : 'none';
            }
        });

        // Update nomor
        updateRowNumbers();
    });
});

// Live Search Function
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const tableBody = document.getElementById('pembayaranTableBody');
    const rows = tableBody.getElementsByTagName('tr');
    const noResultMessage = document.getElementById('noResultMessage');
    let visibleCount = 0;

    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;

        for (let j = 1; j < cells.length - 2; j++) {
            const cellText = cells[j].textContent || cells[j].innerText;
            if (cellText.toLowerCase().indexOf(searchValue) > -1) {
                found = true;
                break;
            }
        }

        if (found) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    }

    updateRowNumbers();

    if (visibleCount === 0) {
        tableBody.style.display = 'none';
        noResultMessage.style.display = 'block';
    } else {
        tableBody.style.display = '';
        noResultMessage.style.display = 'none';
    }

    document.getElementById('totalPembayaran').textContent = visibleCount + ' Data';
});

function updateRowNumbers() {
    const rows = document.querySelectorAll('#pembayaranTableBody tr');
    let displayNo = 1;
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            row.getElementsByTagName('td')[0].textContent = displayNo++;
        }
    });
}

// Auto-dismiss alerts
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