<?php ob_start(); ?>
<?php $errors = $errors ?? session('errors') ?? []; ?>
<?php $jurusan_list = $jurusan_list ?? []; ?>
<?php $guru_list = $guru_list ?? []; ?>

<div class="page-header">
    <div>
        <h3>Tambah Kelas Baru</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('kelas') ?>">Kelas</a> / Tambah
        </div>
    </div>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-header">
        <div class="card-title"><i class="ri-add-circle-line" style="color:var(--primary);"></i> Form Tambah Kelas</div>
    </div>
    <div style="padding:25px;">
        <form method="post" action="<?= base_url('kelas/simpan') ?>">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Kelas <span class="required">*</span></label>
                    <input type="text" name="nama_kelas" class="form-control" placeholder="cth: X RPL 1"
                        value="<?= old('nama_kelas') ?>" required maxlength="50">
                    <?php if (isset($errors['nama_kelas'])): ?>
                    <small style="color:var(--danger);"><?= $errors['nama_kelas'] ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Tingkat <span class="required">*</span></label>
                        <select name="tingkat" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach (['X','XI','XII'] as $t): ?>
                            <option value="<?= $t ?>" <?= old('tingkat') == $t ? 'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Jurusan <span class="required">*</span></label>
                <select name="id_jurusan" class="form-control" required>
                    <option value="">-- Pilih Jurusan --</option>
                    <?php foreach ($jurusan_list as $j): ?>
                    <option value="<?= $j['id_jurusan'] ?>"
                        <?= old('id_jurusan') == $j['id_jurusan'] ? 'selected':'' ?>>
                        <?= esc($j['kode_jurusan']) ?> – <?= esc($j['nama_jurusan']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            

            <div class="form-group">
                <label class="form-label">Wali Kelas (Opsional)</label>
                <select name="id_wali_kelas" class="form-control">
                    <option value="">-- Pilih Guru --</option>
                    <?php foreach ($guru_list as $g): ?>
                    <option value="<?= $g['id_guru'] ?>" <?= old('id_wali_kelas') == $g['id_guru'] ? 'selected':'' ?>>
                        <?= esc($g['nama_guru']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['id_wali_kelas'])): ?>
                <small style="color:var(--danger);"><?= $errors['id_wali_kelas'] ?></small>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Simpan</button>
                <a href="<?= base_url('kelas') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'    => 'Tambah Kelas',
    'subtitle' => 'Master Data Kelas',
    'content'  => $content,
]);
?>