<?php ob_start(); ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="ri-book-open-line"></i></div>
        <div class="stat-text">
            <h4><?= $stats['jurusan'] ?></h4>
            <p>Jurusan</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4; color:#16a34a;"><i class="ri-building-4-line"></i></div>
        <div class="stat-text">
            <h4><?= $stats['kelas'] ?></h4>
            <p>Kelas Aktif</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fdf4ff; color:#9333ea;"><i class="ri-user-smile-line"></i></div>
        <div class="stat-text">
            <h4><?= $stats['siswa'] ?></h4>
            <p>Total Siswa</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0f9ff; color:#0284c7;"><i class="ri-user-star-line"></i></div>
        <div class="stat-text">
            <h4><?= $stats['guru'] ?></h4>
            <p>Total Guru</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff7ed; color:#ea580c;"><i class="ri-book-read-line"></i></div>
        <div class="stat-text">
            <h4><?= $stats['mapel'] ?></h4>
            <p>Mata Pelajaran</p>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="ri-user-smile-line" style="color:var(--primary);"></i> Siswa Terbaru</div>
        <a href="<?= base_url('siswa/tambah') ?>" class="btn btn-primary btn-sm">
            <i class="ri-add-line"></i> Tambah Siswa
        </a>
    </div>
    <div style="padding:20px;">
        <?php if (empty($siswa_terbaru)): ?>
            <div class="empty-state">
                <i class="ri-user-smile-line"></i>
                <p>Belum ada data siswa</p>
            </div>
        <?php else: ?>
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:14px;">
                <?php foreach ($siswa_terbaru as $s): ?>
                    <?php $foto = ! empty($s['foto']) ? base_url('uploads/' . $s['foto']) : null; ?>
                    <a href="<?= base_url('siswa/edit/' . $s['id_siswa']) ?>" style="display:flex; align-items:center; gap:12px; padding:12px; border:1px solid var(--border); border-radius:8px; text-decoration:none; color:inherit; background:#fff;">
                        <?php if ($foto): ?>
                            <img src="<?= $foto ?>" alt="Foto <?= esc($s['nama_siswa']) ?>" style="width:52px; height:52px; border-radius:8px; object-fit:cover; flex-shrink:0;">
                        <?php else: ?>
                            <div style="width:52px; height:52px; border-radius:8px; background:#eff6ff; color:var(--primary); display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0;">
                                <i class="ri-user-line"></i>
                            </div>
                        <?php endif; ?>
                        <div style="min-width:0;">
                            <strong style="display:block; font-size:0.92rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?= esc($s['nama_siswa']) ?></strong>
                            <span style="display:block; font-size:0.78rem; color:var(--text-light); margin-top:3px;">
                                <?= esc($s['nisn']) ?> &bull; <?= esc($s['nama_kelas'] ?? '-') ?>
                            </span>
                            <span class="badge <?= ($s['jenis_kelamin'] ?? '') == 'L' ? 'badge-l' : 'badge-p' ?>" style="margin-top:7px;">
                                <?= ($s['jenis_kelamin'] ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="ri-book-open-line" style="color:var(--primary);"></i> Program Keahlian</div>
        </div>
        <div style="padding:20px;">
            <?php foreach ($jurusan_list as $j): ?>
            <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--border);">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; background:#eff6ff; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; color:var(--primary);">
                        <?= esc($j['kode_jurusan']) ?>
                    </div>
                    <span style="font-size:0.9rem;"><?= esc($j['nama_jurusan']) ?></span>
                </div>
                <span class="badge badge-prod"><?= $j['jumlah_kelas'] ?> Kelas</span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="ri-pie-chart-line" style="color:var(--primary);"></i> Distribusi Siswa per Jurusan</div>
        </div>
        <div style="padding:20px;">
            <?php foreach ($jurusan_list as $j): ?>
            <?php $pct = $stats['siswa'] > 0 ? round($j['jumlah_siswa'] / $stats['siswa'] * 100) : 0; ?>
            <div style="margin-bottom:14px;">
                <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:5px;">
                    <span><?= esc($j['kode_jurusan']) ?> – <?= esc($j['nama_jurusan']) ?></span>
                    <span style="font-weight:600;"><?= $j['jumlah_siswa'] ?> siswa</span>
                </div>
                <div style="background:#e2e8f0; border-radius:20px; height:8px;">
                    <div style="background:var(--primary); height:8px; border-radius:20px; width:<?= $pct ?>%;"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'    => 'Data Siswa SMK Harapan Bangsa',
    'subtitle' => 'Sistem Informasi Sekolah',
    'content'  => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [
        'total' => $stats['siswa'] ?? 0,
    ],
]);
?>
