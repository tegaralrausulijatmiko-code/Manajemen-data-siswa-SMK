<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Edit Mata Pelajaran</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('mapel') ?>">Mata Pelajaran</a> / Edit
        </div>
    </div>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-header">
        <div class="card-title"><i class="ri-edit-line" style="color:var(--primary);"></i> Form Edit Mata Pelajaran</div>
    </div>
    <div style="padding:25px;">
        <form method="post" action="<?= base_url('mapel/update/' . $mapel['id_mapel']) ?>">
            <?= csrf_field() ?>

            
            <div class="form-group">
                <label class="form-label">Nama Mata Pelajaran <span class="required">*</span></label>
                <input type="text" name="nama_mapel" class="form-control" value="<?= esc($mapel['nama_mapel']) ?>" required maxlength="100">
            </div>

            <div class="form-group">
                <label class="form-label">Status <span class="required">*</span></label>
                <select name="status" class="form-control" required>
                    <option value="Umum" <?= $mapel['status'] == 'Umum' ? 'selected':'' ?>>Umum</option>
                    <option value="Produktif"     <?= $mapel['status'] == 'Produktif'     ? 'selected':'' ?>>Produktif</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Update</button>
                <a href="<?= base_url('mapel') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'    => 'Edit Mapel',
    'subtitle' => 'Master Data Mata Pelajaran',
    'content'  => $content,
]);
?>
