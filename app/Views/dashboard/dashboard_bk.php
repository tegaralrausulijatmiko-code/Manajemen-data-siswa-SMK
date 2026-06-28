<?php ob_start(); ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="ri-team-line"></i></div>
        <div class="stat-text">
            <h4><?= $stats['total_siswa'] ?? 0 ?></h4>
            <p>Total Siswa</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4; color:#16a34a;"><i class="ri-checkbox-circle-line"></i></div>
        <div class="stat-text">
            <h4><?= $stats['hadir'] ?? 0 ?></h4>
            <p>Hadir Hari Ini</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef2f2; color:#dc2626;"><i class="ri-close-circle-line"></i></div>
        <div class="stat-text">
            <h4><?= $stats['alpha'] ?? 0 ?></h4>
            <p>Alpha Hari Ini</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff7ed; color:#ea580c;"><i class="ri-error-warning-line"></i></div>
        <div class="stat-text">
            <h4><?= ($stats['sakit'] ?? 0) + ($stats['izin'] ?? 0) ?></h4>
            <p>Sakit/Izin Hari Ini</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="ri-alarm-warning-line" style="color:var(--primary);"></i> 
            Top 5 Siswa Sering Alpha (Bulan Ini)
        </div>
        <a href="<?= base_url('bk/rekap') ?>" class="btn btn-primary btn-sm">
            <i class="ri-file-list-3-line"></i> Lihat Semua Rekap
        </a>
    </div>
    <div style="padding:20px;">
        <?php if (empty($top_alpha)): ?>
            <div class="empty-state">
                <i class="ri-emotion-happy-line"></i>
                <p>Belum ada data siswa yang Alpha bulan ini. Pertahankan!</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Total Alpha</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_alpha as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= esc($row['nama_siswa']) ?></td>
                            <td><?= esc($row['nama_kelas'] ?? '-') ?></td>
                            <td><span class="badge badge-danger"><?= $row['total_alpha'] ?>x</span></td>
                            <td>
                                <a href="<?= base_url('bk/rekap?status=Alpha') ?>" class="btn btn-secondary btn-sm">
                                    <i class="ri-eye-line"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
 $content = ob_get_clean();
echo view('Template/layout', [
    'title'    => 'Dashboard BK',
    'subtitle' => 'Sistem Informasi Sekolah',
    'content'  => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [],
]);
?>