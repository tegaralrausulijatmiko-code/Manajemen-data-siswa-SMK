<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Absensi dari Jadwal</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / <a href="<?= base_url('jadwal') ?>">Jadwal</a> / Absensi</div>
    </div>
    <a href="<?= base_url('jadwal') ?>" class="btn btn-secondary btn-sm"><i class="ri-arrow-left-line"></i> Kembali</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="ri-building-4-line"></i></div>
        <div class="stat-text">
            <p>Kelas</p>
            <h4><?= esc($jadwal['nama_kelas'] ?? '-') ?></h4>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="ri-book-read-line"></i></div>
        <div class="stat-text">
            <p>Mata Pelajaran</p>
            <h4><?= esc($jadwal['nama_mapel'] ?? '-') ?></h4>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="ri-user-star-line"></i></div>
        <div class="stat-text">
            <p>Guru</p>
            <h4><?= esc($jadwal['nama_guru'] ?? '-') ?></h4>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="ri-time-line"></i></div>
        <div class="stat-text">
            <p>Jadwal</p>
            <h4><?= esc($jadwal['hari']) ?>, <?= esc(substr($jadwal['jam_mulai'], 0, 5)) ?> - <?= esc(substr($jadwal['jam_selesai'], 0, 5)) ?></h4>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Siswa <?= esc($jadwal['nama_kelas'] ?? '-') ?></div>
        <form method="get" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <input type="date" name="tanggal" class="form-control" style="width:170px;" value="<?= esc($tanggal) ?>">
            <button type="submit" class="btn btn-secondary btn-sm"><i class="ri-filter-line"></i> Tampilkan</button>
        </form>
    </div>

    <form method="post" action="<?= base_url('absensi/jadwal/' . $jadwal['id_jadwal'] . '/simpan') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="tanggal" value="<?= esc($tanggal) ?>">

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th width="170">NISN</th>
                        <th>Nama</th>
                        <th width="360">Keterangan</th>
                        <th width="260">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($siswa_list)): ?>
                        <tr><td colspan="5"><div class="empty-state"><i class="ri-user-search-line"></i><p>Tidak ada siswa pada kelas ini</p></div></td></tr>
                    <?php else: ?>
                        <?php foreach ($siswa_list as $i => $s): ?>
                            <?php
                                $current = $absensi_map[$s['id_siswa']]['status'] ?? 'Hadir';
                                $note = $absensi_map[$s['id_siswa']]['keterangan'] ?? '';
                            ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($s['nisn']) ?></td>
                                <td><strong><?= esc($s['nama_siswa']) ?></strong></td>
                                <td>
                                    <div class="attendance-options">
                                        <?php foreach ($status_list as $status): ?>
                                            <label class="attendance-option <?= $current === $status ? 'active' : '' ?>">
                                                <input type="radio" name="status[<?= $s['id_siswa'] ?>]" value="<?= esc($status) ?>" <?= $current === $status ? 'checked' : '' ?>>
                                                <?= esc($status) ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="keterangan[<?= $s['id_siswa'] ?>]" class="form-control" value="<?= esc($note) ?>" placeholder="Opsional">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (! empty($siswa_list)): ?>
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
    .attendance-options { display: flex; gap: 8px; flex-wrap: wrap; }
    .attendance-option { position: relative; border: 1px solid #86efac; color: #15803d; border-radius: 6px; padding: 8px 12px; font-size: 0.82rem; font-weight: 700; cursor: pointer; background: #fff; transition: 0.2s; }
    .attendance-option input { position: absolute; opacity: 0; pointer-events: none; }
    .attendance-option.active { background: #16a34a; border-color: #16a34a; color: #fff; }
    .attendance-option:hover { border-color: #16a34a; }
    .stat-text h4 { font-size: 1rem; line-height: 1.35; }
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
    'title'     => 'Absensi Jadwal',
    'subtitle'  => 'Input absensi berdasarkan jadwal pelajaran',
    'content'   => $content,
    'extra_css' => $extra_css,
    'extra_js'  => $extra_js,
]);
?>
