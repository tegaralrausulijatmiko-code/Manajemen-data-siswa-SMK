<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Kelola Jadwal Pelajaran</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / Jadwal</div>
    </div>
    <a href="<?= base_url('jadwal/tambah') ?>" class="btn btn-primary btn-sm"><i class="ri-add-line"></i> Tambah Jadwal</a>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Jadwal</div>
        <form method="get" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <select name="kelas" class="form-control" style="width:170px; padding:8px 10px;" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                <?php foreach ($kelas_list as $k): ?>
                    <option value="<?= $k['id_kelas'] ?>" <?= ($filter_kelas ?? '') == $k['id_kelas'] ? 'selected' : '' ?>><?= esc($k['nama_kelas']) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="search-box">
                <i class="ri-search-line"></i>
                <input type="text" name="q" placeholder="Cari jadwal..." value="<?= esc($keyword ?? '') ?>">
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th width="230">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($jadwal)): ?>
                    <tr><td colspan="8"><div class="empty-state"><i class="ri-calendar-schedule-line"></i><p>Tidak ada data jadwal</p></div></td></tr>
                <?php else: ?>
                    <?php foreach ($jadwal as $i => $j): ?>
                        <tr>
                            <td><?= (($pagination['page'] ?? 1) - 1) * ($pagination['per_page'] ?? 10) + $i + 1 ?></td>
                            <td><span class="badge badge-l"><?= esc($j['hari']) ?></span></td>
                            <td><?= esc(substr($j['jam_mulai'], 0, 5)) ?> - <?= esc(substr($j['jam_selesai'], 0, 5)) ?></td>
                            <td><?= esc($j['nama_kelas'] ?? '-') ?></td>
                            <td><strong><?= esc($j['nama_mapel'] ?? '-') ?></strong></td>
                            <td><?= esc($j['nama_guru'] ?? '-') ?></td>
                            <td>
                                <!-- <a href="<?= base_url('absensi/jadwal/' . $j['id_jadwal']) ?>" class="btn btn-primary btn-sm"><i class="ri-calendar-check-line"></i>Absen</a> -->
                                <a href="<?= base_url('jadwal/edit/' . $j['id_jadwal']) ?>" class="btn btn-edit btn-sm"><i class="ri-edit-line"></i>Edit</a>
                                <button onclick="confirmDelete('<?= base_url('jadwal/hapus/' . $j['id_jadwal']) ?>')" class="btn btn-danger btn-sm"><i class="ri-delete-bin-line"></i>Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?= view('Template/partials/pagination', ['pagination' => $pagination ?? null]) ?>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title' => 'Jadwal Pelajaran',
    'subtitle' => 'Sistem Informasi Sekolah',
    'content' => $content,
]);
?>
