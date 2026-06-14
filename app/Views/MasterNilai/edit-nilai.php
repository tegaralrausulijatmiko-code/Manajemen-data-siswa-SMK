<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Edit Nilai</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / <a href="<?= base_url('nilai') ?>">Nilai</a> / Edit</div>
    </div>
</div>

<div class="card" style="max-width:760px;">
    <div class="card-header"><div class="card-title"><i class="ri-edit-line" style="color:var(--primary);"></i> Form Nilai</div></div>
    <div style="padding:25px;">
        <form method="post" action="<?= base_url('nilai/update/' . $nilai['id_nilai']) ?>">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Siswa <span class="required">*</span></label>
                    <select name="id_siswa" class="form-control" required>
                        <?php foreach ($siswa_list as $s): ?><option value="<?= $s['id_siswa'] ?>" <?= $nilai['id_siswa'] == $s['id_siswa'] ? 'selected' : '' ?>><?= esc($s['nama_siswa']) ?> - <?= esc($s['nama_kelas'] ?? '-') ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Mata Pelajaran <span class="required">*</span></label>
                    <select name="id_mapel" class="form-control" required>
                        <?php foreach ($mapel_list as $m): ?><option value="<?= $m['id_mapel'] ?>" <?= $nilai['id_mapel'] == $m['id_mapel'] ? 'selected' : '' ?>><?= esc($m['nama_mapel']) ?></option><?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Tahun Ajaran <span class="required">*</span></label>
                <select name="id_tahun_ajaran" class="form-control" required>
                    <?php foreach ($tahun_list as $t): ?><option value="<?= $t['id_tahun_ajaran'] ?>" <?= $nilai['id_tahun_ajaran'] == $t['id_tahun_ajaran'] ? 'selected' : '' ?>><?= esc($t['tahun_ajaran']) ?> - <?= esc($t['semester']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Nilai Tugas</label><input type="number" name="nilai_tugas" class="form-control" min="0" max="100" step="0.01" value="<?= esc($nilai['nilai_tugas']) ?>" required></div>
                <div class="form-group"><label class="form-label">Nilai UTS</label><input type="number" name="nilai_uts" class="form-control" min="0" max="100" step="0.01" value="<?= esc($nilai['nilai_uts']) ?>" required></div>
            </div>
            <div class="form-group"><label class="form-label">Nilai UAS</label><input type="number" name="nilai_uas" class="form-control" min="0" max="100" step="0.01" value="<?= esc($nilai['nilai_uas']) ?>" required></div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Update</button>
                <a href="<?= base_url('nilai') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', ['title' => 'Edit Nilai', 'subtitle' => 'Akademik', 'content' => $content]);
?>
