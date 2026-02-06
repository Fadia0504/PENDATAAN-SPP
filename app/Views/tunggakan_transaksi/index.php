<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= esc($title) ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <!-- Alert -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-check-circle"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Statistik -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <span class="badge badge-warning badge-lg mr-2">
                        <i class="fas fa-clock"></i> Pending: <?= $statistik['pending'] ?>
                    </span>
                    <span class="badge badge-success badge-lg mr-2">
                        <i class="fas fa-check"></i> Disetujui: <?= $statistik['approved'] ?>
                    </span>
                    <span class="badge badge-danger badge-lg mr-2">
                        <i class="fas fa-times"></i> Ditolak: <?= $statistik['rejected'] ?>
                    </span>
                    <span class="badge badge-info badge-lg">
                        <i class="fas fa-list"></i> Total: <?= $statistik['total'] ?>
                    </span>
                </div>
            </div>

            <!-- Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i> Daftar Pengajuan Tunggakan
                    </h3>

                    <div class="card-tools">
                        <form method="get" action="<?= base_url('tunggakan-transaksi') ?>">
                            <div class="input-group input-group-sm">
                                <select name="status" class="form-control mr-1">
                                    <option value="">Semua Status</option>
                                    <option value="pending" <?= $filter == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="approved" <?= $filter == 'approved' ? 'selected' : '' ?>>Disetujui</option>
                                    <option value="rejected" <?= $filter == 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                                </select>
                                <button class="btn btn-primary">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <?php if ($filter): ?>
                                    <a href="<?= base_url('tunggakan-transaksi') ?>" class="btn btn-secondary ml-1">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tableTunggakan">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tgl Ajuan</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jenis Pembayaran</th>
                                    <th>Jumlah</th>
                                    <th>Tempo Lama</th>
                                    <th>Tempo Baru</th>
                                    <th>Status</th>
                                    <th width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($tunggakan)): ?>
                                <tr>
                                    <td colspan="11" class="text-center text-muted">
                                        Tidak ada pengajuan tunggakan
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tunggakan as $i => $row): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                                    <td><?= esc($row['nis']) ?></td>
                                    <td><?= esc($row['nama_siswa']) ?></td>
                                    <td><?= esc($row['nama_kelas']) ?></td>
                                    <td><?= esc($row['nama_pembayaran']) ?></td>
                                    <td class="text-right">
                                        Rp <?= number_format($row['jumlah_tagihan'], 0, ',', '.') ?>
                                    </td>
                                    <td class="text-center">
                                        <?= date('d/m/Y', strtotime($row['tanggal_jatuh_tempo_lama'])) ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $row['tanggal_jatuh_tempo_baru']
                                            ? date('d/m/Y', strtotime($row['tanggal_jatuh_tempo_baru']))
                                            : '-' ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-<?= 
                                            $row['status'] == 'pending' ? 'warning' :
                                            ($row['status'] == 'approved' ? 'success' : 'danger') ?>">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= base_url('tunggakan-transaksi/detail/'.$row['id']) ?>" class="btn btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <?php if ($row['status'] == 'pending'): ?>
                                                <button class="btn btn-success" data-toggle="modal" data-target="#approveModal<?= $row['id'] ?>">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#rejectModal<?= $row['id'] ?>">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>

                                            <button class="btn btn-danger" onclick="confirmDelete(<?= $row['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                            <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function () {
    $('#tableTunggakan').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[1, 'desc']],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});

function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus data ini?')) {
        window.location.href = "<?= base_url('tunggakan-transaksi/delete/') ?>" + id;
    }
}
</script>
<?= $this->endSection() ?>
