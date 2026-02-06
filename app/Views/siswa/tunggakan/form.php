<?= $this->extend('layout/siswa') ?>

<?= $this->section('title') ?>
Ajukan Tunggakan
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">
            Form Pengajuan Tunggakan
        </h6>
    </div>

    <div class="card-body">

        <!-- Flash message -->
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')) : ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('siswa/tunggakan/submit') ?>" 
              method="post" 
              enctype="multipart/form-data">

            <?= csrf_field() ?>

            <!-- Pilih Tagihan -->
            <div class="form-group">
                <label for="id_tagihan">Tagihan</label>
                <select name="tagihan" class="form-control" required>
                    <option value="">-- Pilih Tagihan --</option>
                    <?php foreach ($tagihan as $t): ?>
                        <option value="<?= $t['jenis'] ?>_<?= $t['id'] ?>">
                            <?= esc($t['nama_pembayaran']) ?>
                            - Rp <?= number_format($t['jumlah_tagihan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            </div>

            <!-- Alasan -->
            <div class="form-group">
                <label for="alasan">Alasan Pengajuan</label>
                <textarea name="alasan" id="alasan" rows="5" 
                          class="form-control" required><?= old('alasan') ?></textarea>
                <small class="text-muted">
                    Minimal 20 karakter, jelaskan kondisi Anda dengan jelas.
                </small>
            </div>

            <!-- Bukti Pendukung -->
            <div class="form-group">
                <label for="bukti_pendukung">
                    Bukti Pendukung (Opsional)
                </label>
                <input type="file" name="bukti_pendukung" 
                       class="form-control-file"
                       accept=".jpg,.jpeg,.png,.pdf">
                <small class="text-muted">
                    JPG, PNG, atau PDF (maks. 2MB)
                </small>
            </div>

            <div class="text-right">
                <a href="<?= base_url('siswa/tunggakan') ?>" 
                   class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    Kirim Pengajuan
                </button>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>
