<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Edit Data SPP<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Edit Data SPP</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit SPP</h6>
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

        <form method="post" action="<?= base_url('data-spp/update/' . $spp['id']) ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="tahun">Tahun Ajaran <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="tahun" name="tahun" 
                       value="<?= old('tahun', $spp['tahun']) ?>" required>
            </div>

            <div class="form-group">
                <label for="nominal">Nominal per Bulan <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="nominal" name="nominal" 
                       value="<?= old('nominal', $spp['nominal']) ?>" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="<?= base_url('data-spp') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>