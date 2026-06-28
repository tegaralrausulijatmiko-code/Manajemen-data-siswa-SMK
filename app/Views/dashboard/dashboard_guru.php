<?php ob_start(); ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="ri-calendar-check-line"></i></div>
        <div class="stat-text">
            <h4><?= count($jadwal_hari_ini) ?></h4>
            <p>Jadwal Hari Ini (<?= esc($hari_ini) ?>)</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4; color:#16a34a;"><i class="ri-building-4-line"></i></div>
        <div class="stat-text">
            <h4><?= $total_kelas ?></h4>
            <p>Total Kelas Diajar</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fdf4ff; color:#9333ea;"><i class="ri-user-star-line"></i></div>
        <div class="stat-text">
            <h4><?= $kelas_wali ? esc($kelas_wali['nama_kelas']) : 'Bukan' ?></h4>
            <p>Wali Kelas</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="ri-calendar-event-line" style="color:var(--primary);"></i> 
            Jadwal Mengajar Hari Ini (<?= esc($hari_ini) ?>)
        </div>
        <a href="<?= base_url('guru/absensi') ?>" class="btn btn-primary btn-sm">
            <i class="ri-add-line"></i> Input Absensi
        </a>
    </div>
    <div style="padding:20px;">
        <?php if (empty($jadwal_hari_ini)): ?>
            <div class="empty-state">
                <i class="ri-emotion-happy-line"></i>
                <p>Hari ini Anda tidak ada jadwal mengajar. Selamat istirahat!</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Jam</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jadwal_hari_ini as $j): ?>
                        <tr>
                            <td><?= esc(substr($j['jam_mulai'],0,5)) ?> - <?= esc(substr($j['jam_selesai'],0,5)) ?></td>
                            <td><?= esc($j['nama_kelas']) ?></td>
                            <td><?= esc($j['nama_mapel']) ?></td>
                            <td>
                                <a href="<?= base_url('guru/absensi/jadwal/' . $j['id_jadwal']) ?>" class="btn btn-primary btn-sm">
                                    <i class="ri-edit-line"></i> Isi Absen
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
    'title'    => 'Dashboard Guru',
    'subtitle' => 'Sistem Informasi Sekolah',
    'content'  => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [],
]);
?>