<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Isi Absensi</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / <a href="<?= base_url('guru/absensi') ?>">Absensi Guru</a> / Isi Absensi</div>
    </div>
    <a href="<?= base_url('guru/absensi') ?>" class="btn btn-secondary btn-sm"><i class="ri-arrow-left-line"></i> Kembali</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="ri-building-4-line"></i></div>
        <div class="stat-text"><p>Kelas</p><h4><?= esc($jadwal['nama_kelas'] ?? '-') ?></h4></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="ri-book-read-line"></i></div>
        <div class="stat-text"><p>Mata Pelajaran</p><h4><?= esc($jadwal['nama_mapel'] ?? '-') ?></h4></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="ri-time-line"></i></div>
        <div class="stat-text"><p>Jadwal</p><h4><?= esc($jadwal['hari']) ?>, <?= esc(substr($jadwal['jam_mulai'], 0, 5)) ?> - <?= esc(substr($jadwal['jam_selesai'], 0, 5)) ?></h4></div>
    </div>
</div>

<?php if (! $is_today): ?>
    <div class="alert alert-warning"><i class="ri-alert-line"></i> Absensi tanggal ini hanya dapat dilihat. Guru hanya dapat mengedit absensi pada hari yang sama.</div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Siswa</div>
        <form method="get" style="display:flex; gap:10px; align-items:center;">
            <input type="date" name="tanggal" class="form-control" style="width:170px;" value="<?= esc($tanggal) ?>" onchange="this.form.submit()">
        </form>
    </div>

    <form method="post" action="<?= base_url('guru/absensi/jadwal/' . $jadwal['id_jadwal'] . '/simpan') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="tanggal" value="<?= esc($tanggal) ?>">

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th width="160">NISN</th>
                        <th>Nama</th>
                        <th width="520">Status Absensi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($siswa_list)): ?>
                        <tr><td colspan="4"><div class="empty-state"><i class="ri-user-search-line"></i><p>Tidak ada siswa pada kelas ini</p></div></td></tr>
                    <?php else: ?>
                        <?php foreach ($siswa_list as $i => $siswa): ?>
                            <?php $current = $absensi_map[$siswa['id_siswa']]['status'] ?? 'Hadir'; ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($siswa['nisn']) ?></td>
                                <td><strong><?= esc($siswa['nama_siswa']) ?></strong></td>
                                <td>
                                    <div class="attendance-options">
                                        <?php foreach ($status_list as $status): ?>
                                            <label class="attendance-option <?= $current === $status ? 'active' : '' ?>">
                                                <input type="radio" name="status[<?= $siswa['id_siswa'] ?>]" value="<?= esc($status) ?>" <?= $current === $status ? 'checked' : '' ?> <?= ! $is_today ? 'disabled' : '' ?>>
                                                <?= esc($status) ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (! empty($siswa_list) && $is_today): ?>
            <div style="padding:16px 20px; border-top:1px solid var(--border);">
                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;"><i class="ri-save-line"></i> Simpan Absensi</button>
            </div>
        <?php endif; ?>
    </form>
</div>

<?php
$content = ob_get_clean();

$extra_css = <<<CSS
<style>
    .attendance-options { display:flex; gap:8px; flex-wrap:wrap; }
    .attendance-option { position:relative; border:1px solid #bfdbfe; color:#1e40af; border-radius:6px; padding:8px 12px; font-size:0.82rem; font-weight:700; cursor:pointer; background:#fff; transition:0.2s; }
    .attendance-option input { position:absolute; opacity:0; pointer-events:none; }
    .attendance-option.active { background:#1e40af; border-color:#1e40af; color:#fff; }
    .attendance-option:has(input:disabled) { cursor:not-allowed; opacity:0.75; }
</style>
CSS;

$extra_js = <<<JS
<script>
    document.querySelectorAll('.attendance-option input').forEach(input => {
        input.addEventListener('change', () => {
            const group = input.closest('.attendance-options');
            group.querySelectorAll('.attendance-option').forEach(label => label.classList.remove('active'));
            input.closest('.attendance-option').classList.add('active');
        });
    });
</script>
JS;

echo view('Template/layout', [
    'title' => 'Isi Absensi',
    'subtitle' => 'Input absensi kelas yang diajar',
    'content' => $content,
    'extra_css' => $extra_css,
    'extra_js' => $extra_js,
]);
?>
