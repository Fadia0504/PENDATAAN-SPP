<?= $this->extend('layout/admin') ?>

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
                        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('tunggakan-transaksi') ?>">Transaksi Tunggakan</a></li>
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
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Info Siswa & Tagihan -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fas fa-user"></i> Informasi Siswa</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">NIS</th>
                                    <td>: <?= esc($tunggakan['nis']) ?></td>
                                </tr>
                                <tr>
                                    <th>Nama Siswa</th>
                                    <td>: <?= esc($tunggakan['nama_siswa']) ?></td>
                                </tr>
                                <tr>
                                    <th>Kelas</th>
                                    <td>: <?= esc($tunggakan['nama_kelas']) ?></td>
                                </tr>
                                <tr>
                                    <th>No. Telepon</th>
                                    <td>: <?= esc($tunggakan['telp_siswa'] ?? '-') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-info">
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
                                    <td>: 
                                        <span class="badge badge-secondary"><?= esc(ucfirst($tunggakan['kategori'])) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jumlah Tagihan</th>
                                    <td>: <strong class="text-danger">Rp <?= number_format($tunggakan['jumlah_tagihan'], 0, ',', '.') ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Status Bayar</th>
                                    <td>: 
                                        <?php if ($tunggakan['status_bayar'] == 'lunas'): ?>
                                            <span class="badge badge-success">Lunas</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Belum Bayar</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Info Pengajuan -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header" style="background-color: 
                            <?= $tunggakan['status'] == 'pending' ? '#ffc107' : 
                                ($tunggakan['status'] == 'approved' ? '#28a745' : '#dc3545') ?>">
                            <h3 class="card-title text-white">
                                <i class="fas fa-clock"></i> Status Pengajuan
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Tanggal Pengajuan</th>
                                    <td>: <?= date('d/m/Y H:i', strtotime($tunggakan['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>: 
                                        <?php if ($tunggakan['status'] == 'pending'): ?>
                                            <span class="badge badge-warning badge-lg">Menunggu Persetujuan</span>
                                        <?php elseif ($tunggakan['status'] == 'approved'): ?>
                                            <span class="badge badge-success badge-lg">Disetujui</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger badge-lg">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jatuh Tempo Lama</th>
                                    <td>: <strong><?= date('d/m/Y', strtotime($tunggakan['tanggal_jatuh_tempo_lama'])) ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Jatuh Tempo Baru</th>
                                    <td>: 
                                        <?php if ($tunggakan['tanggal_jatuh_tempo_baru']): ?>
                                            <strong class="text-success"><?= date('d/m/Y', strtotime($tunggakan['tanggal_jatuh_tempo_baru'])) ?></strong>
                                            <?php if ($tunggakan['status'] == 'approved'): ?>
                                                <button type="button" class="btn btn-sm btn-primary ml-2" 
                                                        data-toggle="modal" data-target="#editJatuhTempoModal">
                                                    <i class="fas fa-edit"></i> Ubah
                                                </button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Belum ditentukan</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if ($tunggakan['tanggal_diproses']): ?>
                                    <tr>
                                        <th>Diproses Tanggal</th>
                                        <td>: <?= date('d/m/Y H:i', strtotime($tunggakan['tanggal_diproses'])) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Diproses Oleh</th>
                                        <td>: <?= esc($tunggakan['nama_admin']) ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>

                    <!-- Alasan Siswa -->
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h3 class="card-title"><i class="fas fa-comment-alt"></i> Alasan Pengajuan</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-justify"><?= nl2br(esc($tunggakan['alasan'])) ?></p>
                        </div>
                    </div>

                    <!-- Bukti Pendukung -->
                    <?php if ($tunggakan['bukti_pendukung']): ?>
                        <div class="card">
                            <div class="card-header bg-secondary">
                                <h3 class="card-title"><i class="fas fa-paperclip"></i> Bukti Pendukung</h3>
                            </div>
                            <div class="card-body text-center">
                                <?php 
                                $ext = pathinfo($tunggakan['bukti_pendukung'], PATHINFO_EXTENSION);
                                $buktiPath = base_url('uploads/bukti_tunggakan/' . $tunggakan['bukti_pendukung']);
                                ?>
                                <?php if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])): ?>
                                    <img src="<?= $buktiPath ?>" class="img-fluid" style="max-height: 400px">
                                <?php else: ?>
                                    <a href="<?= $buktiPath ?>" target="_blank" class="btn btn-primary btn-lg">
                                        <i class="fas fa-file-pdf"></i> Lihat File PDF
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Catatan Admin -->
                    <?php if ($tunggakan['catatan_admin']): ?>
                        <div class="card">
                            <div class="card-header" style="background-color: 
                                <?= $tunggakan['status'] == 'approved' ? '#28a745' : '#dc3545' ?>">
                                <h3 class="card-title text-white"><i class="fas fa-comment"></i> Catatan Admin</h3>
                            </div>
                            <div class="card-body">
                                <p class="text-justify"><?= nl2br(esc($tunggakan['catatan_admin'])) ?></p>
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
                            <a href="<?= base_url('tunggakan-transaksi') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>

                            <?php if ($tunggakan['status'] == 'pending'): ?>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approveModal">
                                    <i class="fas fa-check"></i> Setujui Pengajuan
                                </button>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                                    <i class="fas fa-times"></i> Tolak Pengajuan
                                </button>
                            <?php endif; ?>

                            <button type="button" class="btn btn-danger float-right" onclick="confirmDelete()">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Modal Approve -->
