<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Tambah Jadwal</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / <a href="<?= base_url('jadwal') ?>">Jadwal</a> / Tambah</div>
    </div>
</div>

<div class="card" style="max-width:720px;">
    <div class="card-header"><div class="card-title"><i class="ri-add-circle-line" style="color:var(--primary);"></i> Form Tambah Jadwal</div></div>
    <div style="padding:25px;">
        <form method="post" action="<?= base_url('jadwal/simpan') ?>">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kelas <span class="required">*</span></label>
                    <select name="id_kelas" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelas_list as $k): ?><option value="<?= $k['id_kelas'] ?>" <?= old('id_kelas') == $k['id_kelas'] ? 'selected' : '' ?>><?= esc($k['nama_kelas']) ?></option><?php endforeach; ?>
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
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Guru <span class="required">*</span></label>
                    <select name="id_guru" class="form-control" required>
                        <option value="">-- Pilih Guru --</option>
                        <?php foreach ($guru_list as $g): ?><option value="<?= $g['id_guru'] ?>" <?= old('id_guru') == $g['id_guru'] ? 'selected' : '' ?>><?= esc($g['nama_guru']) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Hari <span class="required">*</span></label>
                    <select name="hari" class="form-control" required>
                        <option value="">-- Pilih Hari --</option>
                        <?php foreach ($hari_list as $h): ?><option value="<?= $h ?>" <?= old('hari') == $h ? 'selected' : '' ?>><?= $h ?></option><?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Jam Mulai <span class="required">*</span></label><input type="time" name="jam_mulai" class="form-control" value="<?= old('jam_mulai') ?>" required></div>
                <div class="form-group"><label class="form-label">Jam Selesai <span class="required">*</span></label><input type="time" name="jam_selesai" class="form-control" value="<?= old('jam_selesai') ?>" required></div>
            </div>
            <div class="form-group"><label class="form-label">Ruang</label><input type="text" name="ruang" class="form-control" value="<?= old('ruang') ?>" maxlength="50"></div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Simpan</button>
                <a href="<?= base_url('jadwal') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', ['title' => 'Tambah Jadwal', 'subtitle' => 'Master Jadwal', 'content' => $content]);
?>
