<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Edit Jenis Pembayaran<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Edit Jenis Pembayaran Lain</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Jenis Pembayaran</h6>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('jenis-pembayaran-lain/update/' . $jenis['id']) ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="nama_pembayaran">Nama Pembayaran <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_pembayaran" name="nama_pembayaran" 
                       value="<?= old('nama_pembayaran', $jenis['nama_pembayaran']) ?>" required>
            </div>

            <div class="form-group">
                <label for="kategori">Kategori <span class="text-danger">*</span></label>
                <select class="form-control" id="kategori" name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="kegiatan" <?= old('kategori', $jenis['kategori']) == 'kegiatan' ? 'selected' : '' ?>>Kegiatan</option>
                    <option value="seragam" <?= old('kategori', $jenis['kategori']) == 'seragam' ? 'selected' : '' ?>>Seragam</option>
                    <option value="buku" <?= old('kategori', $jenis['kategori']) == 'buku' ? 'selected' : '' ?>>Buku</option>
                    <option value="praktek" <?= old('kategori', $jenis['kategori']) == 'praktek' ? 'selected' : '' ?>>Praktek</option>
                    <option value="lainnya" <?= old('kategori', $jenis['kategori']) == 'lainnya' ? 'selected' : '' ?>>Lainnya</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nominal">Nominal <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="nominal" name="nominal" 
                       value="<?= old('nominal', $jenis['nominal']) ?>" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?= old('deskripsi', $jenis['deskripsi']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select class="form-control" id="status" name="status" required>
                    <option value="aktif" <?= old('status', $jenis['status']) == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= old('status', $jenis['status']) == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="<?= base_url('jenis-pembayaran-lain') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>