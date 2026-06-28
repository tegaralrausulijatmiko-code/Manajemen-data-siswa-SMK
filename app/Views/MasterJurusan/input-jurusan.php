<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Tambah Jurusan Baru</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('jurusan') ?>">Jurusan</a> / Tambah
        </div>
    </div>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-header">
        <div class="card-title"><i class="ri-add-circle-line" style="color:var(--primary);"></i> Form Tambah Jurusan</div>
    </div>
    <div style="padding:25px;">
        <form method="post" action="<?= base_url('jurusan/simpan') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label">Kode Jurusan <span class="required">*</span></label>
                <input type="text" name="kode_jurusan" class="form-control" placeholder="cth: RPL" value="<?= old('kode_jurusan') ?>" required maxlength="10">
                <?php if (isset($errors['kode_jurusan'])): ?>
                    <small style="color:var(--danger);"><?= $errors['kode_jurusan'] ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Jurusan <span class="required">*</span></label>
                <input type="text" name="nama_jurusan" class="form-control" placeholder="cth: Rekayasa Perangkat Lunak" value="<?= old('nama_jurusan') ?>" required maxlength="100">
                <?php if (isset($errors['nama_jurusan'])): ?>
                    <small style="color:var(--danger);"><?= $errors['nama_jurusan'] ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Kepala Program (Opsional)</label>
                <select name="id_kaprog" id="id_kaprog" class="form-control">
                    <option value="">-- Pilih Guru --</option>
                    <?php foreach ($guru_list as $g): ?>
                    <option value="<?= $g['id_guru'] ?>" <?= old('id_kaprog') == $g['id_guru'] ? 'selected' : '' ?> <?= in_array($g['id_guru'], $used_kaprog_ids ?? [], true) ? 'disabled' : '' ?>>
                        <?= esc($g['nama_guru']) ?> (<?= esc($g['nip']) ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['id_kaprog'])): ?>
                    <small style="color:var(--danger);"><?= $errors['id_kaprog'] ?></small>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Simpan</button>
                <a href="<?= base_url('jurusan') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    new TomSelect('#id_kaprog', {
        create: false,
        allowEmptyOption: true,
        placeholder: '-- Pilih Guru --',
        maxOptions: 100,
        sortField: {
            field: 'text',
            direction: 'asc'
        },
        dropdownParent: 'body',
    });
});
</script>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'    => 'Tambah Jurusan',
    'subtitle' => 'Master Data Jurusan',
    'content'  => $content,
]);
?>
