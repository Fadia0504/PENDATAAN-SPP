<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Data SPP<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data SPP & Tagihan</h1>
    <div>
        <a href="<?= base_url('data-spp/kirim-tagihan') ?>" class="btn btn-success btn-icon-split mr-2">
            <span class="icon text-white-50">
                <i class="fas fa-paper-plane"></i>
            </span>
            <span class="text">Kirim Tagihan</span>
        </a>
        <a href="<?= base_url('data-spp/create') ?>" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Tambah SPP</span>
        </a>
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

<!-- Master Data SPP -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Master Data SPP</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun Ajaran</th>
                        <th>Nominal per Bulan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($spp as $s): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= $s['tahun'] ?></strong></td>
                        <td><strong class="text-success">Rp <?= number_format($s['nominal'], 0, ',', '.') ?></strong></td>
                        <td>
                            <a href="<?= base_url('data-spp/edit/' . $s['id']) ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="<?= base_url('data-spp/delete/' . $s['id']) ?>" class="btn btn-danger btn-sm" 
                               onclick="return confirm('Yakin ingin menghapus data ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Daftar Tagihan -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Tagihan SPP yang Sudah Dikirim</h6>
        <div class="d-flex align-items-center">
            <input type="text" id="searchInput" class="form-control mr-2" placeholder="Cari tagihan..." style="width: 250px;">
            <select id="rowsPerPage" class="form-control form-control-sm" style="width: auto;">
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
                <option value="100">100 per halaman</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Tabs -->
        <ul class="nav nav-tabs mb-3" id="statusTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="all-tab" data-toggle="tab" href="#" data-status="all">
                    Semua <span class="badge badge-primary" id="badgeAll"><?= count($tagihan) ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="belum-tab" data-toggle="tab" href="#" data-status="belum_bayar">
                    Belum Bayar <span class="badge badge-danger" id="badgeBelum"><?= count(array_filter($tagihan, fn($t) => $t['status_bayar'] == 'belum_bayar')) ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pending-tab" data-toggle="tab" href="#" data-status="menunggu_verifikasi">
                    Menunggu Verifikasi <span class="badge badge-warning" id="badgePending"><?= count(array_filter($tagihan, fn($t) => $t['status_bayar'] == 'menunggu_verifikasi')) ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="lunas-tab" data-toggle="tab" href="#" data-status="lunas">
                    Lunas <span class="badge badge-success" id="badgeLunas"><?= count(array_filter($tagihan, fn($t) => $t['status_bayar'] == 'lunas')) ?></span>
                </a>
            </li>
        </ul>

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th>Jumlah</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tagihanTableBody">
                    <?php $no = 1; ?>
                    <?php foreach ($tagihan as $t): ?>
                    <tr data-status="<?= $t['status_bayar'] ?>">
                        <td><?= $no++ ?></td>
                        <td><?= $t['nis'] ?></td>
                        <td><?= $t['nama'] ?></td>
                        <td><?= $t['nama_kelas'] ?? '-' ?></td>
                        <td><?= $t['bulan'] ?></td>
                        <td><?= $t['tahun'] ?></td>
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
                                <a href="<?= base_url('data-spp/delete-tagihan/' . $t['id']) ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Yakin ingin menghapus tagihan ini?')">
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
            
            <!-- Pesan jika tidak ada hasil -->
            <div id="noResultMessage" style="display: none; text-align: center; padding: 20px;">
                <i class="fas fa-search fa-3x text-gray-300 mb-3"></i>
                <p class="text-gray-600">Tidak ada data yang sesuai dengan pencarian atau filter</p>
            </div>
        </div>
        
        <!-- Pagination Controls -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div id="paginationInfo" class="text-muted">
                Menampilkan <span id="startRow">1</span> sampai <span id="endRow">10</span> dari <span id="totalRows"><?= count($tagihan) ?></span> data
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination mb-0" id="paginationControls">
                    <!-- Pagination buttons will be generated by JavaScript -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Pagination Variables
let currentPage = 1;
let rowsPerPage = 10;
let allRows = [];
let filteredRows = [];
let currentStatus = 'all';

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('tagihanTableBody');
    allRows = Array.from(tableBody.getElementsByTagName('tr'));
    filteredRows = [...allRows];
    
    // Initial pagination
    updatePagination();
    
    // Rows per page change handler
    document.getElementById('rowsPerPage').addEventListener('change', function() {
        rowsPerPage = parseInt(this.value);
        currentPage = 1;
        updatePagination();
    });
});

// Filter by status
document.querySelectorAll('#statusTab a').forEach(tab => {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        currentStatus = this.getAttribute('data-status');
        currentPage = 1;
        
        // Apply filter
        applyFilters();
        
        // Update active tab
        document.querySelectorAll('#statusTab a').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
    });
});

