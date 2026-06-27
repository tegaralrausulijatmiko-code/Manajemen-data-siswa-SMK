<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Absensi Harian</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <?php if ($kelas): ?>
                <a href="<?= base_url('absensi/harian') ?>">Absensi Harian</a> /
                <?= esc($kelas['nama_kelas']) ?>
            <?php else: ?>
                Absensi Harian
            <?php endif; ?>
        </div>
    </div>
    <div style="display:flex;gap:8px;align-items:center;">
        <?php if ($kelas): ?>
            <a href="<?= base_url('absensi/harian') ?>" class="btn btn-secondary btn-sm">
                <i class="ri-grid-line"></i> Semua Kelas
            </a>
        <?php endif; ?>
        <a href="<?= base_url('absensi/rekap?jenis=harian') ?>" class="btn btn-secondary btn-sm">
            <i class="ri-file-chart-line"></i> Rekap Harian
        </a>
    </div>
</div>

<?php if (! $kelas): ?>
<!-- ===================================================================== -->
<!-- STEP 1 — Pilih kelas                                                  -->
<!-- ===================================================================== -->

<!-- Filter tanggal + cari kelas -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div class="card-title">Tanggal Absensi</div>
    </div>
    <div style="padding:14px 20px;display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <input type="date" id="input-tanggal" class="form-control"
               style="width:180px;padding:8px 10px;"
               value="<?= esc($tanggal) ?>">
        <span style="font-size:.85rem;color:var(--text-muted);">
            Pilih kelas di bawah untuk mulai absensi pada tanggal ini.
        </span>
    </div>
</div>

<!-- Grid kelas dikelompokkan per tingkat -->
<?php
$grouped = [];
foreach ($kelas_list as $k) {
    $grouped[$k['tingkat'] ?? 'Lainnya'][] = $k;
}
uksort($grouped, fn($a, $b) => strcmp($a, $b));
?>

<?php foreach ($grouped as $tingkat => $kelasTingkat): ?>
<div class="card" style="margin-bottom:16px;">
    <div class="card-header">
        <div class="card-title">Kelas <?= esc($tingkat) ?></div>
        <span style="font-size:.82rem;color:var(--text-muted);"><?= count($kelasTingkat) ?> kelas</span>
    </div>
    <div style="padding:14px 20px;display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:10px;">
        <?php foreach ($kelasTingkat as $k): ?>
            <a href="#" class="kelas-card"
               data-kelas="<?= $k['id_kelas'] ?>"
               style="display:flex;align-items:center;gap:12px;background:var(--bg-secondary);border:1.5px solid var(--border);border-radius:10px;padding:14px 16px;text-decoration:none;color:inherit;transition:.15s;">
                <div style="background:#f0fdf4;color:#16a34a;border-radius:8px;padding:10px;font-size:1.2rem;flex-shrink:0;">
                    <i class="ri-building-4-line"></i>
                </div>
                <div style="min-width:0;">
                    <strong style="font-size:.92rem;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        <?= esc($k['nama_kelas']) ?>
                    </strong>
                    <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        <?= esc($k['nama_jurusan'] ?? '-') ?>
                        &nbsp;·&nbsp;
                        <span style="color:#16a34a;font-weight:600;"><?= esc($k['jumlah_siswa'] ?? 0) ?> siswa</span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endforeach; ?>

<?php if (empty($kelas_list)): ?>
    <div class="card">
        <div class="empty-state" style="padding:40px;">
            <i class="ri-building-4-line"></i>
            <p>Belum ada kelas yang tersedia</p>
        </div>
    </div>
<?php endif; ?>

<?php else: ?>
<!-- ===================================================================== -->
<!-- STEP 2 — Form absensi kelas yang dipilih                              -->
<!-- ===================================================================== -->

