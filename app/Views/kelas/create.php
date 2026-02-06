<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Tambah Kelas<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Tambah Data Kelas</h1>

<!-- Form Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Tambah Kelas</h6>
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

        <form method="post" action="<?= base_url('kelas/store') ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="nama_kelas">Nama Kelas <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" 
                       placeholder="Contoh: X RPL 1" value="<?= old('nama_kelas') ?>" required>
                <small class="form-text text-muted">Format: Tingkat + Jurusan + Nomor Kelas</small>
            </div>

            <div class="form-group">
                <label for="kompetensi_keahlian">Kompetensi Keahlian <span class="text-danger">*</span></label>
                <select class="form-control" id="kompetensi_keahlian" name="kompetensi_keahlian" required>
                    <option value="">-- Pilih Kompetensi Keahlian --</option>
                    <option value="Rekayasa Perangkat Lunak" <?= old('kompetensi_keahlian') == 'Rekayasa Perangkat Lunak' ? 'selected' : '' ?>>Rekayasa Perangkat Lunak</option>
                    <option value="Teknik Komputer dan Jaringan" <?= old('kompetensi_keahlian') == 'Teknik Komputer dan Jaringan' ? 'selected' : '' ?>>Teknik Komputer dan Jaringan</option>
                    <option value="Multimedia" <?= old('kompetensi_keahlian') == 'Multimedia' ? 'selected' : '' ?>>Multimedia</option>
                    <option value="Teknik Elektronika Industri" <?= old('kompetensi_keahlian') == 'Teknik Elektronika Industri' ? 'selected' : '' ?>>Teknik Elektronika Industri</option>
                    <option value="Teknik Mesin" <?= old('kompetensi_keahlian') == 'Teknik Mesin' ? 'selected' : '' ?>>Teknik Mesin</option>
                    <option value="Teknik Kendaraan Ringan" <?= old('kompetensi_keahlian') == 'Teknik Kendaraan Ringan' ? 'selected' : '' ?>>Teknik Kendaraan Ringan</option>
                    <option value="Akuntansi" <?= old('kompetensi_keahlian') == 'Akuntansi' ? 'selected' : '' ?>>Akuntansi</option>
                    <option value="Administrasi Perkantoran" <?= old('kompetensi_keahlian') == 'Administrasi Perkantoran' ? 'selected' : '' ?>>Administrasi Perkantoran</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?= base_url('kelas') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>