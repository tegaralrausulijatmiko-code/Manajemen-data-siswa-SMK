<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Rekap Absensi</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('absensi') ?>">Absensi</a> /
            Rekap
        </div>
    </div>
    <div style="display:flex; gap:8px;">
        <a href="<?= base_url('absensi/rekap/export?' . http_build_query(array_filter($filters))) ?>"
           class="btn btn-primary btn-sm">
            <i class="ri-file-excel-2-line"></i> Export Excel
        </a>
        <!-- <a href="<?= base_url('absensi') ?>" class="btn btn-secondary btn-sm">
            <i class="ri-arrow-left-line"></i> Kembali
        </a> -->
    </div>
</div>

<!-- Statistik -->
<div class="stats-grid" style="margin-bottom:20px;">
    <?php
    $statCards = [
        'Total'     => ['icon' => 'ri-file-list-3-line',     'color' => 'var(--primary)'],
        'Hadir'     => ['icon' => 'ri-checkbox-circle-line', 'color' => '#16a34a'],
        'Izin'      => ['icon' => 'ri-mail-check-line',      'color' => '#d97706'],
        'Sakit'     => ['icon' => 'ri-heart-pulse-line',     'color' => '#2563eb'],
        'Alpha'      => ['icon' => 'ri-close-circle-line',    'color' => '#dc2626'],
    ];
    foreach ($statCards as $label => $cfg):
    ?>
        <div class="stat-card">
            <div class="stat-icon" style="color:<?= $cfg['color'] ?>;">
                <i class="<?= esc($cfg['icon']) ?>"></i>
            </div>
            <div class="stat-text">
                <p><?= esc($label) ?></p>
                <h4><?= esc($summary[$label] ?? 0) ?></h4>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Filter -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div class="card-title">Filter Rekap</div>
        <div style="display:flex; gap:8px;">
            <button type="submit" form="form-filter" class="btn btn-primary btn-sm">
                <i class="ri-filter-line"></i> Terapkan
            </button>
            <a href="<?= base_url('admin/absensi/rekap') ?>" class="btn btn-secondary btn-sm">
                <i class="ri-refresh-line"></i> Reset
            </a>
        </div>
    </div>
    <form id="form-filter" method="get" style="padding:16px 20px;">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" class="form-control"
                       value="<?= esc($filters['tanggal_awal'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control"
                       value="<?= esc($filters['tanggal_akhir'] ?? '') ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Jenis Absensi</label>
                <select name="jenis" class="form-control">
                    <option value="">Semua Jenis</option>
                    <option value="harian"  <?= ($filters['jenis'] ?? '') === 'harian'  ? 'selected' : '' ?>>Absen Harian </option>
                    <option value="mapel"   <?= ($filters['jenis'] ?? '') === 'mapel'   ? 'selected' : '' ?>>Absen Mata Pelajaran</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Kelas</label>
                <select name="kelas" class="form-control">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($kelas_list as $k): ?>
                        <option value="<?= $k['id_kelas'] ?>"
                            <?= ($filters['id_kelas'] ?? '') == $k['id_kelas'] ? 'selected' : '' ?>>
                            <?= esc($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Jadwal (Mapel)</label>
                <select name="jadwal" class="form-control">
                    <option value="">Semua Jadwal</option>
                    <?php foreach ($jadwal_list as $jadwal):
                        $lbl = ($jadwal['nama_kelas'] ?? '-')
                             . ' – ' . ($jadwal['nama_mapel'] ?? '-')
                             . ' (' . $jadwal['hari'] . ', ' . substr($jadwal['jam_mulai'], 0, 5) . ')';
                    ?>
                        <option value="<?= $jadwal['id_jadwal'] ?>"
                            <?= ($filters['id_jadwal'] ?? '') == $jadwal['id_jadwal'] ? 'selected' : '' ?>>
                            <?= esc($lbl) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>
</div>

<!-- Tabel Rekap -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            Data Rekap
            <?php if (! empty($rekap)): ?>
                <span style="font-weight:400; font-size:0.82rem; color:var(--text-muted); margin-left:8px;">
                    — <?= $pagination['total'] ?? count($rekap) ?> record
                </span>
            <?php endif; ?>
        </div>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th width="100">Tanggal</th>
                    <th width="80">Jenis</th>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Jadwal / Mapel</th>
                    <th>Guru</th>
                    <th width="110">Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rekap)): ?>
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <i class="ri-file-search-line"></i>
                                <p>Tidak ada data rekap absensi</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                    $offset = (($pagination['page'] ?? 1) - 1) * ($pagination['per_page'] ?? 20);
                    foreach ($rekap as $i => $row):
                        // Badge status
                        $statusBadge = match($row['status']) {
                            'Hadir'     => 'badge-aktif',
                            'Izin'      => 'badge-warning',
                            'Sakit'     => 'badge-info',
                            'Alpha'      => 'badge-nonaktif',
                            default     => 'badge-light',
                        };

                        // Jadwal info
                        $isHarian = ($row['jenis'] ?? 'mapel') === 'harian';
                        if ($isHarian) {
                            $jadwalInfo = '<span style="color:var(--text-muted); font-size:0.82rem;">— Absen Harian —</span>';
                        } else {
                            $jam = trim(substr($row['jam_mulai'] ?? '', 0, 5) . '–' . substr($row['jam_selesai'] ?? '', 0, 5), '–');
                            $jadwalInfo = '<strong>' . esc($row['nama_mapel'] ?? '-') . '</strong>'
                                        . '<br><small>' . esc($row['hari'] ?? '-') . ', ' . esc($jam ?: '-') . '</small>';
                        }
                    ?>
                        <tr>
                            <td><?= $offset + $i + 1 ?></td>
                            <td><?= esc(date('d/m/Y', strtotime($row['tanggal']))) ?></td>
                            <td>
                                <?php if ($isHarian): ?>
                                    <span class="badge" style="background:#f0fdf4; color:#15803d; border:1px solid #86efac;">Harian</span>
                                <?php else: ?>
                                    <span class="badge" style="background:#eff6ff; color:#1d4ed8; border:1px solid #93c5fd;">Mapel</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= esc($row['nama_siswa'] ?? '-') ?></strong>
                                <br><small style="color:var(--text-muted);"><?= esc($row['nisn'] ?? '-') ?></small>
                            </td>
                            <td><?= esc($row['nama_kelas'] ?? '-') ?></td>
                            <td><?= $jadwalInfo ?></td>
                            <td><?= esc($row['nama_guru'] ?? '-') ?></td>
                            <td><span class="badge <?= $statusBadge ?>"><?= esc($row['status']) ?></span></td>
                            <td><?= esc($row['keterangan'] ?: '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?= view('Template/partials/pagination', ['pagination' => $pagination ?? null]) ?>
</div>

<?php
$content = ob_get_clean();

$extra_css = <<<CSS
<style>
/* Badge tambahan */
.badge-info      { background:#eff6ff; color:#1d4ed8; }
.badge-light     { background:#f3f4f6; color:#6b7280; }
</style>
CSS;

echo view('Template/layout', [
    'title'     => 'Rekap Absensi',
    'subtitle'  => 'Laporan dan export absensi siswa',
    'content'   => $content,
    'extra_css' => $extra_css,
]);
?>