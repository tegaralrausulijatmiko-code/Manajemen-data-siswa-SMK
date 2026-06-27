<?php ob_start(); ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="ri-dashboard-line" style="color:var(--primary);"></i> 
            Selamat Datang, <?= esc(session()->get('nama')) ?>
        </div>
    </div>
    <div style="padding:30px; text-align:center;">
        <i class="ri-user-star-line" style="font-size:4rem; color:var(--primary); margin-bottom:15px;"></i>
        <h2>Dashboard Guru</h2>
        <p style="color:var(--text-light); margin-bottom:25px;">
            Gunakan menu di bawah ini untuk mengelola aktivitas absensi kelas Anda.
        </p>
        
        <div style="display:flex; justify-content:center; gap:20px; flex-wrap:wrap;">
            <a href="<?= base_url('guru/absensi') ?>" class="btn btn-primary" style="padding:15px 25px; font-size:1.1rem;">
                <i class="ri-checkbox-circle-line"></i> Input Absensi Kelas
            </a>
            <a href="<?= base_url('guru/absensi/rekap') ?>" class="btn btn-secondary" style="padding:15px 25px; font-size:1.1rem; background:#64748b; color:white;">
                <i class="ri-file-list-3-line"></i> Rekap Absensi Siswa
            </a>
        </div>
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