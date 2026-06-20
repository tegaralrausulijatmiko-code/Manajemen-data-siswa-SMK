<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Kelola Absensi</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / Absensi</div>
    </div>
    <div style="display:flex; gap:8px;">
        <a href="<?= base_url('absensi/rekap') ?>" class="btn btn-secondary btn-sm"><i class="ri-file-list-3-line"></i> Rekap Absensi</a>
        <button type="submit" form="attendance-save-form" class="btn btn-primary btn-sm"><i class="ri-save-line"></i> Simpan Absensi</button>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Absensi</div>
        <form method="get" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <select name="kelas" class="form-control" style="width:160px; padding:8px 10px;" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                <?php foreach ($kelas_list as $k): ?>
                    <option value="<?= $k['id_kelas'] ?>" <?= ($filter_kelas ?? '') == $k['id_kelas'] ? 'selected' : '' ?>><?= esc($k['nama_kelas']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="tanggal" class="form-control" style="width:150px; padding:8px 10px;" value="<?= esc($filter_tanggal ?? '') ?>" onchange="this.form.submit()">
            <div class="search-box">
                <i class="ri-search-line"></i>
                <input type="text" name="q" placeholder="Cari siswa..." value="<?= esc($keyword ?? '') ?>">
            </div>
        </form>
    </div>
    <form method="post" action="<?= base_url('absensi/simpan') ?>" id="attendance-save-form">
        <?= csrf_field() ?>
        <input type="hidden" name="kelas" value="<?= esc($filter_kelas ?? '') ?>">
        <input type="hidden" name="q" value="<?= esc($keyword ?? '') ?>">
        <input type="hidden" name="tanggal" value="<?= esc($filter_tanggal ?? date('Y-m-d')) ?>">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Status Saat Ini</th>
                        <th>Keterangan</th>
                        <th width="260">Ubah Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($siswa_list)): ?>
                        <tr><td colspan="7"><div class="empty-state"><i class="ri-calendar-check-line"></i><p>Tidak ada siswa untuk ditampilkan.</p></div></td></tr>
                    <?php else: ?>
                        <?php foreach ($siswa_list as $i => $s):
                            $attendance = $attendance_map[$s['id_siswa']] ?? null;
                            $status = $attendance['status'] ?? 'Belum Absen';
                            $badgeClass = $status === 'Hadir' ? 'badge-aktif' : ($status === 'Izin' ? 'badge-warning' : ($status === 'Alpa' ? 'badge-nonaktif' : 'badge-light'));
                        ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc(date('d/m/Y', strtotime($filter_tanggal ?? date('Y-m-d')))) ?></td>
                                <td><strong><?= esc($s['nama_siswa']) ?></strong><br><small><?= esc($s['nisn']) ?></small></td>
                                <td><?= esc($s['nama_kelas'] ?? '-') ?></td>
                                <td><span class="badge <?= $badgeClass ?>"><?= esc($status) ?></span></td>
                                <td><?= esc($attendance['keterangan'] ?? '-') ?></td>
                                <td>
                                    <input type="hidden" name="id_kelas[<?= $s['id_siswa'] ?>]" value="<?= esc($s['id_kelas']) ?>">
                                    <select name="status[<?= $s['id_siswa'] ?>]" class="form-control status-select" data-id="<?= $s['id_siswa'] ?>" style="width:100%; padding:8px 10px;">
                                        <option value="Belum Absen" <?= $status === 'Belum Absen' ? 'selected' : '' ?>>Belum Absen</option>
                                        <option value="Hadir" <?= $status === 'Hadir' ? 'selected' : '' ?>>Hadir</option>
                                        <option value="Izin" <?= $status === 'Izin' ? 'selected' : '' ?>>Izin</option>
                                        <option value="Alpa" <?= $status === 'Alpa' ? 'selected' : '' ?>>Alpa</option>
                                    </select>
                                    <input type="text" name="keterangan[<?= $s['id_siswa'] ?>]" id="keterangan-<?= $s['id_siswa'] ?>" class="form-control keterangan-input" style="width:100%; padding:8px 10px; margin-top:6px; <?= $status !== 'Izin' ? 'display:none;' : '' ?>" placeholder="Keterangan izin..." value="<?= esc($attendance['keterangan'] ?? '') ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </form>
</div>

<script>
    document.addEventListener('change', function (a) {
        if (a.target.classList.contains('status-select')) {
            var id = a.target.dataset.id;
            var keteranganInput = document.getElementById('keterangan-' + id);
            if (keteranganInput) {
                keteranganInput.style.display = (a.target.value === 'Izin') ? 'block' : 'none';
                if (a.target.value !== 'Izin') {
                    keteranganInput.value = '';
                }
            }
        }
    });
</script>

<?php
$content = ob_get_clean();
echo view('Template/layout', ['title' => 'Absensi Siswa', 'subtitle' => 'Akademik', 'content' => $content]);
?>