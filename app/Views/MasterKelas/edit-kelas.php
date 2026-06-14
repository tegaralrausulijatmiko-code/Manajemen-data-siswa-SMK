<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Edit Kelas</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('kelas') ?>">Kelas</a> / Edit
        </div>
    </div>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-header">
        <div class="card-title"><i class="ri-edit-line" style="color:var(--primary);"></i> Form Edit Kelas</div>
    </div>
    <div style="padding:25px;">
        <form method="post" action="<?= base_url('kelas/update/' . $kelas['id_kelas']) ?>">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Kelas <span class="required">*</span></label>
                    <input type="text" name="nama_kelas" class="form-control" value="<?= esc($kelas['nama_kelas']) ?>" required maxlength="50">
                </div>
                <div class="form-group">
                    <label class="form-label">Tingkat <span class="required">*</span></label>
                    <select name="tingkat" class="form-control" required>
                        <?php foreach (['X','XI','XII'] as $t): ?>
                        <option value="<?= $t ?>" <?= $kelas['tingkat'] == $t ? 'selected':'' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Jurusan <span class="required">*</span></label>
                <select name="id_jurusan" class="form-control" required>
                    <?php foreach ($jurusan_list as $j): ?>
                    <option value="<?= $j['id_jurusan'] ?>" <?= $kelas['id_jurusan'] == $j['id_jurusan'] ? 'selected':'' ?>>
                        <?= esc($j['kode_jurusan']) ?> – <?= esc($j['nama_jurusan']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Wali Kelas (Opsional)</label>
                <select name="id_wali_kelas" class="form-control">
                    <option value="">-- Tidak Ada --</option>
                    <?php foreach ($guru_list as $g): ?>
                    <option value="<?= $g['id_guru'] ?>" <?= $kelas['id_wali_kelas'] == $g['id_guru'] ? 'selected':'' ?>>
                        <?= esc($g['nama_guru']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Jumlah Siswa</label>
                <input type="number" name="jumlah_siswa" class="form-control" value="<?= esc($kelas['jumlah_siswa']) ?>" min="0">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Update</button>
                <a href="<?= base_url('kelas') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'    => 'Edit Kelas',
    'subtitle' => 'Master Data Kelas',
    'content'  => $content,
]);
?>
