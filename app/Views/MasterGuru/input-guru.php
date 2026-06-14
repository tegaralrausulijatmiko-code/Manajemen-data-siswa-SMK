<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Tambah Guru Baru</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('guru') ?>">Guru</a> / Tambah
        </div>
    </div>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-header">
        <div class="card-title"><i class="ri-add-circle-line" style="color:var(--primary);"></i> Form Tambah Guru</div>
    </div>
    <div style="padding:25px;">
        <form method="post" action="<?= base_url('guru/simpan') ?>">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">NIP <span class="required">*</span></label>
                    <input type="text" name="nip" class="form-control" placeholder="cth: 1987654321" value="<?= old('nip') ?>" required maxlength="20">
                    <?php if (isset($errors['id_guru'])): ?>
                        <small style="color:var(--danger);"><?= $errors['id_guru'] ?></small>
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
                <label class="form-label">Nama Guru <span class="required">*</span></label>
                <input type="text" name="nama_guru" class="form-control" placeholder="Nama lengkap beserta gelar" value="<?= old('nama_guru') ?>" required maxlength="100">
                <?php if (isset($errors['nama_guru'])): ?>
                    <small style="color:var(--danger);"><?= $errors['nama_guru'] ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" value="<?= old('no_hp') ?>" maxlength="15">
            </div>

            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap"><?= old('alamat') ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Simpan</button>
                <a href="<?= base_url('guru') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'    => 'Tambah Guru',
    'subtitle' => 'Master Data Guru',
    'content'  => $content,
]);
?>
