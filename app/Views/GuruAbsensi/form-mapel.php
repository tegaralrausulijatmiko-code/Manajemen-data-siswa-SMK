<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Absensi Mata Pelajaran</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('guru/absensi') ?>">Absensi</a> /
            <?= esc($jadwal['nama_mapel'] ?? 'Mapel') ?>
        </div>
    </div>
    <a href="<?= base_url('guru/absensi') ?>" class="btn btn-secondary btn-sm">
        <i class="ri-arrow-left-line"></i> Kembali
    </a>
</div>

<!-- Info Jadwal -->
<div class="stats-grid" style="margin-bottom: 20px;">
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
        <div class="stat-icon"><i class="ri-time-line"></i></div>
        <div class="stat-text">
            <p>Jadwal</p>
            <h4><?= esc($jadwal['hari']) ?>, <?= esc(substr($jadwal['jam_mulai'], 0, 5)) ?>–<?= esc(substr($jadwal['jam_selesai'], 0, 5)) ?></h4>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="ri-calendar-line"></i></div>
        <div class="stat-text">
            <p>Tanggal</p>
            <h4><?= date('d M Y', strtotime($tanggal)) ?></h4>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Siswa — <?= esc($jadwal['nama_kelas'] ?? '-') ?></div>
        <!-- Pilih tanggal -->
        <form method="get" style="display:flex; gap:8px; align-items:center;">
            <input type="date" name="tanggal" class="form-control" style="width:170px;" value="<?= esc($tanggal) ?>">
            <button type="submit" class="btn btn-secondary btn-sm"><i class="ri-filter-line"></i> Tampilkan</button>
        </form>
    </div>

    <form method="post" action="<?= base_url('guru/absensi/jadwal/' . $jadwal['id_jadwal'] . '/simpan') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="tanggal" value="<?= esc($tanggal) ?>">

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="160">NISN</th>
                        <th>Nama Siswa</th>
                        <th width="380">Status Kehadiran</th>
                        <th width="220">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($siswa_list)): ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="ri-user-search-line"></i>
                                    <p>Tidak ada siswa pada kelas ini</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($siswa_list as $i => $s):
                            $current = $absensi_map[$s['id_siswa']]['status'] ?? 'Hadir';
                            $note    = $absensi_map[$s['id_siswa']]['keterangan'] ?? '';
                        ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><code><?= esc($s['nisn']) ?></code></td>
                                <td><strong><?= esc($s['nama_siswa']) ?></strong></td>
                                <td>
                                    <div class="attendance-options">
                                        <?php foreach ($status_list as $status):
                                            $colorMap = [
                                                'Hadir'     => ['border'=>'#86efac','active_bg'=>'#16a34a','color'=>'#15803d'],
                                                'Izin'      => ['border'=>'#fcd34d','active_bg'=>'#d97706','color'=>'#92400e'],
                                                'Sakit'     => ['border'=>'#93c5fd','active_bg'=>'#2563eb','color'=>'#1e40af'],
                                                'Alpha'      => ['border'=>'#fca5a5','active_bg'=>'#dc2626','color'=>'#991b1b'],
                                            ];
                                            $c = $colorMap[$status] ?? $colorMap['Hadir'];
                                            $isActive = $current === $status;
                                        ?>
                                            <label class="attendance-option <?= $isActive ? 'active' : '' ?>"
                                                   data-color="<?= esc($c['active_bg']) ?>"
                                                   data-border="<?= esc($c['border']) ?>"
                                                   data-text="<?= esc($c['color']) ?>"
                                                   style="<?= $isActive ? 'background:' . $c['active_bg'] . '; border-color:' . $c['active_bg'] . '; color:#fff;' : 'border-color:' . $c['border'] . '; color:' . $c['color'] . ';' ?>">
                                                <input type="radio"
                                                       name="status[<?= $s['id_siswa'] ?>]"
                                                       value="<?= esc($status) ?>"
                                                       <?= $isActive ? 'checked' : '' ?>>
                                                <?= esc($status) ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                                <td>
                                    <input type="text"
                                           name="keterangan[<?= $s['id_siswa'] ?>]"
                                           class="form-control keterangan-field"
                                           data-id="<?= $s['id_siswa'] ?>"
                                           value="<?= esc($note) ?>"
                                           placeholder="Opsional"
                                           style="<?= in_array($current, ['Izin','Sakit']) ? '' : 'display:none;' ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (! empty($siswa_list)): ?>
            <div style="padding:16px 20px; border-top:1px solid var(--border); display:flex; justify-content:flex-end; gap:10px;">
                <a href="<?= base_url('guru/absensi') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i> Simpan Absensi
                </button>
            </div>
        <?php endif; ?>
    </form>
</div>

<?php
$content = ob_get_clean();

$extra_css = <<<CSS
<style>
.attendance-options { display:flex; gap:6px; flex-wrap:wrap; }
.attendance-option {
    position:relative; border:1px solid #86efac; color:#15803d;
    border-radius:6px; padding:7px 12px; font-size:0.8rem; font-weight:700;
    cursor:pointer; background:#fff; transition:.15s; user-select:none;
}
.attendance-option input { position:absolute; opacity:0; pointer-events:none; }
.attendance-option:hover { filter: brightness(0.95); }
</style>
CSS;

$extra_js = <<<JS
<script>
document.querySelectorAll('.attendance-option input').forEach(input => {
    input.addEventListener('change', () => {
        const row   = input.closest('tr');
        const group = input.closest('.attendance-options');

        // reset semua option di baris ini
        group.querySelectorAll('.attendance-option').forEach(lbl => {
            lbl.classList.remove('active');
            lbl.style.background   = '#fff';
            lbl.style.borderColor  = lbl.dataset.border;
            lbl.style.color        = lbl.dataset.text;
        });

        // aktifkan yang dipilih
        const active = input.closest('.attendance-option');
        active.classList.add('active');
        active.style.background  = active.dataset.color;
        active.style.borderColor = active.dataset.color;
        active.style.color       = '#fff';

        // tampilkan/sembunyikan field keterangan
        const needNote = ['Izin', 'Sakit'].includes(input.value);
        const ket = row.querySelector('.keterangan-field');
        if (ket) {
            ket.style.display = needNote ? '' : 'none';
            if (!needNote) ket.value = '';
        }
    });
});
</script>
JS;

echo view('Template/layout', [
    'title'     => 'Absensi Mapel',
    'subtitle'  => 'Input absensi berdasarkan jadwal pelajaran',
    'content'   => $content,
    'extra_css' => $extra_css,
    'extra_js'  => $extra_js,
]);
?>