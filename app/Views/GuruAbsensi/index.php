<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Absensi Kelas</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / Absensi Guru</div>
    </div>
    <a href="<?= base_url('guru/absensi/rekap') ?>" class="btn btn-secondary btn-sm"><i class="ri-file-chart-line"></i> Rekap Absensi</a>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Pilih Kelas yang Diajar</div>
        <form method="get" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <select name="kelas" class="form-control" style="width:210px; padding:8px 10px;" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                <?php foreach ($kelas_list as $kelas): ?>
                    <option value="<?= $kelas['id_kelas'] ?>" <?= ($filter_kelas ?? '') == $kelas['id_kelas'] ? 'selected' : '' ?>>
                        <?= esc($kelas['nama_kelas']) ?><?= ! empty($kelas['kode_jurusan']) ? ' - ' . esc($kelas['kode_jurusan']) : '' ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="tanggal" class="form-control" style="width:160px; padding:8px 10px;" value="<?= esc($tanggal) ?>">
            <button type="submit" class="btn btn-secondary btn-sm"><i class="ri-filter-line"></i> Tampilkan</button>
        </form>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kelas</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Mata Pelajaran</th>
                    <th>Ruang</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($jadwal_list)): ?>
                    <tr><td colspan="7"><div class="empty-state"><i class="ri-calendar-line"></i><p>Belum ada jadwal mengajar</p></div></td></tr>
                <?php else: ?>
                    <?php foreach ($jadwal_list as $i => $jadwal): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= esc($jadwal['nama_kelas'] ?? '-') ?></td>
                            <td><span class="badge badge-l"><?= esc($jadwal['hari']) ?></span></td>
                            <td><?= esc(substr($jadwal['jam_mulai'], 0, 5) . ' - ' . substr($jadwal['jam_selesai'], 0, 5)) ?></td>
                            <td><strong><?= esc($jadwal['nama_mapel'] ?? '-') ?></strong></td>
                            <td><?= esc($jadwal['ruang'] ?? '-') ?></td>
                            <td>
                                <a href="<?= base_url('guru/absensi/jadwal/' . $jadwal['id_jadwal'] . '?tanggal=' . $tanggal) ?>" class="btn btn-primary btn-sm">
                                    <i class="ri-calendar-check-line"></i> Absen
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title' => 'Absensi Guru',
    'subtitle' => 'Pilih kelas dan jadwal yang diajar',
    'content' => $content,
]);
?>
