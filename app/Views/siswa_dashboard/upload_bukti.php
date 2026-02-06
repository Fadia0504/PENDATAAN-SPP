<?= $this->extend('layout/siswa') ?>

<?= $this->section('title') ?>Upload Bukti Pembayaran<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Upload Bukti Pembayaran</h1>

<!-- Form Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success">Form Upload Bukti Pembayaran</h6>
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

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Pilih Tipe Pembayaran -->
        <div class="form-group">
            <label>Tipe Pembayaran <span class="text-danger">*</span></label>
            <select class="form-control" id="tipePembayaran" required>
                <option value="">-- Pilih Tipe Pembayaran --</option>
                <option value="spp" <?= $tipe_tagihan == 'spp' ? 'selected' : '' ?>>SPP</option>
                <option value="lain" <?= $tipe_tagihan == 'lain' ? 'selected' : '' ?>>Pembayaran Lain</option>
            </select>
        </div>

        <!-- Form SPP -->
        <form method="post" action="<?= base_url('siswa/proses-upload-spp') ?>" enctype="multipart/form-data" id="formSpp" style="display: <?= $tipe_tagihan == 'spp' ? 'block' : 'none' ?>;">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="id_tagihan_spp">Pilih Tagihan SPP <span class="text-danger">*</span></label>
                <select class="form-control" id="id_tagihan_spp" name="id_tagihan" required>
                    <option value="">-- Pilih Tagihan --</option>
                    <?php foreach ($tagihan_spp as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= $selected_tagihan && $selected_tagihan['id'] == $t['id'] ? 'selected' : '' ?>>
                            <?= $t['bulan'] ?> <?= $t['tahun'] ?> - Rp <?= number_format($t['jumlah_tagihan'], 0, ',', '.') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="tanggal_bayar">Tanggal Pembayaran <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="tanggal_bayar" name="tanggal_bayar" 
                       value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-group">
                <label for="bukti_pembayaran">Upload Bukti Pembayaran <span class="text-danger">*</span></label>
                <input type="file" class="form-control-file" id="bukti_pembayaran" name="bukti_pembayaran" 
                       accept=".jpg,.jpeg,.png,.pdf" required>
                <small class="form-text text-muted">Format: JPG, JPEG, PNG, atau PDF. Maksimal 2MB</small>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan (Opsional)</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                          placeholder="Contoh: Dibayar melalui transfer Bank BCA"></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-upload"></i> Upload Bukti SPP
                </button>
                <a href="<?= base_url('siswa/dashboard') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>

        <!-- Form Pembayaran Lain -->
        <form method="post" action="<?= base_url('siswa/proses-upload-lain') ?>" enctype="multipart/form-data" id="formLain" style="display: <?= $tipe_tagihan == 'lain' ? 'block' : 'none' ?>;">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="id_tagihan_lain">Pilih Tagihan <span class="text-danger">*</span></label>
                <select class="form-control" id="id_tagihan_lain" name="id_tagihan" required>
                    <option value="">-- Pilih Tagihan --</option>
                    <?php foreach ($tagihan_lain as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= $selected_tagihan && $selected_tagihan['id'] == $t['id'] ? 'selected' : '' ?>>
                            <?= $t['nama_pembayaran'] ?> - Rp <?= number_format($t['jumlah_tagihan'], 0, ',', '.') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="tanggal_bayar_lain">Tanggal Pembayaran <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="tanggal_bayar_lain" name="tanggal_bayar" 
                       value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-group">
                <label for="bukti_pembayaran_lain">Upload Bukti Pembayaran <span class="text-danger">*</span></label>
                <input type="file" class="form-control-file" id="bukti_pembayaran_lain" name="bukti_pembayaran" 
                       accept=".jpg,.jpeg,.png,.pdf" required>
                <small class="form-text text-muted">Format: JPG, JPEG, PNG, atau PDF. Maksimal 2MB</small>
            </div>

            <div class="form-group">
                <label for="keterangan_lain">Keterangan (Opsional)</label>
                <textarea class="form-control" id="keterangan_lain" name="keterangan" rows="3" 
                          placeholder="Contoh: Dibayar melalui transfer Bank BCA"></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                </button>
                <a href="<?= base_url('siswa/dashboard') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Toggle form berdasarkan tipe pembayaran
document.getElementById('tipePembayaran').addEventListener('change', function() {
    const tipe = this.value;
    const formSpp = document.getElementById('formSpp');
    const formLain = document.getElementById('formLain');
    
    if (tipe === 'spp') {
        formSpp.style.display = 'block';
        formLain.style.display = 'none';
    } else if (tipe === 'lain') {
        formSpp.style.display = 'none';
        formLain.style.display = 'block';
    } else {
        formSpp.style.display = 'none';
        formLain.style.display = 'none';
    }
});
</script>
<?= $this->endSection() ?>