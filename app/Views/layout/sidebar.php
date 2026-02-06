<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('dashboard') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SPP System</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('dashboard') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Data Master
    </div>

    <!-- Nav Item - Data Siswa -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('siswa') ?>">
            <i class="fas fa-fw fa-user-graduate"></i>
            <span>Data Siswa</span>
        </a>
    </li>

    <!-- Nav Item - Data Guru -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('guru') ?>">
            <i class="fas fa-fw fa-chalkboard-teacher"></i>
            <span>Data Guru</span>
        </a>
    </li>

    <!-- Nav Item - Data Kelas -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('kelas') ?>">
            <i class="fas fa-fw fa-door-open"></i>
            <span>Data Kelas</span>
        </a>
    </li>

    <!-- Nav Item - Data Spp -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('data-spp') ?>">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Data SPP</span>
        </a>
    </li>

    <!-- Nav Item - Data Pembayaran Lain -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('jenis-pembayaran-lain') ?>">
            <i class="fas fa-fw fa-receipt"></i>
            <span>Jenis Pembayaran Lain</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Transaksi
    </div>

    <!-- Nav Item - Pembayaran SPP -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('pembayaran-spp') ?>">
            <i class="fas fa-fw fa-money-check-alt"></i>
            <span>Pembayaran SPP</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('pembayaran-lain-transaksi') ?>">
            <i class="fas fa-fw fa-hand-holding-usd"></i>
            <span>Pembayaran Lain</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('tunggakan-transaksi') ?>">
            <i class="fas fa-fw fa-exclamation-triangle"></i>
            <span>Transaksi Tunggakan</span>
        </a>
    </li>


    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Laporan
    </div>
    <!-- Nav Item - Laporan -->
    <li class="nav-item">
        <a class="nav-link" href="laporan-pembayaran-spp">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Laporan Pembayaran SPP</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="laporan-pembayaran-lain">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span>Laporan Pembayaran Lain</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="laporan-tunggakan">
            <i class="fas fa-fw fa-chart-pie"></i>
            <span>Laporan Tunggakan</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>