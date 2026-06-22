<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Tambah Siswa Baru</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('siswa') ?>">Siswa</a> / Tambah
        </div>
    </div>
</div>

<div class="card" style="max-width:650px;">
    <div class="card-header">
        <div class="card-title"><i class="ri-add-circle-line" style="color:var(--primary);"></i> Form Tambah Siswa</div>
    </div>
    <div style="padding:25px;">
        <form method="post" action="<?= base_url('siswa/simpan') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">NISN <span class="required">*</span></label>
                    <input type="text" name="nisn" class="form-control" placeholder="NISN (10 digit)" value="<?= old('nisn') ?>" required maxlength="10" inputmode="numeric" pattern="[0-9]{10}">
                    <?php if (isset($errors['nisn'])): ?>
                        <small style="color:var(--danger);"><?= $errors['nisn'] ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="L" <?= old('jenis_kelamin') == 'L' ? 'selected':'' ?>>Laki-laki</option>
                        <option value="P" <?= old('jenis_kelamin') == 'P' ? 'selected':'' ?>>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Siswa <span class="required">*</span></label>
                <input type="text" name="nama_siswa" class="form-control" placeholder="Nama lengkap siswa" value="<?= old('nama_siswa') ?>" required maxlength="100">
                <?php if (isset($errors['nama_siswa'])): ?>
                    <small style="color:var(--danger);"><?= $errors['nama_siswa'] ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Kelas <span class="required">*</span></label>
                <select name="id_kelas" class="form-control" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($kelas_list as $k): ?>
                    <option value="<?= $k['id_kelas'] ?>" <?= old('id_kelas') == $k['id_kelas'] ? 'selected':'' ?>>
                        <?= esc($k['nama_kelas']) ?> (<?= esc($k['nama_jurusan'] ?? '') ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" value="<?= old('no_hp') ?>" maxlength="15">
                </div>
                <div class="form-group">
                    <label class="form-label">Foto (Opsional)</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" style="padding:7px;">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap siswa"><?= old('alamat') ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Simpan</button>
                <a href="<?= base_url('siswa') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'    => 'Tambah Siswa',
    'subtitle' => 'Master Data Siswa',
    'content'  => $content,
]);
?>
