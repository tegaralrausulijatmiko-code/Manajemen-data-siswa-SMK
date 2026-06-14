<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Tambah Mata Pelajaran</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('mapel') ?>">Mata Pelajaran</a> / Tambah
        </div>
    </div>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-header">
        <div class="card-title"><i class="ri-add-circle-line" style="color:var(--primary);"></i> Form Tambah Mata Pelajaran</div>
    </div>
    <div style="padding:25px;">
        <form method="post" action="<?= base_url('mapel/simpan') ?>">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kode Mapel <span class="required">*</span></label>
                    <input type="text" name="kode_mapel" class="form-control" value="<?= old('kode_mapel', $next_kode ?? '120') ?>" readonly maxlength="10">
                    <?php if (isset($errors['kode_mapel'])): ?>
                        <small style="color:var(--danger);"><?= $errors['kode_mapel'] ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                <label class="form-label">Tingkat <span class="required">*</span></label>
                    <select name="tingkat" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <?php foreach (['X','XI','XII'] as $t): ?>
                        <option value="<?= $t ?>" <?= old('tbl_kelas.tingkat') == $t ? 'selected':'' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
                    <div class="form-group">
                    <label class="form-label">Guru Pengampu <span class="required">*</span></label>
                    <select name="id_guru" class="form-control" required>
                        <option value="">-- Pilih Guru --</option>
                        <?php foreach ($guru_list as $guru): ?>
                            <option value="<?= $guru['id_guru'] ?>" <?= old('id_guru') == $guru['id_guru'] ? 'selected' : '' ?>>
                                <?= esc($guru['nama_guru']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['id_guru'])): ?>
                        <small style="color:var(--danger);"><?= $errors['id_guru'] ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Mata Pelajaran <span class="required">*</span></label>
                <input type="text" name="nama_mapel" class="form-control" placeholder="cth: Pemrograman Web" value="<?= old('nama_mapel') ?>" required maxlength="100">
                <?php if (isset($errors['nama_mapel'])): ?>
                    <small style="color:var(--danger);"><?= $errors['nama_mapel'] ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Status <span class="required">*</span></label>
                <select name="status" class="form-control" required>
                    <option value="Non Produktif" <?= old('status') == 'Non Produktif' ? 'selected':'' ?>>Non Produktif</option>
                    <option value="Produktif"     <?= old('status') == 'Produktif'     ? 'selected':'' ?>>Produktif</option>
                </select>
            </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Simpan</button>
                <a href="<?= base_url('mapel') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'    => 'Tambah Mapel',
    'subtitle' => 'Master Data Mata Pelajaran',
    'content'  => $content,
]);
?>