// Live Search
document.getElementById('searchInput').addEventListener('keyup', function() {
    currentPage = 1;
    applyFilters();
});

// Apply all filters (status + search)
function applyFilters() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const tableBody = document.getElementById('tagihanTableBody');
    const noResultMessage = document.getElementById('noResultMessage');
    
    filteredRows = allRows.filter(row => {
        // Check status filter
        const rowStatus = row.getAttribute('data-status');
        const statusMatch = currentStatus === 'all' || rowStatus === currentStatus;
        
        if (!statusMatch) return false;
        
        // Check search filter
        if (searchValue === '') return true;
        
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        for (let i = 1; i < cells.length - 1; i++) {
            const cellText = cells[i].textContent || cells[i].innerText;
            if (cellText.toLowerCase().indexOf(searchValue) > -1) {
                found = true;
                break;
            }
        }
        
        return found;
    });
    
    // Show/hide "no result" message
    if (filteredRows.length === 0) {
        tableBody.style.display = 'none';
        noResultMessage.style.display = 'block';
        document.getElementById('paginationControls').style.display = 'none';
        document.getElementById('paginationInfo').style.display = 'none';
    } else {
        tableBody.style.display = '';
        noResultMessage.style.display = 'none';
        document.getElementById('paginationControls').style.display = 'flex';
        document.getElementById('paginationInfo').style.display = 'block';
    }
    
    // Update counts
    document.getElementById('totalRows').textContent = filteredRows.length;
    
    updatePagination();
}

// Update Pagination
function updatePagination() {
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
    const startIndex = (currentPage - 1) * rowsPerPage;
    const endIndex = Math.min(startIndex + rowsPerPage, filteredRows.length);
    
    // Hide all rows first
    allRows.forEach(row => row.style.display = 'none');
    
    // Show only rows for current page
    for (let i = startIndex; i < endIndex; i++) {
        filteredRows[i].style.display = '';
        // Update row number
        filteredRows[i].getElementsByTagName('td')[0].textContent = i + 1;
    }
    
    // Update pagination info
    document.getElementById('startRow').textContent = filteredRows.length > 0 ? startIndex + 1 : 0;
    document.getElementById('endRow').textContent = endIndex;
    
    // Generate pagination buttons
    generatePaginationButtons(totalPages);
}

// Generate Pagination Buttons
function generatePaginationButtons(totalPages) {
    const paginationControls = document.getElementById('paginationControls');
    paginationControls.innerHTML = '';
    
    if (totalPages <= 1) {
        return;
    }
    
    // Previous button
    const prevLi = document.createElement('li');
    prevLi.className = 'page-item' + (currentPage === 1 ? ' disabled' : '');
    prevLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">
        <i class="fas fa-chevron-left"></i>
    </a>`;
    paginationControls.appendChild(prevLi);
    
    // Page numbers
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    if (endPage - startPage < maxVisiblePages - 1) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    // First page
    if (startPage > 1) {
        const firstLi = document.createElement('li');
        firstLi.className = 'page-item';
        firstLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(1); return false;">1</a>`;
        paginationControls.appendChild(firstLi);
        
        if (startPage > 2) {
            const dotsLi = document.createElement('li');
            dotsLi.className = 'page-item disabled';
            dotsLi.innerHTML = `<a class="page-link" href="#">...</a>`;
            paginationControls.appendChild(dotsLi);
        }
    }
    
    // Visible pages
    for (let i = startPage; i <= endPage; i++) {
        const pageLi = document.createElement('li');
        pageLi.className = 'page-item' + (i === currentPage ? ' active' : '');
        pageLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>`;
        paginationControls.appendChild(pageLi);
    }
    
    // Last page
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            const dotsLi = document.createElement('li');
            dotsLi.className = 'page-item disabled';
            dotsLi.innerHTML = `<a class="page-link" href="#">...</a>`;
            paginationControls.appendChild(dotsLi);
        }
        
        const lastLi = document.createElement('li');
        lastLi.className = 'page-item';
        lastLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${totalPages}); return false;">${totalPages}</a>`;
        paginationControls.appendChild(lastLi);
    }
    
    // Next button
    const nextLi = document.createElement('li');
    nextLi.className = 'page-item' + (currentPage === totalPages ? ' disabled' : '');
    nextLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">
        <i class="fas fa-chevron-right"></i>
    </a>`;
    paginationControls.appendChild(nextLi);
}

// Change Page Function
function changePage(page) {
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
    if (page < 1 || page > totalPages) return;
    
    currentPage = page;
    updatePagination();
    
    // Scroll to top of table
    document.getElementById('dataTable').scrollIntoView({ behavior: 'smooth', block: 'start' });
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