<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Tambah Data Guru<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-user-plus"></i> <?= $title ?>
    </h1>
    <a href="<?= base_url('guru') ?>" class="btn btn-secondary btn-icon-split">
        <span class="icon text-white-50">
            <i class="fas fa-arrow-left"></i>
        </span>
        <span class="text">Kembali</span>
    </a>
</div>

<!-- Form Card -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Tambah Guru</h6>
            </div>
            <div class="card-body">
                <form action="<?= base_url('guru/store') ?>" method="post" enctype="multipart/form-data" id="formGuru">
                    <?= csrf_field() ?>

                    <!-- Foto Preview -->
                    <div class="row mb-4">
                        <div class="col-md-12 text-center">
                            <div class="mb-3">
                                <img id="preview-foto" src="<?= base_url('assets/img/undraw_profile.svg') ?>" 
                                     class="rounded-circle border" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                            <div class="custom-file" style="max-width: 400px; margin: 0 auto;">
                                <input type="file" class="custom-file-input" id="foto" name="foto" accept="image/*">
                                <label class="custom-file-label" for="foto">Pilih Foto</label>
                            </div>
                            <small class="form-text text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                            <?php if (isset($errors['foto'])): ?>
                                <div class="text-danger"><?= $errors['foto'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr>

                    <!-- Data Identitas -->
                    <h5 class="mb-3"><i class="fas fa-id-card"></i> Data Identitas</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nip">NIP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= isset($errors['nip']) ? 'is-invalid' : '' ?>" 
                                       id="nip" name="nip" value="<?= old('nip') ?>" required>
                                <?php if (isset($errors['nip'])): ?>
                                    <div class="invalid-feedback"><?= $errors['nip'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>" 
                                       id="nama" name="nama" value="<?= old('nama') ?>" required>
                                <?php if (isset($errors['nama'])): ?>
                                    <div class="invalid-feedback"><?= $errors['nama'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gelar">Gelar</label>
                                <input type="text" class="form-control" id="gelar" name="gelar" 
                                       placeholder="S.Pd, M.Pd, dll" value="<?= old('gelar') ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-control <?= isset($errors['jenis_kelamin']) ? 'is-invalid' : '' ?>" 
                                        id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L" <?= old('jenis_kelamin') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="P" <?= old('jenis_kelamin') == 'P' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                                <?php if (isset($errors['jenis_kelamin'])): ?>
                                    <div class="invalid-feedback"><?= $errors['jenis_kelamin'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tempat_lahir">Tempat Lahir</label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" 
                                       value="<?= old('tempat_lahir') ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
                                       value="<?= old('tanggal_lahir') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= old('alamat') ?></textarea>
                    </div>

                    <hr>

                    <!-- Kontak -->
                    <h5 class="mb-3"><i class="fas fa-phone"></i> Kontak</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                       id="email" name="email" value="<?= old('email') ?>">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_telepon">No. Telepon</label>
                                <input type="text" class="form-control <?= isset($errors['no_telepon']) ? 'is-invalid' : '' ?>" 
                                       id="no_telepon" name="no_telepon" value="<?= old('no_telepon') ?>">
                                <?php if (isset($errors['no_telepon'])): ?>
                                    <div class="invalid-feedback"><?= $errors['no_telepon'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Data Kepegawaian -->
                    <h5 class="mb-3"><i class="fas fa-briefcase"></i> Data Kepegawaian</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mata_pelajaran">Mata Pelajaran</label>
                                <input type="text" class="form-control" id="mata_pelajaran" name="mata_pelajaran" 
                                       value="<?= old('mata_pelajaran') ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pendidikan_terakhir">Pendidikan Terakhir</label>
                                <select class="form-control" id="pendidikan_terakhir" name="pendidikan_terakhir">
                                    <option value="">-- Pilih --</option>
                                    <option value="D3" <?= old('pendidikan_terakhir') == 'D3' ? 'selected' : '' ?>>D3</option>
                                    <option value="S1" <?= old('pendidikan_terakhir') == 'S1' ? 'selected' : '' ?>>S1</option>
                                    <option value="S2" <?= old('pendidikan_terakhir') == 'S2' ? 'selected' : '' ?>>S2</option>
                                    <option value="S3" <?= old('pendidikan_terakhir') == 'S3' ? 'selected' : '' ?>>S3</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_masuk">Tanggal Masuk</label>
                                <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" 
                                       value="<?= old('tanggal_masuk') ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="aktif" <?= old('status') == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="nonaktif" <?= old('status') == 'nonaktif' ? 'selected' : '' ?>>Non-Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Submit Buttons -->
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Data
                        </button>
                        <a href="<?= base_url('guru') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Preview foto
    $('#foto').on('change', function(e) {
        const file = e.target.files[0];
        const label = $(this).next('.custom-file-label');
        
        if (file) {
            label.text(file.name);
            
            // Show preview
            if (file.type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-foto').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    // Form validation
    $('#formGuru').on('submit', function(e) {
        const foto = $('#foto')[0].files[0];
        
        if (foto && foto.size > 2097152) { // 2MB
            e.preventDefault();
            alert('Ukuran foto maksimal 2MB!');
            return false;
        }
    });
});
</script>
<?= $this->endSection() ?>