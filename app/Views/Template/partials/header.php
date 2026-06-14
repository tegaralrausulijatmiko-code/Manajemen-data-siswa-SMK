<header>
    <div class="header-info">
        <?php // Header ini menggunakan variabel $title yang dikirim dari setiap view atau controller.
              // Contoh: echo view('Template/layout', ['title' => 'Daftar Mapel', ...]); ?>
        <h2><?= esc($title ?? '') ?></h2>
        <p>Sistem Informasi Sekolah</p>
    </div>
    <?php if (session()->get('is_logged_in')): ?>
        <div class="header-right">
            <strong><?= esc(session()->get('nama')) ?></strong><br>
            <span><?= esc(ucfirst(session()->get('role'))) ?></span>
            <div style="margin-top:6px;">
                <a href="<?= base_url('logout') ?>" class="btn btn-secondary btn-sm">
                    <i class="ri-logout-circle-line"></i> Logout
                </a>
            </div>
        </div>
    <?php endif; ?>
</header>
