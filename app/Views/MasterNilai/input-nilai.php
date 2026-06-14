<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Tambah Nilai</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / <a href="<?= base_url('nilai') ?>">Nilai</a> / Tambah</div>
    </div>
</div>

<div class="card" style="max-width:760px;">
    <div class="card-header"><div class="card-title"><i class="ri-add-circle-line" style="color:var(--primary);"></i> Form Nilai</div></div>
    <div style="padding:25px;">
        <form method="post" action="<?= base_url('nilai/simpan') ?>">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Siswa <span class="required">*</span></label>
                    <select name="id_siswa" class="form-control" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php foreach ($siswa_list as $s): ?><option value="<?= $s['id_siswa'] ?>" <?= old('id_siswa') == $s['id_siswa'] ? 'selected' : '' ?>><?= esc($s['nama_siswa']) ?> - <?= esc($s['nama_kelas'] ?? '-') ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Mata Pelajaran <span class="required">*</span></label>
                    <select name="id_mapel" class="form-control" required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php foreach ($mapel_list as $m): ?><option value="<?= $m['id_mapel'] ?>" <?= old('id_mapel') == $m['id_mapel'] ? 'selected' : '' ?>><?= esc($m['nama_mapel']) ?></option><?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Tahun Ajaran <span class="required">*</span></label>
                <select name="id_tahun_ajaran" class="form-control" required>
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    <?php foreach ($tahun_list as $t): ?><option value="<?= $t['id_tahun_ajaran'] ?>" <?= old('id_tahun_ajaran') == $t['id_tahun_ajaran'] ? 'selected' : '' ?>><?= esc($t['tahun_ajaran']) ?> - <?= esc($t['semester']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Nilai Tugas</label><input type="number" name="nilai_tugas" class="form-control" min="0" max="100" step="0.01" value="<?= old('nilai_tugas', '0') ?>" required></div>
                <div class="form-group"><label class="form-label">Nilai UTS</label><input type="number" name="nilai_uts" class="form-control" min="0" max="100" step="0.01" value="<?= old('nilai_uts', '0') ?>" required></div>
            </div>
            <div class="form-group"><label class="form-label">Nilai UAS</label><input type="number" name="nilai_uas" class="form-control" min="0" max="100" step="0.01" value="<?= old('nilai_uas', '0') ?>" required></div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Simpan</button>
                <a href="<?= base_url('nilai') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', ['title' => 'Tambah Nilai', 'subtitle' => 'Akademik', 'content' => $content]);
?>
