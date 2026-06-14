<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Edit Tahun Ajaran</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / <a href="<?= base_url('tahun-ajaran') ?>">Tahun Ajaran</a> / Edit</div>
    </div>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-header"><div class="card-title"><i class="ri-edit-line" style="color:var(--primary);"></i> Form Tahun Ajaran</div></div>
    <div style="padding:25px;">
        <form method="post" action="<?= base_url('tahun-ajaran/update/' . $tahun['id_tahun_ajaran']) ?>">
            <?= csrf_field() ?>
            <div class="form-group"><label class="form-label">Tahun Ajaran <span class="required">*</span></label><input type="text" name="tahun_ajaran" class="form-control" value="<?= esc($tahun['tahun_ajaran']) ?>" required maxlength="9"></div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Semester <span class="required">*</span></label>
                    <select name="semester" class="form-control" required>
                        <option value="Ganjil" <?= $tahun['semester'] == 'Ganjil' ? 'selected' : '' ?>>Ganjil</option>
                        <option value="Genap" <?= $tahun['semester'] == 'Genap' ? 'selected' : '' ?>>Genap</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="Nonaktif" <?= $tahun['status'] == 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        <option value="Aktif" <?= $tahun['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Update</button>
                <a href="<?= base_url('tahun-ajaran') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', ['title' => 'Edit Tahun Ajaran', 'subtitle' => 'Akademik', 'content' => $content]);
?>
