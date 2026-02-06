<?= $this->extend('layout/siswa') ?>

<?= $this->section('title') ?>
Pengajuan Tunggakan
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pengajuan Tunggakan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('siswa/dashboard') ?>">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Pengajuan Tunggakan</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <!-- Alert -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-check-circle"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Info -->
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5><i class="fas fa-info-circle"></i> Informasi Pengajuan Tunggakan</h5>
                            <ul class="mb-0">
                                <li>Jelaskan alasan minimal 20 karakter</li>
                                <li>Lampiran opsional</li>
                                <li>Diproses maksimal 3 hari kerja</li>
                            </ul>
                        </div>
                        <div class="col-md-4 text-center">
                            <a href="<?= base_url('siswa/tunggakan/form') ?>"
                               class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-plus-circle"></i> Ajukan Tunggakan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i> Riwayat Pengajuan
                    </h3>
                </div>
                <div class="card-body">
                    <?php if (empty($tunggakan)): ?>
                        <div class="alert alert-info text-center">
                            Belum ada pengajuan.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tableTunggakan">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Pembayaran</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($tunggakan as $i => $t): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= date('d M Y', strtotime($t['created_at'])) ?></td>
                                        <td><?= esc($t['nama_pembayaran']) ?></td>
                                        <td>Rp <?= number_format($t['jumlah_tagihan'], 0, ',', '.') ?></td>
                                        <td>
                                            <span class="badge badge-<?= $t['status'] == 'pending' ? 'warning' : 'success' ?>">
                                                <?= ucfirst($t['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('siswa/tunggakan/detail/'.$t['id']) ?>"
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </section>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function () {
    $('#tableTunggakan').DataTable();
});

function confirmCancel(id) {
    if (confirm('Yakin batalkan pengajuan?')) {
        window.location.href =
            '<?= base_url('siswa/tunggakan/cancel/') ?>' + id;
    }
}
</script>
<?= $this->endSection() ?>