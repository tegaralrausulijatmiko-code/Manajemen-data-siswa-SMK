<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Edit Data Siswa</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('siswa') ?>">Siswa</a> / Edit
        </div>
    </div>
</div>

<div class="card" style="max-width:650px;">
    <div class="card-header">
        <div class="card-title"><i class="ri-edit-line" style="color:var(--primary);"></i> Form Edit Siswa</div>
    </div>
    <div style="padding:25px;">
        <form method="post" action="<?= base_url('siswa/update/' . $siswa['id_siswa']) ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <input type="hidden" name="redirect_to" value="<?= esc($id_kelas ?? '') ?>">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">NISN <span class="required">*</span></label>
                    <input type="text" name="nisn" class="form-control" placeholder="NISN (10 digit)"   value="<?= esc($siswa['nisn']) ?>" required maxlength="10" inputmode="numeric" pattern="[0-9]{10}">
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="L" <?= $siswa['jenis_kelamin'] == 'L' ? 'selected':'' ?>>Laki-laki</option>
                        <option value="P" <?= $siswa['jenis_kelamin'] == 'P' ? 'selected':'' ?>>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Siswa <span class="required">*</span></label>
                <input type="text" name="nama_siswa" class="form-control" value="<?= esc($siswa['nama_siswa']) ?>" required maxlength="100">
            </div>

            <div class="form-group">
                <label class="form-label">Kelas <span class="required">*</span></label>
                <select name="id_kelas" class="form-control" required>
                    <?php foreach ($kelas_list as $k): ?>
                    <option value="<?= $k['id_kelas'] ?>" <?= $siswa['id_kelas'] == $k['id_kelas'] ? 'selected':'' ?>>
                        <?= esc($k['nama_kelas']) ?> (<?= esc($k['nama_jurusan'] ?? '') ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= esc($siswa['no_hp'] ?? '') ?>" maxlength="15">
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Baru (Opsional)</label>
                    <?php if (!empty($siswa['foto'])): ?>
                        <div style="margin-bottom:6px;">
                            <img src="<?= base_url('uploads/' . $siswa['foto']) ?>" style="height:40px; border-radius:4px;" alt="foto">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="foto" class="form-control" accept="image/*" style="padding:7px;">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3"><?= esc($siswa['alamat'] ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Update</button>
                <a href="<?= base_url('siswa') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'    => 'Edit Siswa',
    'subtitle' => 'Master Data Siswa',
    'content'  => $content,
]);
?>
