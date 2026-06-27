<?php ob_start(); ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="ri-dashboard-line" style="color:var(--primary);"></i> 
            Selamat Datang, <?= esc(session()->get('nama')) ?>
        </div>
    </div>
    <div style="padding:30px; text-align:center;">
        <i class="ri-graduation-cap-line" style="font-size:4rem; color:var(--primary); margin-bottom:15px;"></i>
        <h2>Dashboard Siswa</h2>
        <p style="color:var(--text-light); margin-bottom:25px;">
            Ini adalah halaman utama Anda sebagai siswa. Nantinya menu untuk melihat jadwal dan rekap absensi Anda dapat diakses melalui menu di sebelah kiri.
        </p>
    </div>
</div>

<?php
 $content = ob_get_clean();
echo view('Template/layout', [
    'title'    => 'Dashboard Siswa',
    'subtitle' => 'Sistem Informasi Sekolah',
    'content'  => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [],
]);
?>