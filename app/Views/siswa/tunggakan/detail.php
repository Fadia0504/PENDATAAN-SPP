<?= $this->extend('layout/siswa') ?>

<?= $this->section('content') ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= esc($title) ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('siswa/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('siswa/tunggakan') ?>">Pengajuan Tunggakan</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <!-- Alert Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Status Pengajuan -->
                <div class="col-md-12">
                    <div class="card card-widget widget-user-2">
                        <div class="widget-user-header" style="background-color: 
                            <?= $tunggakan['status'] == 'pending' ? '#ffc107' : 
                                ($tunggakan['status'] == 'approved' ? '#28a745' : '#dc3545') ?>">
                            <div class="row">
                                <div class="col-md-8">
                                    <h3 class="widget-user-username text-white">
                                        Status: 
                                        <?php if ($tunggakan['status'] == 'pending'): ?>
                                            Menunggu Persetujuan Admin
                                        <?php elseif ($tunggakan['status'] == 'approved'): ?>
                                            Pengajuan Disetujui
                                        <?php else: ?>
                                            Pengajuan Ditolak
                                        <?php endif; ?>
                                    </h3>
                                    <h5 class="widget-user-desc text-white">
                                        Tanggal Pengajuan: <?= date('d F Y, H:i', strtotime($tunggakan['created_at'])) ?>
                                    </h5>
                                </div>
                                <div class="col-md-4 text-right">
                                    <?php if ($tunggakan['status'] == 'pending'): ?>
                                        <i class="fas fa-clock fa-5x opacity-50"></i>
                                    <?php elseif ($tunggakan['status'] == 'approved'): ?>
                                        <i class="fas fa-check-circle fa-5x opacity-50"></i>
                                    <?php else: ?>
                                        <i class="fas fa-times-circle fa-5x opacity-50"></i>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Informasi Tagihan -->
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-file-invoice"></i> Informasi Tagihan</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Jenis Pembayaran</th>
                                    <td>: <?= esc($tunggakan['nama_pembayaran']) ?></td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>: <span class="badge badge-info"><?= esc(ucfirst($tunggakan['kategori'])) ?></span></td>
                                </tr>
                                <tr>
                                    <th>Jumlah Tagihan</th>
                                    <td>: <h4 class="text-danger mb-0">Rp <?= number_format($tunggakan['jumlah_tagihan'], 0, ',', '.') ?></h4></td>
                                </tr>
                                <tr>
                                    <th>Status Pembayaran</th>
                                    <td>: 
                                        <?php if ($tunggakan['status_bayar'] == 'lunas'): ?>
                                            <span class="badge badge-success badge-lg">Lunas</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning badge-lg">Belum Bayar</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Timeline Jatuh Tempo -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Timeline Jatuh Tempo</h3>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <!-- Tempo Lama -->
                                <div>
                                    <i class="fas fa-calendar bg-danger"></i>
                                    <div class="timeline-item">
                                        <h3 class="timeline-header">Jatuh Tempo Awal</h3>
                                        <div class="timeline-body">
                                            <strong class="text-danger"><?= date('d F Y', strtotime($tunggakan['tanggal_jatuh_tempo_lama'])) ?></strong>
                                            <p class="mb-0 text-muted">Tanggal jatuh tempo sebelum pengajuan tunggakan</p>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($tunggakan['tanggal_jatuh_tempo_baru']): ?>
                                    <!-- Tempo Baru -->
                                    <div>
                                        <i class="fas fa-calendar-check bg-success"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header">Jatuh Tempo Baru</h3>
                                            <div class="timeline-body">
                                                <strong class="text-success"><?= date('d F Y', strtotime($tunggakan['tanggal_jatuh_tempo_baru'])) ?></strong>
                                                <p class="mb-0 text-muted">Perpanjangan yang disetujui admin</p>
                                                <?php 
                                                $selisih = (strtotime($tunggakan['tanggal_jatuh_tempo_baru']) - strtotime($tunggakan['tanggal_jatuh_tempo_lama'])) / (60*60*24);
                                                ?>
                                                <span class="badge badge-success">+<?= round($selisih) ?> hari perpanjangan</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div>
                                    <i class="fas fa-clock bg-gray"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alasan & Bukti -->
                <div class="col-md-6">
                    <!-- Alasan Pengajuan -->
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-comment-dots"></i> Alasan Pengajuan Anda</h3>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-warning">
                                <p class="text-justify"><?= nl2br(esc($tunggakan['alasan'])) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Bukti Pendukung -->
                    <?php if ($tunggakan['bukti_pendukung']): ?>
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-paperclip"></i> Bukti Pendukung</h3>
                            </div>
                            <div class="card-body text-center">
                                <?php 
                                $ext = pathinfo($tunggakan['bukti_pendukung'], PATHINFO_EXTENSION);
                                $buktiPath = base_url('uploads/bukti_tunggakan/' . $tunggakan['bukti_pendukung']);
                                ?>
                                <?php if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])): ?>
                                    <img src="<?= $buktiPath ?>" class="img-fluid rounded" style="max-height: 400px; cursor: pointer;" 
                                         onclick="window.open('<?= $buktiPath ?>', '_blank')">
                                    <p class="text-muted mt-2 mb-0">
                                        <small>Klik gambar untuk melihat ukuran penuh</small>
                                    </p>
                                <?php else: ?>
                                    <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>
                                    <br>
                                    <a href="<?= $buktiPath ?>" target="_blank" class="btn btn-danger btn-lg">
                                        <i class="fas fa-download"></i> Unduh File PDF
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Respon Admin -->
                    <?php if ($tunggakan['status'] != 'pending'): ?>
                        <div class="card" style="border: 2px solid 
                            <?= $tunggakan['status'] == 'approved' ? '#28a745' : '#dc3545' ?>">
                            <div class="card-header" style="background-color: 
                                <?= $tunggakan['status'] == 'approved' ? '#28a745' : '#dc3545' ?>">
                                <h3 class="card-title text-white">
                                    <i class="fas fa-user-shield"></i> Respon dari Admin
                                </h3>
                            </div>
                            <div class="card-body">
                                <?php if ($tunggakan['nama_admin']): ?>
                                    <p class="mb-2">
                                        <i class="fas fa-user"></i> <strong>Diproses oleh:</strong> <?= esc($tunggakan['nama_admin']) ?>
                                    </p>
                                    <p class="mb-3">
                                        <i class="fas fa-clock"></i> <strong>Tanggal:</strong> <?= date('d F Y, H:i', strtotime($tunggakan['tanggal_diproses'])) ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ($tunggakan['catatan_admin']): ?>
                                    <hr>
                                    <label class="mb-2"><i class="fas fa-comment"></i> Catatan Admin:</label>
                                    <div class="alert alert-<?= $tunggakan['status'] == 'approved' ? 'success' : 'danger' ?>">
                                        <?= nl2br(esc($tunggakan['catatan_admin'])) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($tunggakan['status'] == 'approved'): ?>
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i> 
                                        <strong>Selamat!</strong> Pengajuan tunggakan Anda telah disetujui. 
                                        Silakan lakukan pembayaran sebelum tanggal jatuh tempo yang baru.
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-danger">
                                        <i class="fas fa-times-circle"></i> 
                                        <strong>Mohon maaf,</strong> pengajuan tunggakan Anda ditolak. 
                                        Anda dapat mengajukan kembali dengan perbaikan atau melakukan pembayaran sesuai jadwal awal.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Info Pending -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-hourglass-half"></i> Status Proses</h3>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <h5><i class="fas fa-clock"></i> Pengajuan Sedang Diproses</h5>
                                    <p class="mb-0">
                                        Pengajuan tunggakan Anda sedang ditinjau oleh admin. 
                                        Proses review biasanya memakan waktu maksimal <strong>3 hari kerja</strong>. 
                                        Anda akan mendapat notifikasi hasil melalui sistem.
                                    </p>
                                </div>
                                <ul class="timeline">
                                    <li>
                                        <i class="fas fa-check bg-success"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header">Pengajuan Terkirim</h3>
                                        </div>
                                    </li>
                                    <li>
                                        <i class="fas fa-spinner fa-spin bg-warning"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header">Sedang Ditinjau Admin</h3>
                                        </div>
                                    </li>
                                    <li>
                                        <i class="fas fa-clock bg-gray"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header text-muted">Menunggu Keputusan</h3>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="<?= base_url('siswa/tunggakan') ?>" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                            </a>

                            <?php if ($tunggakan['status'] == 'pending'): ?>
                                <button type="button" class="btn btn-danger btn-lg float-right" onclick="confirmCancel()">
                                    <i class="fas fa-ban"></i> Batalkan Pengajuan
                                </button>
                            <?php endif; ?>

                            <?php if ($tunggakan['status'] == 'approved' && $tunggakan['status_bayar'] != 'lunas'): ?>
                                <a href="<?= base_url('siswa/pembayaran-lain') ?>" class="btn btn-success btn-lg float-right">
                                    <i class="fas fa-money-bill-wave"></i> Bayar Sekarang
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
function confirmCancel() {
    if (confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?\n\nPengajuan yang dibatalkan tidak dapat dikembalikan dan Anda harus mengajukan ulang dari awal.')) {
        window.location.href = '<?= base_url('siswa/tunggakan/cancel/' . $tunggakan['id']) ?>';
    }
}
</script>

<style>
.opacity-50 {
    opacity: 0.3;
}

.timeline {
    position: relative;
    margin: 0 0 30px 0;
    padding: 0;
    list-style: none;
}

.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #ddd;
    left: 31px;
    margin: 0;
    border-radius: 2px;
}

.timeline > div {
    margin-bottom: 15px;
    position: relative;
}

.timeline > div > .timeline-item {
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    border-radius: 3px;
    margin-top: 0;
    background: #fff;
    color: #495057;
    margin-left: 60px;
    margin-right: 15px;
    padding: 10px;
    position: relative;
}

.timeline > div > .fa,
.timeline > div > .fas,
.timeline > div > .far {
    width: 30px;
    height: 30px;
    font-size: 15px;
    line-height: 30px;
    position: absolute;
    color: #fff;
    background: #999;
    border-radius: 50%;
    text-align: center;
    left: 18px;
    top: 0;
}

.timeline-header {
    margin: 0;
    color: #495057;
    border-bottom: 1px solid #dee2e6;
    padding: 5px 0;
    font-size: 16px;
    line-height: 1.1;
}

.timeline-body {
    padding: 10px 0;
}
</style>

<?= $this->endSection() ?>