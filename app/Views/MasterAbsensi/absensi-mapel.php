<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Absensi Mata Pelajaran</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> / Absensi Mapel
        </div>
    </div>
</div>

<!-- Filter tanggal -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div class="card-title"><i class="ri-filter-line" style="margin-right:6px;"></i>Filter</div>
    </div>
    <div style="padding:16px 20px;">
        <form method="get" style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
            <div>
                <label style="font-size:0.82rem; font-weight:600; display:block; margin-bottom:4px;">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?= esc($tanggal) ?>" style="width:170px;">
            </div>
            <div>
                <label style="font-size:0.82rem; font-weight:600; display:block; margin-bottom:4px;">Kelas</label>
                <select name="kelas" id="kelas" class="form-control" style="width:180px;">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($kelas_list as $k): ?>
                        <option value="<?= $k['id_kelas'] ?>" <?= $filter_kelas == $k['id_kelas'] ? 'selected' : '' ?>>
                            <?= esc($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="font-size:0.82rem; font-weight:600; display:block; margin-bottom:4px;">Guru</label>
                <select name="guru" id="guru" class="form-control" style="width:200px;">
                    <option value="">Semua Guru</option>
                    <?php foreach ($guru_list as $g): ?>
                        <option value="<?= $g['id_guru'] ?>" <?= $filter_guru == $g['id_guru'] ? 'selected' : '' ?>>
                            <?= esc($g['nama_guru']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="ri-filter-line"></i> Tampilkan</button>
            <a href="<?= base_url('admin/absensi/mapel') ?>" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>
</div>

<!-- Daftar Jadwal -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="ri-book-open-line" style="margin-right:6px;"></i>Jadwal Pelajaran</div>
        <span style="font-size:0.82rem; color:var(--text-muted);">
            Tanggal: <strong><?= date('d M Y', strtotime($tanggal)) ?></strong>
        </span>
    </div>

    <?php if (empty($jadwal_list)): ?>
        <div class="empty-state" style="padding:40px;">
            <i class="ri-calendar-close-line"></i>
            <p>Tidak ada jadwal ditemukan</p>
        </div>
    <?php else: ?>
        <?php
            $hariOrder = ['Senin'=>1,'Selasa'=>2,'Rabu'=>3,'Kamis'=>4,'Jumat'=>5,'Sabtu'=>6,'Minggu'=>7];
            $byHari = [];
            foreach ($jadwal_list as $j) {
                $byHari[$j['hari']][] = $j;
            }
            uksort($byHari, fn($a, $b) => ($hariOrder[$a] ?? 9) <=> ($hariOrder[$b] ?? 9));

            $hariIniId = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'][date('l')] ?? '';
        ?>
        <div style="padding:16px 20px; display:flex; flex-direction:column; gap:20px;">
            <?php foreach ($byHari as $hari => $jadwals): ?>
                <div>
                    <div style="font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--text-muted); margin-bottom:8px; display:flex; align-items:center; gap:8px;">
                        <?= esc($hari) ?>
                        <?php if ($hari === $hariIniId): ?>
                            <span class="badge badge-aktif" style="font-size:0.7rem;">Hari Ini</span>
                        <?php endif; ?>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <?php foreach ($jadwals as $j): ?>
                            <div style="display:flex; align-items:center; justify-content:space-between; background:var(--bg-secondary); border-radius:10px; padding:14px 16px; border:1px solid var(--border); gap:12px; flex-wrap:wrap;">
                                <div style="display:flex; align-items:center; gap:14px;">
                                    <div style="background:var(--primary-light, #eff6ff); color:var(--primary); border-radius:8px; padding:10px; font-size:1.2rem; flex-shrink:0;">
                                        <i class="ri-book-2-line"></i>
                                    </div>
                                    <div>
                                        <strong style="font-size:0.95rem;"><?= esc($j['nama_mapel'] ?? '-') ?></strong>
                                        <div style="font-size:0.82rem; color:var(--text-muted); margin-top:2px; display:flex; gap:10px; flex-wrap:wrap;">
                                            <span><i class="ri-building-4-line" style="margin-right:3px;"></i><?= esc($j['nama_kelas'] ?? '-') ?></span>
                                            <span><i class="ri-time-line" style="margin-right:3px;"></i><?= esc(substr($j['jam_mulai'],0,5)) ?> – <?= esc(substr($j['jam_selesai'],0,5)) ?></span>
                                            <span><i class="ri-user-star-line" style="margin-right:3px;"></i><?= esc($j['nama_guru'] ?? '-') ?></span>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?= base_url('admin/absensi/mapel/' . $j['id_jadwal'] . '?tanggal=' . $tanggal) ?>"
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

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'    => 'Absensi Mapel',
    'subtitle' => 'Kelola absensi mata pelajaran',
    'content'  => $content,
]);
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    new TomSelect('#kelas', {
        create: false,
        maxOptions: 500,
        placeholder: 'Pilih kelas...',
        dropdownParent: 'body',
    });

    new TomSelect('#guru', {
        create: false,
        maxOptions: 500,
        placeholder: 'Pilih guru...',
        dropdownParent: 'body',
    });
});
</script>