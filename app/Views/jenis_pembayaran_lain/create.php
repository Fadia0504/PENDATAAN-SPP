<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Tambah Jenis Pembayaran<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Tambah Jenis Pembayaran Lain</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Tambah Jenis Pembayaran</h6>
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

        <form method="post" action="<?= base_url('jenis-pembayaran-lain/store') ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="nama_pembayaran">Nama Pembayaran <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_pembayaran" name="nama_pembayaran" 
                       placeholder="Contoh: Study Tour 2025" value="<?= old('nama_pembayaran') ?>" required>
            </div>

            <div class="form-group">
                <label for="kategori">Kategori <span class="text-danger">*</span></label>
                <select class="form-control" id="kategori" name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="kegiatan" <?= old('kategori') == 'kegiatan' ? 'selected' : '' ?>>Kegiatan</option>
                    <option value="seragam" <?= old('kategori') == 'seragam' ? 'selected' : '' ?>>Seragam</option>
                    <option value="buku" <?= old('kategori') == 'buku' ? 'selected' : '' ?>>Buku</option>
                    <option value="praktek" <?= old('kategori') == 'praktek' ? 'selected' : '' ?>>Praktek</option>
                    <option value="lainnya" <?= old('kategori') == 'lainnya' ? 'selected' : '' ?>>Lainnya</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nominal">Nominal <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="nominal" name="nominal" 
                       placeholder="Contoh: 2000000" value="<?= old('nominal') ?>" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" 
                          placeholder="Deskripsi pembayaran..."><?= old('deskripsi') ?></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?= base_url('jenis-pembayaran-lain') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>