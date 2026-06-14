<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Edit Absensi</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / <a href="<?= base_url('absensi') ?>">Absensi</a> / Edit</div>
    </div>
</div>

<div class="card" style="max-width:760px;">
    <div class="card-header"><div class="card-title"><i class="ri-edit-line" style="color:var(--primary);"></i> Form Absensi</div></div>
    <div style="padding:25px;">
        <form method="post" action="<?= base_url('absensi/update/' . $absensi['id_absensi']) ?>">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Siswa <span class="required">*</span></label>
                    <select name="id_siswa" class="form-control" required>
                        <?php foreach ($siswa_list as $s): ?><option value="<?= $s['id_siswa'] ?>" <?= $absensi['id_siswa'] == $s['id_siswa'] ? 'selected' : '' ?>><?= esc($s['nama_siswa']) ?> - <?= esc($s['nama_kelas'] ?? '-') ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kelas <span class="required">*</span></label>
                    <select name="id_kelas" class="form-control" required>
                        <?php foreach ($kelas_list as $k): ?><option value="<?= $k['id_kelas'] ?>" <?= $absensi['id_kelas'] == $k['id_kelas'] ? 'selected' : '' ?>><?= esc($k['nama_kelas']) ?></option><?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tahun Ajaran <span class="required">*</span></label>
                    <select name="id_tahun_ajaran" class="form-control" required>
                        <?php foreach ($tahun_list as $t): ?><option value="<?= $t['id_tahun_ajaran'] ?>" <?= $absensi['id_tahun_ajaran'] == $t['id_tahun_ajaran'] ? 'selected' : '' ?>><?= esc($t['tahun_ajaran']) ?> - <?= esc($t['semester']) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Tanggal <span class="required">*</span></label><input type="date" name="tanggal" class="form-control" value="<?= esc($absensi['tanggal']) ?>" required></div>
            </div>
            <div class="form-group">
                <label class="form-label">Status <span class="required">*</span></label>
                <select name="status" class="form-control" required>
                    <?php foreach ($status_list as $st): ?><option value="<?= $st ?>" <?= $absensi['status'] == $st ? 'selected' : '' ?>><?= $st ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="3"><?= esc($absensi['keterangan'] ?? '') ?></textarea></div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Update</button>
                <a href="<?= base_url('absensi') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', ['title' => 'Edit Absensi', 'subtitle' => 'Akademik', 'content' => $content]);
?>
