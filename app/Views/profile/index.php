<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Profil Saya</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / Profil</div>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1.5fr; gap:20px;">

    <!-- Kartu Info User -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Informasi Akun</div>
        </div>
        <div style="padding:20px; text-align:center;">
            <div style="width:80px; height:80px; border-radius:50%; background:var(--primary); color:#fff; display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:700; margin:0 auto 15px;">
                <?= strtoupper(substr(esc($user['nama']), 0, 1)) ?>
            </div>
            <h3 style="margin-bottom:5px;"><?= esc($user['nama']) ?></h3>
            <span class="badge badge-admin" style="background:#6f42c1; color:#fff; text-transform:capitalize; padding:5px 12px; border-radius:20px;">
                <?= esc($user['role']) ?>
            </span>
            <hr style="margin:20px 0; border-color:var(--border);">
            <div style="text-align:left;">
                <p style="margin-bottom:10px;"><strong>Username:</strong><br><code style="background:var(--bg-secondary); padding:3px 8px; border-radius:4px;"><?= esc($user['username']) ?></code></p>
                <p style="margin-bottom:10px;"><strong>Email:</strong><br><?= esc($user['email'] ?? '-') ?></p>
                <p style="margin-bottom:0;"><strong>Status:</strong><br><span class="badge badge-aktif">Aktif</span></p>
            </div>
        </div>
    </div>

    <!-- Form Ganti Password -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Ubah Password</div>
        </div>
        <div style="padding:20px;">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger" style="background:#fee2e2; color:#991b1b; padding:12px 16px; border-radius:8px; margin-bottom:15px; font-size:13px; border:1px solid #fecaca;">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success" style="background:#dcfce7; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:15px; font-size:13px; border:1px solid #bbf7d0;">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('profile/update-password') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div style="margin-bottom:15px;">
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Password Lama</label>
                    <input type="password" name="password_lama" class="form-control" required style="width:100%; padding:10px; border-radius:8px; border:1px solid var(--border);">
                </div>

                <div style="margin-bottom:15px;">
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Password Baru</label>
                    <input type="password" name="password_baru" class="form-control" required style="width:100%; padding:10px; border-radius:8px; border:1px solid var(--border);">
                    <small style="color:var(--text-light); font-size:12px;">Minimal 6 karakter.</small>
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Konfirmasi Password Baru</label>
                    <input type="password" name="konfirmasi_password" class="form-control" required style="width:100%; padding:10px; border-radius:8px; border:1px solid var(--border);">
                </div>

                <button type="submit" class="btn btn-primary" style="padding:10px 20px;">
                    <i class="ri-save-3-line"></i> Simpan Password Baru
                </button>
            </form>
        </div>
    </div>

</div>

<?php
 $content = ob_get_clean();
echo view('Template/layout', [
    'title'       => 'Profil Saya',
    'subtitle'    => 'Sistem Informasi Sekolah',
    'content'     => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [],
]);
?>