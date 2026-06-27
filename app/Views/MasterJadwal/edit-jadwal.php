<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Edit Jadwal</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / <a href="<?= base_url('jadwal') ?>">Jadwal</a> / Edit</div>
    </div>
</div>

<div class="card" style="max-width:720px;">
    <div class="card-header"><div class="card-title"><i class="ri-edit-line" style="color:var(--primary);"></i> Form Edit Jadwal</div></div>
    <div style="padding:25px;">
        <form method="post" action="<?= base_url('jadwal/update/' . $jadwal['id_jadwal']) ?>">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kelas <span class="required">*</span></label>
                    <select name="id_kelas" class="form-control" required>
                        <?php foreach ($kelas_list as $k): ?><option value="<?= $k['id_kelas'] ?>" <?= $jadwal['id_kelas'] == $k['id_kelas'] ? 'selected' : '' ?>><?= esc($k['nama_kelas']) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Mata Pelajaran <span class="required">*</span></label>
                    <select name="id_mapel" class="form-control" required>
                        <?php foreach ($mapel_list as $m): ?><option value="<?= $m['id_mapel'] ?>" <?= $jadwal['id_mapel'] == $m['id_mapel'] ? 'selected' : '' ?>><?= esc($m['nama_mapel']) ?></option><?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Guru <span class="required">*</span></label>
                    <select name="id_guru" class="form-control" required>
                        <?php foreach ($guru_list as $g): ?><option value="<?= $g['id_guru'] ?>" <?= $jadwal['id_guru'] == $g['id_guru'] ? 'selected' : '' ?>><?= esc($g['nama_guru']) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Hari <span class="required">*</span></label>
                    <select name="hari" class="form-control" required>
                        <?php foreach ($hari_list as $h): ?><option value="<?= $h ?>" <?= $jadwal['hari'] == $h ? 'selected' : '' ?>><?= $h ?></option><?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Jam Mulai <span class="required">*</span></label><input type="time" name="jam_mulai" class="form-control" value="<?= esc(substr($jadwal['jam_mulai'], 0, 5)) ?>" required></div>
                <div class="form-group"><label class="form-label">Jam Selesai <span class="required">*</span></label><input type="time" name="jam_selesai" class="form-control" value="<?= esc(substr($jadwal['jam_selesai'], 0, 5)) ?>" required></div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Update</button>
                <a href="<?= base_url('jadwal') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', ['title' => 'Edit Jadwal', 'subtitle' => 'Master Jadwal', 'content' => $content]);
?>
