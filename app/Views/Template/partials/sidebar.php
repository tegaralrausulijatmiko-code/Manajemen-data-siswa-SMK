

<nav class="sidebar">
    <div class="brand">
        <i class="ri-graduation-cap-fill" style="font-size:1.3rem; vertical-align:middle;"></i>
        SMK Harapan Bangsa
        <small>T.A. 2025/2026</small>
    </div>

    <ul class="nav-menu">

        <li class="nav-item">
            <a href="<?= base_url('dashboard') ?>" class="nav-link">
                <i class="ri-dashboard-line"></i> Dashboard
            </a>
        </li>

        <?php if (session()->get('role') === 'admin'): ?>

        <!-- DATA MASTER -->
        <li class="nav-title">DATA MASTER</li>

        <li class="nav-item">
            <a href="<?= base_url('jurusan') ?>" class="nav-link">
                <i class="ri-book-open-line"></i> Jurusan
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('kelas') ?>" class="nav-link">
                <i class="ri-building-4-line"></i> Kelas
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('siswa') ?>" class="nav-link">
                <i class="ri-user-smile-line"></i> Siswa
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('guru') ?>" class="nav-link">
                <i class="ri-user-star-line"></i> Guru
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('mapel') ?>" class="nav-link">
                <i class="ri-book-read-line"></i> Mata Pelajaran
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('jadwal') ?>" class="nav-link">
                <i class="ri-calendar-2-line"></i> Jadwal
            </a>
        </li>
        
        <!-- PRESENSI -->
        <li class="nav-title">PRESENSI</li>
        
        <li class="nav-item">
            <a href="<?= base_url('admin/absensi/mapel') ?>" class="nav-link">
                <i class="ri-calendar-2-line"></i> Absensi Mapel
            </a>
        </li>
        
        
        <li class="nav-item">
            <a href="<?= base_url('admin/absensi/harian') ?>" class="nav-link">
                <i class="ri-calendar-check-line"></i> Absensi Harian
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('admin/absensi/rekap') ?>" class="nav-link">
                <i class="ri-file-chart-line"></i> Rekap Absensi
            </a>
        </li>

        <!-- MANAJEMEN -->
        <li class="nav-title">MANAJEMEN</li>

        <li class="nav-item">
            <a href="<?= base_url('user') ?>" class="nav-link">
                <i class="ri-user-settings-line"></i> User
            </a>
        </li>

        <?php endif; ?>

        <?php if (session()->get('role') === 'guru'): ?>

        <li class="nav-title">PRESENSI</li>

        <li class="nav-item">
            <a href="<?= base_url('guru/absensi') ?>" class="nav-link">
                <i class="ri-calendar-check-line"></i> Absensi Kelas
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('guru/absensi/rekap') ?>" class="nav-link">
                <i class="ri-file-chart-line"></i> Rekap Absensi
            </a>
        </li>

        <?php endif; ?>

        <?php if (session()->get('role') === 'bk'): ?>

        <li class="nav-title">PRESENSI</li>

        <li class="nav-item">
            <a href="<?= base_url('bk/absensi/rekap') ?>" class="nav-link">
                <i class="ri-file-chart-line"></i> Rekap Absensi
            </a>
        </li>

        <?php endif; ?>

    </ul>

    <div class="sidebar-footer">
        &copy; <?= date('Y') ?> SMK Harapan Bangsa
    </div>
</nav>