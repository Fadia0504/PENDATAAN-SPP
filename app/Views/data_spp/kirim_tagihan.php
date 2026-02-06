<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Kirim Tagihan SPP<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Kirim Tagihan SPP</h1>

<!-- Form Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success">Form Kirim Tagihan</h6>
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

        <!-- Info Box -->
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> <strong>Info:</strong><br>
            Tagihan akan dikirim dengan nominal: <strong>Rp <?= number_format($spp['nominal'], 0, ',', '.') ?></strong> per siswa<br>
            Tahun Ajaran: <strong><?= $spp['tahun'] ?></strong>
        </div>

        <form method="post" action="<?= base_url('data-spp/proses-kirim-tagihan') ?>">
            <?= csrf_field() ?>
            
            <input type="hidden" name="id_spp" value="<?= $spp['id'] ?>">

            <div class="form-group">
                <label>Kirim Tagihan Ke <span class="text-danger">*</span></label>
                <select class="form-control" name="tipe_tagihan" id="tipe_tagihan" required>
                    <option value="">-- Pilih --</option>
                    <option value="semua">Semua Siswa</option>
                    <option value="kelas">Per Kelas</option>
                    <option value="individu">Per Siswa</option>
                </select>
            </div>

            <!-- Pilihan Kelas (Hidden by default) -->
            <div class="form-group" id="kelasField" style="display: none;">
                <label>Pilih Kelas <span class="text-danger">*</span></label>
                <select class="form-control" name="id_kelas">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($kelas as $k): ?>
                        <option value="<?= $k['id'] ?>">
                            <?= $k['nama_kelas'] ?> - <?= $k['kompetensi_keahlian'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Pilihan Siswa (Hidden by default) -->
            <div class="form-group" id="siswaField" style="display: none;">
                <label>Pilih Siswa <span class="text-danger">*</span></label>
                <select class="form-control" name="id_siswa">
                    <option value="">-- Pilih Siswa --</option>
                    <?php foreach ($siswa as $s): ?>
                        <option value="<?= $s['id'] ?>">
                            <?= $s['nis'] ?> - <?= $s['nama'] ?> (<?= $s['nama_kelas'] ?? '-' ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bulan">Bulan <span class="text-danger">*</span></label>
                        <select class="form-control" id="bulan" name="bulan" required>
                            <option value="">-- Pilih Bulan --</option>
                            <option value="Januari">Januari</option>
                            <option value="Februari">Februari</option>
                            <option value="Maret">Maret</option>
                            <option value="April">April</option>
                            <option value="Mei">Mei</option>
                            <option value="Juni">Juni</option>
                            <option value="Juli">Juli</option>
                            <option value="Agustus">Agustus</option>
                            <option value="September">September</option>
                            <option value="Oktober">Oktober</option>
                            <option value="November">November</option>
                            <option value="Desember">Desember</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tahun">Tahun <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="tahun" name="tahun" 
                               value="<?= date('Y') ?>" min="2020" max="2030" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane"></i> Kirim Tagihan
                </button>
                <a href="<?= base_url('data-spp') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Panduan -->
<div class="card shadow mb-4 border-left-info">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info">Panduan Kirim Tagihan</h6>
    </div>
    <div class="card-body">
        <ol>
            <li>Pilih tujuan tagihan (Semua Siswa, Per Kelas, atau Per Siswa)</li>
            <li>Pilih bulan dan tahun tagihan</li>
            <li>Tentukan tanggal jatuh tempo pembayaran</li>
            <li>Klik "Kirim Tagihan"</li>
            <li>Tagihan akan otomatis masuk ke akun siswa</li>
        </ol>
        <div class="alert alert-warning mb-0">
            <i class="fas fa-exclamation-triangle"></i> <strong>Perhatian:</strong>
            Sistem akan mencegah tagihan duplikat untuk bulan yang sama!
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Show/hide field berdasarkan tipe tagihan
document.getElementById('tipe_tagihan').addEventListener('change', function() {
    const tipe = this.value;
    const kelasField = document.getElementById('kelasField');
    const siswaField = document.getElementById('siswaField');
    
    // Hide all
    kelasField.style.display = 'none';
    siswaField.style.display = 'none';
    
    // Show based on selection
    if (tipe === 'kelas') {
        kelasField.style.display = 'block';
        kelasField.querySelector('select').required = true;
        siswaField.querySelector('select').required = false;
    } else if (tipe === 'individu') {
        siswaField.style.display = 'block';
        siswaField.querySelector('select').required = true;
        kelasField.querySelector('select').required = false;
    } else {
        kelasField.querySelector('select').required = false;
        siswaField.querySelector('select').required = false;
    }
});
</script>
<?= $this->endSection() ?>