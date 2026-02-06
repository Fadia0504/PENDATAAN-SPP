<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Tambah Data SPP<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Tambah Data SPP</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Tambah SPP</h6>
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

        <form method="post" action="<?= base_url('data-spp/store') ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="tahun">Tahun Ajaran <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="tahun" name="tahun" 
                       placeholder="Contoh: 2024/2025" value="<?= old('tahun') ?>" required>
            </div>

            <div class="form-group">
                <label for="nominal">Nominal per Bulan <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="nominal" name="nominal" 
                       placeholder="Contoh: 500000" value="<?= old('nominal') ?>" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?= base_url('data-spp') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>