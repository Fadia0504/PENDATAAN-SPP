<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Data Guru<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-chalkboard-teacher"></i> <?= $title ?>
    </h1>
    <div class="d-flex">
        <!-- Search Bar -->
        <input type="text" id="searchInput" class="form-control mr-2" placeholder="Cari guru..." style="width: 250px;">
        
        <!-- Tombol Tambah -->
        <a href="<?= base_url('guru/create') ?>" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Tambah Guru</span>
        </a>
    </div>
</div>

<!-- Alert Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Guru
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <span id="totalGuru"><?= $statistik['total'] ?></span> Orang
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Guru Aktif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $statistik['aktif'] ?> Orang
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Laki-laki
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $statistik['laki_laki'] ?> Orang
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-male fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Perempuan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $statistik['perempuan'] ?> Orang
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-female fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Guru</h6>
        <select id="rowsPerPage" class="form-control form-control-sm" style="width: auto;">
            <option value="10">10 per halaman</option>
            <option value="25">25 per halaman</option>
            <option value="50">50 per halaman</option>
            <option value="100">100 per halaman</option>
        </select>
    </div>
    <div class="card-body">
        <?php if (empty($guru)): ?>
            <div class="text-center py-5">
                <i class="fas fa-user-slash fa-3x text-gray-300 mb-3"></i>
                <p class="text-gray-600">Belum ada data guru</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Foto</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Mata Pelajaran</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="guruTableBody">
                        <?php $no = 1; ?>
                        <?php foreach ($guru as $g): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="text-center">
                                <?php if (!empty($g['foto'])): ?>
                                    <img src="<?= base_url('uploads/foto_guru/' . $g['foto']) ?>" 
                                         alt="<?= esc($g['nama']) ?>"
                                         class="img-thumbnail rounded-circle"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-user fa-2x text-white"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($g['nip']) ?></td>
                            <td>
                                <strong><?= esc($g['nama']) ?></strong><br>
                                <small class="text-muted">
                                    <?= $g['jenis_kelamin'] == 'L' ? '<i class="fas fa-mars text-info"></i> Laki-laki' : '<i class="fas fa-venus text-danger"></i> Perempuan' ?>
                                </small>
                            </td>
                            <td>
                                <span class="badge badge-info"><?= esc($g['mata_pelajaran'] ?? '-') ?></span>
                            </td>
                            <td>
                                <?php if (!empty($g['email'])): ?>
                                    <i class="fas fa-envelope"></i> <?= esc($g['email']) ?><br>
                                <?php endif; ?>
                                <?php if (!empty($g['no_telepon'])): ?>
                                    <i class="fas fa-phone"></i> <?= esc($g['no_telepon']) ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($g['status'] == 'aktif'): ?>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-times-circle"></i> Non-Aktif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('guru/detail/' . $g['id']) ?>" 
                                       class="btn btn-sm btn-info" 
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('guru/edit/' . $g['id']) ?>" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('guru/delete/' . $g['id']) ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Yakin ingin menghapus guru <?= esc($g['nama']) ?>?')"
                                       title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- Pesan jika tidak ada hasil -->
                <div id="noResultMessage" style="display: none; text-align: center; padding: 20px;">
                    <i class="fas fa-search fa-3x text-gray-300 mb-3"></i>
                    <p class="text-gray-600">Tidak ada data yang sesuai dengan pencarian</p>
                </div>
            </div>
            
            <!-- Pagination Controls -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div id="paginationInfo" class="text-muted">
                    Menampilkan <span id="startRow">1</span> sampai <span id="endRow">10</span> dari <span id="totalRows"><?= count($guru) ?></span> data
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0" id="paginationControls">
                        <!-- Pagination buttons will be generated by JavaScript -->
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
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

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('guruTableBody');
    if (tableBody) {
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
    }
});

// Live Search Function
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const tableBody = document.getElementById('guruTableBody');
    const noResultMessage = document.getElementById('noResultMessage');
    
    if (!tableBody) return;
    
    filteredRows = allRows.filter(row => {
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        // Search in NIP, Nama, Mata Pelajaran, Kontak, Status (skip No, Foto, Aksi)
        const searchableIndices = [2, 3, 4, 5, 6]; // NIP, Nama, Mata Pelajaran, Kontak, Status
        
        for (let j of searchableIndices) {
            if (cells[j]) {
                const cellText = cells[j].textContent || cells[j].innerText;
                if (cellText.toLowerCase().indexOf(searchValue) > -1) {
                    found = true;
                    break;
                }
            }
        }
        
        return found;
    });
    
    // Reset to first page when searching
    currentPage = 1;
    
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
    
    // Update badge count
    document.getElementById('totalGuru').textContent = filteredRows.length;
    document.getElementById('totalRows').textContent = filteredRows.length;
    
    updatePagination();
});

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