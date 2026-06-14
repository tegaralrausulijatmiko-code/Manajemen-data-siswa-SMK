<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Rekap Absensi</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / <a href="<?= base_url('absensi') ?>">Absensi</a> / Rekap</div>
    </div>
    <a href="<?= base_url('absensi') ?>" class="btn btn-secondary btn-sm"><i class="ri-arrow-left-line"></i> Kembali</a>
</div>

<div class="stats-grid">
    <?php foreach (['Total' => 'ri-file-list-3-line', 'Hadir' => 'ri-checkbox-circle-line', 'Izin' => 'ri-mail-check-line', 'Sakit' => 'ri-first-aid-kit-line', 'Alpa' => 'ri-close-circle-line'] as $label => $icon): ?>
        <div class="stat-card">
            <div class="stat-icon"><i class="<?= esc($icon) ?>"></i></div>
            <div class="stat-text">
                <p><?= esc($label) ?></p>
                <h4><?= esc($summary[$label] ?? 0) ?></h4>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Filter Rekap</div>
        <a href="<?= base_url('absensi/rekap/export?' . http_build_query(array_filter($filters))) ?>" class="btn btn-primary btn-sm"><i class="ri-file-excel-2-line"></i> Export Excel</a>
    </div>
    <form method="get" style="padding:20px;">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" class="form-control" value="<?= esc($filters['tanggal_awal'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control" value="<?= esc($filters['tanggal_akhir'] ?? '') ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Kelas</label>
                <select name="kelas" class="form-control">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($kelas_list as $kelas): ?>
                        <option value="<?= $kelas['id_kelas'] ?>" <?= ($filters['id_kelas'] ?? '') == $kelas['id_kelas'] ? 'selected' : '' ?>><?= esc($kelas['nama_kelas']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <?php foreach ($status_list as $status): ?>
                        <option value="<?= esc($status) ?>" <?= ($filters['status'] ?? '') === $status ? 'selected' : '' ?>><?= esc($status) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Jadwal</label>
            <select name="jadwal" class="form-control">
                <option value="">Semua Jadwal</option>
                <?php foreach ($jadwal_list as $jadwal): ?>
                    <?php $label = ($jadwal['nama_kelas'] ?? '-') . ' - ' . ($jadwal['nama_mapel'] ?? '-') . ' (' . $jadwal['hari'] . ', ' . substr($jadwal['jam_mulai'], 0, 5) . ')'; ?>
                    <option value="<?= $jadwal['id_jadwal'] ?>" <?= ($filters['id_jadwal'] ?? '') == $jadwal['id_jadwal'] ? 'selected' : '' ?>><?= esc($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-sm"><i class="ri-filter-line"></i> Terapkan</button>
            <a href="<?= base_url('absensi/rekap') ?>" class="btn btn-secondary btn-sm"><i class="ri-refresh-line"></i> Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Data Rekap</div>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Jadwal</th>
                    <th>Mapel</th>
                    <th>Guru</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rekap)): ?>
                    <tr><td colspan="9"><div class="empty-state"><i class="ri-file-search-line"></i><p>Tidak ada data rekap absensi</p></div></td></tr>
                <?php else: ?>
                    <?php foreach ($rekap as $i => $row): ?>
                        <tr>
                            <td><?= (($pagination['page'] ?? 1) - 1) * ($pagination['per_page'] ?? 20) + $i + 1 ?></td>
                            <td><?= esc(date('d/m/Y', strtotime($row['tanggal']))) ?></td>
                            <td><strong><?= esc($row['nama_siswa'] ?? '-') ?></strong><br><small><?= esc($row['nisn'] ?? '-') ?></small></td>
                            <td><?= esc($row['nama_kelas'] ?? '-') ?></td>
                            <td><?= esc(($row['hari'] ?? '-') . ', ' . substr($row['jam_mulai'] ?? '', 0, 5) . ' - ' . substr($row['jam_selesai'] ?? '', 0, 5)) ?></td>
                            <td><?= esc($row['nama_mapel'] ?? '-') ?></td>
                            <td><?= esc($row['nama_guru'] ?? '-') ?></td>
                            <td><span class="badge <?= $row['status'] === 'Hadir' ? 'badge-aktif' : 'badge-warning' ?>"><?= esc($row['status']) ?></span></td>
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
echo view('Template/layout', [
    'title' => 'Rekap Absensi',
    'subtitle' => 'Laporan dan export absensi siswa',
    'content' => $content,
]);
?>
