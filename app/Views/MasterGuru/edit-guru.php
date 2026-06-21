<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Edit Data Guru</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('guru') ?>">Guru</a> / Edit
        </div>
    </div>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-header">
        <div class="card-title"><i class="ri-edit-line" style="color:var(--primary);"></i> Form Edit Guru</div>
    </div>
    <div style="padding:25px;">
        <form method="post" action="<?= base_url('guru/update/' . $guru['id_guru']) ?>">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">NIP <span class="required">*</span></label>
                    <input type="text" name="nip" class="form-control" placeholder="NIP (18 digit)" value="<?= esc($guru['nip']) ?>" required maxlength="18" inputmode="numeric" pattern="[0-9]{18}">
                    <?php if (isset($errors['nip'])): ?>
                        <small style="color:var(--danger);"><?= $errors['nip'] ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="L" <?= $guru['jenis_kelamin'] == 'L' ? 'selected':'' ?>>Laki-laki</option>
                        <option value="P" <?= $guru['jenis_kelamin'] == 'P' ? 'selected':'' ?>>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Guru <span class="required">*</span></label>
                <input type="text" name="nama_guru" class="form-control" value="<?= esc($guru['nama_guru']) ?>" required maxlength="100">
            </div>

            <div class="form-group">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" class="form-control" value="<?= esc($guru['no_hp'] ?? '') ?>" maxlength="15">
            </div>

            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3"><?= esc($guru['alamat'] ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Update</button>
                <a href="<?= base_url('guru') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'    => 'Edit Guru',
    'subtitle' => 'Master Data Guru',
    'content'  => $content,
]);
?>