<!-- Stat kelas -->
<div class="stats-grid" style="margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4;color:#16a34a;"><i class="ri-building-4-line"></i></div>
        <div class="stat-text"><p>Kelas</p><h4><?= esc($kelas['nama_kelas']) ?></h4></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4;color:#16a34a;"><i class="ri-award-line"></i></div>
        <div class="stat-text"><p>Jurusan</p><h4><?= esc($kelas['nama_jurusan'] ?? '-') ?></h4></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4;color:#16a34a;"><i class="ri-user-star-line"></i></div>
        <div class="stat-text"><p>Wali Kelas</p><h4><?= esc($kelas['nama_guru'] ?? '-') ?></h4></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4;color:#16a34a;"><i class="ri-calendar-check-line"></i></div>
        <div class="stat-text">
            <p>Tanggal</p>
            <h4><?= date('d M Y', strtotime($tanggal)) ?></h4>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <div class="card-title">
                Daftar Siswa
                <span style="font-size:.82rem;font-weight:400;color:var(--text-muted);margin-left:6px;">
                    — <?= count($siswa_list) ?> siswa
                </span>
            </div>
            <!-- Ganti tanggal tanpa pindah kelas -->
            <form method="get" style="display:flex;gap:8px;align-items:center;">
                <input type="hidden" name="kelas" value="<?= esc($filter_kelas) ?>">
                <input type="date" name="tanggal" class="form-control"
                       style="width:160px;padding:7px 10px;font-size:.85rem;"
                       value="<?= esc($tanggal) ?>">
                <button type="submit" class="btn btn-secondary btn-sm">
                    <i class="ri-calendar-line"></i> Ganti Tanggal
                </button>
            </form>
        </div>
        <!-- Tandai semua -->
        <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
            <span style="font-size:.8rem;color:var(--text-muted);">Tandai semua:</span>
            <?php
            $quickColors = [
                'Hadir' => ['#16a34a', '#fff'],
                'Izin'  => ['#d97706', '#fff'],
                'Sakit' => ['#2563eb', '#fff'],
                'Alpa'  => ['#dc2626', '#fff'],
            ];
            foreach ($status_list as $st):
                [$bg, $fg] = $quickColors[$st] ?? ['#6b7280', '#fff'];
            ?>
                <button type="button" class="btn btn-sm quick-all"
                        data-status="<?= esc($st) ?>"
                        style="background:<?= $bg ?>;color:<?= $fg ?>;">
                    <?= esc($st) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <form method="post"
          action="<?= base_url('absensi/harian/simpan') ?>"
          id="form-harian">
        <?= csrf_field() ?>
        <input type="hidden" name="tanggal"  value="<?= esc($tanggal) ?>">
        <input type="hidden" name="id_kelas" value="<?= esc($filter_kelas) ?>">

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="55">Foto</th>
                        <th width="145">NISN</th>
                        <th>Nama Siswa</th>
                        <th width="360">Status</th>
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
                        <?php
                        $colorMap = [
                            'Hadir' => ['border' => '#86efac', 'active' => '#16a34a', 'text' => '#15803d'],
                            'Izin'  => ['border' => '#fcd34d', 'active' => '#d97706', 'text' => '#92400e'],
                            'Sakit' => ['border' => '#93c5fd', 'active' => '#2563eb', 'text' => '#1e40af'],
                            'Alpa'  => ['border' => '#fca5a5', 'active' => '#dc2626', 'text' => '#991b1b'],
                        ];
                        foreach ($siswa_list as $i => $s):
                            $current = $absensi_map[$s['id_siswa']]['status'] ?? 'Hadir';
                            $note    = $absensi_map[$s['id_siswa']]['keterangan'] ?? '';
                        ?>
                            <tr data-siswa="<?= $s['id_siswa'] ?>">
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <?php if (! empty($s['foto'])): ?>
                                        <img src="<?= base_url('uploads/foto_siswa/' . $s['foto']) ?>"
                                             style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                                    <?php else: ?>
                                        <div style="width:36px;height:36px;border-radius:50%;background:#eff6ff;color:var(--primary);display:flex;align-items:center;justify-content:center;">
                                            <i class="ri-user-line"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><code style="font-size:.82rem;"><?= esc($s['nisn']) ?></code></td>
                                <td><strong><?= esc($s['nama_siswa']) ?></strong></td>
                                <td>
                                    <div class="attendance-options">
                                        <?php foreach ($status_list as $status):
                                            $c = $colorMap[$status] ?? $colorMap['Hadir'];
                                            $isActive = $current === $status;
                                        ?>
                                            <label class="attendance-option <?= $isActive ? 'active' : '' ?>"
                                                   data-color="<?= $c['active'] ?>"
                                                   data-border="<?= $c['border'] ?>"
                                                   data-text="<?= $c['text'] ?>"
                                                   style="<?= $isActive
                                                       ? 'background:' . $c['active'] . ';border-color:' . $c['active'] . ';color:#fff;'
                                                       : 'border-color:' . $c['border'] . ';color:' . $c['text'] . ';' ?>">
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
                                           style="<?= in_array($current, ['Izin', 'Sakit']) ? '' : 'display:none;' ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (! empty($siswa_list)): ?>
            <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
                <a href="<?= base_url('absensi/harian') ?>" class="btn btn-secondary">
                    <i class="ri-grid-line"></i> Ganti Kelas
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i> Simpan Absensi Harian
                </button>
            </div>
        <?php endif; ?>
    </form>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();

$extra_css = <<<CSS
<style>
.kelas-card:hover {
    border-color: var(--primary) !important;
    background: var(--bg-primary) !important;
}
.attendance-options { display:flex; gap:6px; flex-wrap:wrap; }
.attendance-option {
    position:relative; border:1px solid #86efac; color:#15803d;
    border-radius:6px; padding:7px 12px; font-size:.8rem; font-weight:700;
    cursor:pointer; background:#fff; transition:.15s; user-select:none;
}
.attendance-option input { position:absolute; opacity:0; pointer-events:none; }
.attendance-option:hover { filter:brightness(.95); }
</style>
CSS;

$extra_js = <<<JS
<script>
// Klik kelas → navigate dengan tanggal yang dipilih
document.querySelectorAll('.kelas-card').forEach(card => {
    card.addEventListener('click', e => {
        e.preventDefault();
        const tanggal = document.getElementById('input-tanggal')?.value
            || '<?= esc($tanggal) ?>';
        const idKelas = card.dataset.kelas;
        window.location.href = '<?= base_url('absensi/harian') ?>?kelas=' + idKelas + '&tanggal=' + tanggal;
    });
});

// Toggle status + keterangan
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

// Tandai semua siswa sekaligus
document.querySelectorAll('.quick-all').forEach(btn => {
    btn.addEventListener('click', () => {
        const target = btn.dataset.status;
        document.querySelectorAll('tr[data-siswa]').forEach(row => {
            const radio = row.querySelector('input[type="radio"][value="' + target + '"]');
            if (radio) {
                radio.checked = true;
                radio.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });
});
</script>
JS;

echo view('Template/layout', [
    'title'     => 'Absensi Harian',
    'subtitle'  => 'Input presensi harian per kelas',
    'content'   => $content,
    'extra_css' => $extra_css,
    'extra_js'  => $extra_js,
]);
?>