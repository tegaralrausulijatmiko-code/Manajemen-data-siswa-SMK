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
        <div class="card-title">
            <i class="ri-edit-line" style="color:var(--primary);"></i>
            Form Edit Mata Pelajaran
        </div>
    </div>

    <div style="padding:25px;">
        <form method="post" action="<?= base_url('mapel/update/' . $mapel['id_mapel']) ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label">Tingkat <span class="required">*</span></label>
                <select name="tingkat" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <?php foreach (['X','XI','XII'] as $t): ?>
                        <option value="<?= $t ?>" <?= $mapel['tingkat'] == $t ? 'selected' : '' ?>>
                            <?= $t ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Jurusan</label>
                <select name="id_jurusan" class="form-control">
                    <option value="">Semua Jurusan</option>

                    <?php foreach ($jurusan_list as $jurusan): ?>
                        <option value="<?= $jurusan['id_jurusan'] ?>"
                            <?= $mapel['id_jurusan'] == $jurusan['id_jurusan'] ? 'selected' : '' ?>>
                            <?= esc($jurusan['nama_jurusan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <?php if (isset($errors['id_jurusan'])): ?>
                    <small style="color:var(--danger);">
                        <?= $errors['id_jurusan'] ?>
                    </small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Nama Mata Pelajaran <span class="required">*</span>
                </label>

                <input
                    type="text"
                    name="nama_mapel"
                    class="form-control"
                    value="<?= esc($mapel['nama_mapel']) ?>"
                    maxlength="150"
                    required>

                <?php if (isset($errors['nama_mapel'])): ?>
                    <small style="color:var(--danger);">
                        <?= $errors['nama_mapel'] ?>
                    </small>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i> Update
                </button>

                <a href="<?= base_url('mapel') ?>" class="btn btn-secondary">
                    Batal
                </a>
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