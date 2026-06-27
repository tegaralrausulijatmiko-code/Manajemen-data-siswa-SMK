<?php ob_start(); ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="ri-dashboard-line" style="color:var(--primary);"></i> 
            Selamat Datang, <?= esc(session()->get('nama')) ?>
        </div>
    </div>
    <div style="padding:30px; text-align:center;">
        <i class="ri-mental-health-line" style="font-size:4rem; color:var(--primary); margin-bottom:15px;"></i>
        <h2>Dashboard Bimbingan Konseling</h2>
        <p style="color:var(--text-light); margin-bottom:25px;">
            Gunakan menu di bawah ini untuk melihat rekap absensi siswa seluruh kelas.
        </p>
        
        <div style="display:flex; justify-content:center; gap:20px; flex-wrap:wrap;">
            <a href="<?= base_url('bk/rekap') ?>" class="btn btn-primary" style="padding:15px 25px; font-size:1.1rem;">
                <i class="ri-file-list-3-line"></i> Lihat Rekap Absensi
            </a>
            <a href="<?= base_url('bk/rekap/export') ?>" class="btn btn-secondary" style="padding:15px 25px; font-size:1.1rem; background:#64748b; color:white;">
                <i class="ri-download-line"></i> Export Rekap Excel
            </a>
        </div>
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