<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Absensi Harian — <?= esc($kelas['nama_kelas']) ?></h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('admin/absensi/harian') ?>">Absensi Harian</a> /
            <?= esc($kelas['nama_kelas']) ?>
        </div>
    </div>
    <a href="<?= base_url('admin/absensi/harian?tanggal=' . $tanggal) ?>" class="btn btn-secondary btn-sm">
        <i class="ri-arrow-left-line"></i> Kembali
    </a>
</div>

<!-- Info Kelas -->
<div class="stats-grid" style="margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4; color:#16a34a;"><i class="ri-building-4-line"></i></div>
        <div class="stat-text">
            <p>Kelas</p>
            <h4><?= esc($kelas['nama_kelas']) ?></h4>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4; color:#16a34a;"><i class="ri-award-line"></i></div>
        <div class="stat-text">
            <p>Jurusan</p>
            <h4><?= esc($kelas['kode_jurusan'] . ' – ' . $kelas['nama_jurusan']) ?></h4>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4; color:#16a34a;"><i class="ri-user-star-line"></i></div>
        <div class="stat-text">
            <p>Wali Kelas</p>
            <h4><?= esc($kelas['nama_wali'] ?? '-') ?></h4>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4; color:#16a34a;"><i class="ri-calendar-check-line"></i></div>
        <div class="stat-text">
            <p>Tanggal</p>
            <h4><?= date('d M Y', strtotime($tanggal)) ?></h4>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Siswa — <?= esc($kelas['nama_kelas']) ?></div>
        <form method="get" style="display:flex; gap:8px; align-items:center;">
            <input type="date" name="tanggal" class="form-control" style="width:170px;" value="<?= esc($tanggal) ?>">
            <button type="submit" class="btn btn-secondary btn-sm">
                <i class="ri-filter-line"></i> Tampilkan
            </button>
        </form>
    </div>

    <form method="post" action="<?= base_url('admin/absensi/harian/' . $kelas['id_kelas'] . '/simpan') ?>" id="form-harian">
        <?= csrf_field() ?>
        <input type="hidden" name="tanggal" value="<?= esc($tanggal) ?>">

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="70">Foto</th>
                        <th width="160">NISN</th>
                        <th>Nama Siswa</th>
                        <th width="340">Status</th>
                        <th width="200">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($siswa_list)): ?>
                        <tr>
                            <td colspan="6">
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
                            <tr data-siswa="<?= $s['id_siswa'] ?>">
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <?php if (! empty($s['foto'])): ?>
                                        <img src="<?= base_url('uploads/foto_siswa/' . $s['foto']) ?>"
                                             style="width:38px; height:38px; border-radius:50%; object-fit:cover;">
                                    <?php else: ?>
                                        <div style="width:38px; height:38px; border-radius:50%; background:#eff6ff; color:var(--primary); display:flex; align-items:center; justify-content:center;">
                                            <i class="ri-user-line"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><code><?= esc($s['nisn']) ?></code></td>
                                <td><strong><?= esc($s['nama_siswa']) ?></strong></td>
                                <td>
                                    <div class="attendance-options">
                                        <?php foreach ($status_list as $status):
                                            $colorMap = [
                                                'Hadir' => ['border'=>'#86efac','active_bg'=>'#16a34a','text'=>'#15803d'],
                                                'Izin'  => ['border'=>'#fcd34d','active_bg'=>'#d97706','text'=>'#92400e'],
                                                'Sakit' => ['border'=>'#93c5fd','active_bg'=>'#2563eb','text'=>'#1e40af'],
                                                'Alpha' => ['border'=>'#fca5a5','active_bg'=>'#dc2626','text'=>'#991b1b'],
                                            ];
                                            $c = $colorMap[$status] ?? $colorMap['Hadir'];
                                            $isActive = $current === $status;
                                        ?>
                                            <label class="attendance-option <?= $isActive ? 'active' : '' ?>"
                                                   data-color="<?= esc($c['active_bg']) ?>"
                                                   data-border="<?= esc($c['border']) ?>"
                                                   data-text="<?= esc($c['text']) ?>"
                                                   style="<?= $isActive ? 'background:' . $c['active_bg'] . '; border-color:' . $c['active_bg'] . '; color:#fff;' : 'border-color:' . $c['border'] . '; color:' . $c['text'] . ';' ?>">
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
                                           value="<?= esc($note) ?>"
                                           placeholder="Keterangan..."
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
                <a href="<?= base_url('admin/absensi/harian?tanggal=' . $tanggal) ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i> Simpan Absensi Harian
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
.attendance-option:hover { filter:brightness(0.95); }
</style>
CSS;

$extra_js = <<<JS
<script>
document.querySelectorAll('.attendance-option input').forEach(input => {
    input.addEventListener('change', () => {
        const row   = input.closest('tr');
        const group = input.closest('.attendance-options');

        group.querySelectorAll('.attendance-option').forEach(lbl => {
            lbl.classList.remove('active');
            lbl.style.background  = '#fff';
            lbl.style.borderColor = lbl.dataset.border;
            lbl.style.color       = lbl.dataset.text;
        });

        const active = input.closest('.attendance-option');
        active.classList.add('active');
        active.style.background  = active.dataset.color;
        active.style.borderColor = active.dataset.color;
        active.style.color       = '#fff';

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
    'title'     => 'Absensi Harian — ' . $kelas['nama_kelas'],
    'subtitle'  => 'Input absensi harian siswa',
    'content'   => $content,
    'extra_css' => $extra_css,
    'extra_js'  => $extra_js,
]);
?>