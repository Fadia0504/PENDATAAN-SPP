<?= $this->extend('layout/siswa') ?>

<?= $this->section('title') ?>Profil Saya<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Profil Saya</h1>

<div class="row">
    <!-- Profil Card -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Informasi Pribadi</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <img class="img-profile rounded-circle" style="width: 120px; height: 120px;"
                        src="<?= base_url('assets/sbadmin2/img/undraw_profile.svg') ?>">
                    <h5 class="mt-3 mb-0"><?= session()->get('nama') ?></h5>
                    <small class="text-muted"><?= session()->get('nis') ?></small>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="font-weight-bold">NIS</label>
                            <p class="text-gray-800"><?= session()->get('nis') ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="font-weight-bold">Nama Lengkap</label>
                            <p class="text-gray-800"><?= session()->get('nama') ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="font-weight-bold">Kelas</label>
                            <p class="text-gray-800"><?= session()->get('nama_kelas') ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="font-weight-bold">Username</label>
                            <p class="text-gray-800"><?= session()->get('username') ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="font-weight-bold">Role</label>
                            <p class="text-gray-800">
                                <span class="badge badge-success">Siswa</span>
                            </p>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> Untuk mengubah data pribadi, silakan hubungi administrator sekolah.
                </div>
            </div>
        </div>
    </div>

    <!-- Aksi Card -->
    <div class="col-lg-4">
        <!-- Quick Stats -->
        <div class="card shadow mb-4 border-left-success">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Quick Stats</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Status Akun</small>
                    <h5 class="text-success mb-0">
                        <i class="fas fa-check-circle"></i> Aktif
                    </h5>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Terdaftar Sejak</small>
                    <h6 class="mb-0"><?= date('d F Y') ?></h6>
                </div>
            </div>
        </div>

        <!-- Menu Actions -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Menu Cepat</h6>
            </div>
            <div class="card-body">
                <a href="<?= base_url('siswa/dashboard') ?>" class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="<?= base_url('siswa/pembayaran') ?>" class="btn btn-info btn-block mb-2">
                    <i class="fas fa-history"></i> Riwayat Pembayaran
                </a>
                <a href="<?= base_url('siswa/upload-bukti') ?>" class="btn btn-success btn-block mb-2">
                    <i class="fas fa-upload"></i> Upload Bukti Bayar
                </a>
                <a href="#" data-toggle="modal" data-target="#logoutModal" class="btn btn-danger btn-block">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Bantuan -->
        <div class="card shadow mb-4 border-left-warning">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">Butuh Bantuan?</h6>
            </div>
            <div class="card-body">
                <p class="small mb-2">Jika mengalami kendala, hubungi:</p>
                <p class="mb-0">
                    <i class="fas fa-phone"></i> <strong>Admin:</strong> 0812-3456-7890<br>
                    <i class="fas fa-envelope"></i> <strong>Email:</strong> admin@sekolah.com
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>