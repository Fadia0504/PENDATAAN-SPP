<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Kirim Tagihan Pembayaran Lain<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Kirim Tagihan Pembayaran Lain</h1>

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

        <form method="post" action="<?= base_url('jenis-pembayaran-lain/store-tagihan') ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label>Jenis Pembayaran <span class="text-danger">*</span></label>
                <select class="form-control" name="id_jenis_pembayaran" id="id_jenis_pembayaran" required>
                    <option value="">-- Pilih Jenis Pembayaran --</option>
                    <?php foreach ($jenis_pembayaran as $jp): ?>
                        <option value="<?= $jp['id'] ?>" data-nominal="<?= $jp['nominal'] ?>">
                            <?= $jp['nama_pembayaran'] ?> - Rp <?= number_format($jp['nominal'], 0, ',', '.') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="alert alert-info" id="infoNominal" style="display: none;">
                <i class="fas fa-info-circle"></i> <strong>Nominal:</strong> <span id="nominalText"></span>
            </div>

            <div class="form-group">
                <label>Kirim Tagihan Ke <span class="text-danger">*</span></label>
                <select class="form-control" name="tipe_tagihan" id="tipe_tagihan" required>
                    <option value="">-- Pilih --</option>
                    <option value="semua">Semua Siswa</option>
                    <option value="kelas">Per Kelas</option>
                    <option value="individu">Per Siswa</option>
                </select>
            </div>

            <!-- Pilihan Kelas -->
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

            <!-- Pilihan Siswa -->
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

            <div class="form-group">
                <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane"></i> Kirim Tagihan
                </button>
                <a href="<?= base_url('jenis-pembayaran-lain/tagihan') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Show nominal when jenis pembayaran selected
document.getElementById('id_jenis_pembayaran').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const nominal = selected.getAttribute('data-nominal');
    
    if (nominal) {
        document.getElementById('nominalText').textContent = 'Rp ' + parseInt(nominal).toLocaleString('id-ID');
        document.getElementById('infoNominal').style.display = 'block';
    } else {
        document.getElementById('infoNominal').style.display = 'none';
    }
});

// Show/hide field berdasarkan tipe tagihan
document.getElementById('tipe_tagihan').addEventListener('change', function() {
    const tipe = this.value;
    const kelasField = document.getElementById('kelasField');
    const siswaField = document.getElementById('siswaField');
    
    kelasField.style.display = 'none';
    siswaField.style.display = 'none';
    
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