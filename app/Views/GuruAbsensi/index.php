<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Absensi Siswa</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> / Absensi
        </div>
    </div>
</div>

<!-- ===== ABSEN MAPEL ===== -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <div class="card-title"><i class="ri-book-open-line" style="margin-right:6px;"></i>Absensi Mata Pelajaran</div>
        <span style="font-size:0.82rem; color:var(--text-muted);">Berdasarkan jadwal mengajar</span>
    </div>

    <?php if (empty($jadwal_list)): ?>
        <div class="empty-state" style="padding: 32px;">
            <i class="ri-calendar-close-line"></i>
            <p>Tidak ada jadwal mengajar yang ditemukan</p>
        </div>
    <?php else: ?>
        <?php
            $hariOrder = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7];
            $byHari = [];
            foreach ($jadwal_list as $j) {
                $byHari[$j['hari']][] = $j;
            }
            uksort($byHari, fn($a, $b) => ($hariOrder[$a] ?? 9) <=> ($hariOrder[$b] ?? 9));

            $hariIni = date('l');
            $hariMap = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
            $hariIniId = $hariMap[$hariIni] ?? '';
        ?>
        <div style="padding: 16px 20px; display: flex; flex-direction: column; gap: 16px;">
            <?php foreach ($byHari as $hari => $jadwals): ?>
                <div>
                    <div style="font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--text-muted); margin-bottom:8px; display:flex; align-items:center; gap:8px;">
                        <?= esc($hari) ?>
                        <?php if ($hari === $hariIniId): ?>
                            <span class="badge badge-aktif" style="font-size:0.7rem;">Hari Ini</span>
                        <?php endif; ?>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <?php foreach ($jadwals as $j): ?>
                            <div style="display:flex; align-items:center; justify-content:space-between; background:var(--bg-secondary); border-radius:10px; padding:14px 16px; border:1px solid var(--border); gap:12px; flex-wrap:wrap;">
                                <div style="display:flex; align-items:center; gap:14px;">
                                    <div style="background:var(--primary-light, #eff6ff); color:var(--primary); border-radius:8px; padding:10px; font-size:1.2rem; flex-shrink:0;">
                                        <i class="ri-time-line"></i>
                                    </div>
                                    <div>
                                        <strong style="font-size:0.95rem;"><?= esc($j['nama_mapel'] ?? '-') ?></strong>
                                        <div style="font-size:0.82rem; color:var(--text-muted); margin-top:2px;">
                                            <?= esc($j['nama_kelas'] ?? '-') ?> &nbsp;·&nbsp;
                                            <?= esc(substr($j['jam_mulai'], 0, 5)) ?> – <?= esc(substr($j['jam_selesai'], 0, 5)) ?>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?= base_url('guru/absensi/jadwal/' . $j['id_jadwal'] . '?tanggal=' . $tanggal) ?>"
                                   class="btn btn-primary btn-sm" style="white-space:nowrap;">
                                    <i class="ri-edit-box-line"></i> Isi Absensi
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- ===== ABSEN HARIAN (WALI KELAS) ===== -->
<?php if (! empty($kelas_wali)): ?>
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="ri-home-heart-line" style="margin-right:6px;"></i>Absensi Harian — Wali Kelas</div>
        <span style="font-size:0.82rem; color:var(--text-muted);">Presensi harian kelas yang Anda wali</span>
    </div>
    <div style="padding: 16px 20px; display: flex; flex-direction: column; gap: 10px;">
        <?php foreach ($kelas_wali as $kw): ?>
            <div style="display:flex; align-items:center; justify-content:space-between; background:var(--bg-secondary); border-radius:10px; padding:14px 16px; border:1px solid var(--border); gap:12px; flex-wrap:wrap;">
                <div style="display:flex; align-items:center; gap:14px;">
                    <div style="background:#f0fdf4; color:#16a34a; border-radius:8px; padding:10px; font-size:1.2rem; flex-shrink:0;">
                        <i class="ri-building-4-line"></i>
                    </div>
                    <div>
                        <strong style="font-size:0.95rem;"><?= esc($kw['nama_kelas']) ?></strong>
                        <div style="font-size:0.82rem; color:var(--text-muted); margin-top:2px;">
                            <?= esc($kw['kode_jurusan'] . ' – ' . $kw['nama_jurusan']) ?>
                            &nbsp;·&nbsp; Kelas <?= esc($kw['tingkat']) ?>
                        </div>
                    </div>
                </div>
                <a href="<?= base_url('guru/absensi/harian/' . $kw['id_kelas'] . '?tanggal=' . $tanggal) ?>"
                   class="btn btn-sm" style="background:#16a34a; color:#fff; white-space:nowrap;">
                    <i class="ri-calendar-check-line"></i> Absen Harian
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'    => 'Absensi Guru',
    'subtitle' => 'Kelola absensi mapel & harian',
    'content'  => $content,
]);
?>