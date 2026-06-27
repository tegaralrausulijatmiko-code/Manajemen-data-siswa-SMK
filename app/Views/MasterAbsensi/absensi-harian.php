<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Absensi Harian</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> / Absensi Harian
        </div>
    </div>
</div>

<!-- Filter -->
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
                <label style="font-size:0.82rem; font-weight:600; display:block; margin-bottom:4px;">Jurusan</label>
                <select name="jurusan" class="form-control" style="width:180px;">
                    <option value="">Semua Jurusan</option>
                    <?php foreach ($jurusan_list as $j): ?>
                        <option value="<?= $j['id_jurusan'] ?>" <?= $filter_jurusan == $j['id_jurusan'] ? 'selected' : '' ?>>
                            <?= esc($j['kode_jurusan'] . ' – ' . $j['nama_jurusan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="ri-filter-line"></i> Tampilkan</button>
            <a href="<?= base_url('admin/absensi/harian') ?>" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>
</div>

<!-- Daftar Kelas -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="ri-building-4-line" style="margin-right:6px;"></i>Pilih Kelas</div>
        <span style="font-size:0.82rem; color:var(--text-muted);">
            Tanggal: <strong><?= date('d M Y', strtotime($tanggal)) ?></strong>
        </span>
    </div>

    <?php if (empty($kelas_list)): ?>
        <div class="empty-state" style="padding:40px;">
            <i class="ri-building-2-line"></i>
            <p>Tidak ada kelas ditemukan</p>
        </div>
    <?php else: ?>
        <?php
            // Kelompokkan per tingkat
            $byTingkat = [];
            foreach ($kelas_list as $k) {
                $byTingkat[$k['tingkat']][] = $k;
            }
            ksort($byTingkat);
        ?>
        <div style="padding:16px 20px; display:flex; flex-direction:column; gap:20px;">
            <?php foreach ($byTingkat as $tingkat => $kelasList): ?>
                <div>
                    <div style="font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--text-muted); margin-bottom:8px;">
                        Kelas <?= esc($tingkat) ?>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <?php foreach ($kelasList as $k): ?>
                            <div style="display:flex; align-items:center; justify-content:space-between; background:var(--bg-secondary); border-radius:10px; padding:14px 16px; border:1px solid var(--border); gap:12px; flex-wrap:wrap;">
                                <div style="display:flex; align-items:center; gap:14px;">
                                    <div style="background:#f0fdf4; color:#16a34a; border-radius:8px; padding:10px; font-size:1.2rem; flex-shrink:0;">
                                        <i class="ri-building-4-line"></i>
                                    </div>
                                    <div>
                                        <strong style="font-size:0.95rem;"><?= esc($k['nama_kelas']) ?></strong>
                                        <div style="font-size:0.82rem; color:var(--text-muted); margin-top:2px; display:flex; gap:10px; flex-wrap:wrap;">
                                            <span><?= esc($k['kode_jurusan'] . ' – ' . $k['nama_jurusan']) ?></span>
                                            <?php if (! empty($k['nama_wali'])): ?>
                                                <span><i class="ri-user-star-line" style="margin-right:3px;"></i>Wali: <?= esc($k['nama_wali']) ?></span>
                                            <?php endif; ?>
                                            <span><i class="ri-group-line" style="margin-right:3px;"></i><?= esc($k['jumlah_siswa']) ?> siswa</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?= base_url('admin/absensi/harian/' . $k['id_kelas'] . '?tanggal=' . $tanggal) ?>"
                                   class="btn btn-sm" style="background:#16a34a; color:#fff; white-space:nowrap;">
                                    <i class="ri-calendar-check-line"></i> Absen Harian
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
    'title'    => 'Absensi Harian',
    'subtitle' => 'Kelola absensi harian per kelas',
    'content'  => $content,
]);
?>