<div class="modal fade" id="approveModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?= base_url('tunggakan-transaksi/approve/' . $tunggakan['id']) ?>">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">Setujui Pengajuan Tunggakan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Dengan menyetujui pengajuan ini, jatuh tempo tagihan akan diperbarui sesuai tanggal yang Anda tentukan.
                    </div>
                    <div class="form-group">
                        <label>Jatuh Tempo Baru <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_jatuh_tempo_baru" class="form-control" required
                               min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime($tunggakan['tanggal_jatuh_tempo_lama'] . ' +30 days')) ?>">
                        <small class="text-muted">Tentukan tanggal jatuh tempo yang baru</small>
                    </div>
                    <div class="form-group">
                        <label>Catatan Admin (Opsional)</label>
                        <textarea name="catatan_admin" class="form-control" rows="3" 
                                  placeholder="Berikan catatan jika perlu, misal: 'Disetujui dengan jatuh tempo 30 hari'"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?= base_url('tunggakan-transaksi/reject/' . $tunggakan['id']) ?>">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title">Tolak Pengajuan Tunggakan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Pengajuan yang ditolak tidak dapat dikembalikan. Pastikan Anda memberikan alasan yang jelas.
                    </div>
                    <div class="form-group">
                        <label>Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="catatan_admin" class="form-control" rows="4" required 
                                  placeholder="Jelaskan alasan penolakan, misal: 'Bukti tidak memadai' atau 'Siswa tidak memenuhi kriteria'"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Jatuh Tempo -->
<div class="modal fade" id="editJatuhTempoModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?= base_url('tunggakan-transaksi/edit-jatuh-tempo/' . $tunggakan['id']) ?>">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Ubah Jatuh Tempo</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Jatuh Tempo Saat Ini</label>
                        <input type="text" class="form-control" 
                               value="<?= date('d/m/Y', strtotime($tunggakan['tanggal_jatuh_tempo_baru'])) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Jatuh Tempo Baru <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_jatuh_tempo_baru" class="form-control" required 
                               min="<?= date('Y-m-d') ?>" 
                               value="<?= $tunggakan['tanggal_jatuh_tempo_baru'] ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')) {
        window.location.href = '<?= base_url('tunggakan-transaksi/delete/' . $tunggakan['id']) ?>';
    }
}
</script>

<?= $this->endSection() ?>