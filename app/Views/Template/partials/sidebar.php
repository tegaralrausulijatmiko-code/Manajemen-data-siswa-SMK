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
        <li class="nav-item">
            <a href="<?= base_url('tahun-ajaran') ?>" class="nav-link">
                <i class="ri-calendar-line"></i> Tahun Ajaran
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= base_url('nilai') ?>" class="nav-link">
                <i class="ri-bar-chart-box-line"></i> Nilai
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= base_url('absensi') ?>" class="nav-link">
                <i class="ri-calendar-check-line"></i> Absensi
            </a>
        </li>
    </ul>
    <div class="sidebar-footer">
        <ul>
            &copy; <?= date('Y')?> SMK Harapan Bangsa
        </ul>
    </div>
</nav>
