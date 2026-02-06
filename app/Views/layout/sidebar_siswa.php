<ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('siswa/dashboard') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Portal Siswa</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('siswa/dashboard') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Tagihan
    </div>

    <!-- Nav Item - Tagihan SPP -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('siswa/tagihan-spp') ?>">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Tagihan SPP</span>
        </a>
    </li>

    <!-- Nav Item - Tagihan Pembayaran Lain -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('siswa/tagihan-lain') ?>">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Tagihan Pembayaran Lain</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="<?= base_url('siswa/tunggakan') ?>" class="nav-link">
            <i class="nav-icon fas fa-hourglass-half"></i>
            <span>Pengajuan Tunggakan</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Pembayaran
    </div>

    <!-- Nav Item - Upload Bukti -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('siswa/upload-bukti') ?>">
            <i class="fas fa-fw fa-upload"></i>
            <span>Upload Bukti Bayar</span>
        </a>
    </li>

    <!-- Nav Item - Riwayat Pembayaran -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('siswa/riwayat-pembayaran') ?>">
            <i class="fas fa-fw fa-history"></i>
            <span>Riwayat Pembayaran</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Profil -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('siswa/profil') ?>">
            <i class="fas fa-fw fa-user"></i>
            <span>Profil Saya</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>