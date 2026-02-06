<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Detail Guru<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-user"></i> <?= $title ?>
    </h1>
    <div>
        <a href="<?= base_url('guru/edit/' . $guru['id']) ?>" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-edit"></i>
            </span>
            <span class="text">Edit</span>
        </a>
        <a href="<?= base_url('guru') ?>" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Kembali</span>
        </a>
    </div>
</div>

<div class="row">
    <!-- Profile Card -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-body text-center">
                <?php if (!empty($guru['foto'])): ?>
                    <img src="<?= base_url('uploads/foto_guru/' . $guru['foto']) ?>" 
                         alt="<?= esc($guru['nama']) ?>"
                         class="img-thumbnail rounded-circle mb-3"
                         style="width: 200px; height: 200px; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 200px; height: 200px;">
                        <i class="fas fa-user fa-5x text-white"></i>
                    </div>
                <?php endif; ?>

                <h4 class="mb-1"><?= esc($guru['nama']) ?></h4>
                <?php if (!empty($guru['gelar'])): ?>
                    <p class="text-muted"><?= esc($guru['gelar']) ?></p>
                <?php endif; ?>

                <div class="mt-3">
                    <?php if ($guru['status'] == 'aktif'): ?>
                        <span class="badge badge-success badge-lg">
                            <i class="fas fa-check-circle"></i> Aktif
                        </span>
                    <?php else: ?>
                        <span class="badge badge-secondary badge-lg">
                            <i class="fas fa-times-circle"></i> Non-Aktif
                        </span>
                    <?php endif; ?>
                </div>

                <hr>

                <div class="text-left">
                    <p class="mb-2">
                        <i class="fas fa-id-card text-primary"></i> 
                        <strong>NIP:</strong> <?= esc($guru['nip']) ?>
                    </p>
                    
                    <?php if (!empty($guru['mata_pelajaran'])): ?>
                    <p class="mb-2">
                        <i class="fas fa-book text-info"></i> 
                        <strong>Mapel:</strong> <?= esc($guru['mata_pelajaran']) ?>
                    </p>
                    <?php endif; ?>

                    <?php if (!empty($guru['jenis_kelamin'])): ?>
                    <p class="mb-2">
                        <?= $guru['jenis_kelamin'] == 'L' ? '<i class="fas fa-mars text-info"></i>' : '<i class="fas fa-venus text-danger"></i>' ?>
                        <strong>Jenis Kelamin:</strong> 
                        <?= $guru['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Card -->
    <div class="col-lg-8">
        <!-- Data Pribadi -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-circle"></i> Data Pribadi
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Tempat Lahir</strong></td>
                                <td>: <?= esc($guru['tempat_lahir'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Lahir</strong></td>
                                <td>: 
                                    <?php if (!empty($guru['tanggal_lahir'])): ?>
                                        <?= date('d F Y', strtotime($guru['tanggal_lahir'])) ?>
                                        <br><small class="text-muted">(<?= date_diff(date_create($guru['tanggal_lahir']), date_create('now'))->y ?> tahun)</small>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>: 
                                    <?php if (!empty($guru['email'])): ?>
                                        <a href="mailto:<?= $guru['email'] ?>"><?= esc($guru['email']) ?></a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>No. Telepon</strong></td>
                                <td>: 
                                    <?php if (!empty($guru['no_telepon'])): ?>
                                        <a href="tel:<?= $guru['no_telepon'] ?>"><?= esc($guru['no_telepon']) ?></a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>: <?= esc($guru['alamat'] ?? '-') ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Kepegawaian -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-briefcase"></i> Data Kepegawaian
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="50%"><strong>Mata Pelajaran</strong></td>
                                <td>: <?= esc($guru['mata_pelajaran'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Pendidikan Terakhir</strong></td>
                                <td>: <?= esc($guru['pendidikan_terakhir'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Gelar</strong></td>
                                <td>: <?= esc($guru['gelar'] ?? '-') ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="50%"><strong>Tanggal Masuk</strong></td>
                                <td>: 
                                    <?php if (!empty($guru['tanggal_masuk'])): ?>
                                        <?= date('d F Y', strtotime($guru['tanggal_masuk'])) ?>
                                        <br><small class="text-muted">(<?= date_diff(date_create($guru['tanggal_masuk']), date_create('now'))->y ?> tahun mengajar)</small>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>: 
                                    <?php if ($guru['status'] == 'aktif'): ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Non-Aktif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data System -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-database"></i> Informasi System
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Dibuat:</strong><br>
                            <small class="text-muted">
                                <?= date('d F Y H:i', strtotime($guru['created_at'])) ?>
                            </small>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Terakhir Update:</strong><br>
                            <small class="text-muted">
                                <?= date('d F Y H:i', strtotime($guru['updated_at'])) ?>
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-right">
            <a href="<?= base_url('guru/edit/' . $guru['id']) ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Data
            </a>
            <a href="<?= base_url('guru/delete/' . $guru['id']) ?>" 
               class="btn btn-danger"
               onclick="return confirm('Yakin ingin menghapus data guru <?= esc($guru['nama']) ?>?')">
                <i class="fas fa-trash"></i> Hapus
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>