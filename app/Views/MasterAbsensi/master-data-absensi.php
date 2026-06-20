<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Kelola Absensi</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / Absensi</div>
    </div>
    <a href="<?= base_url('absensi/tambah') ?>" class="btn btn-primary btn-sm"><i class="ri-add-line"></i> Tambah Absensi</a>
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
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($absensi)): ?>
                    <tr><td colspan="8"><div class="empty-state"><i class="ri-calendar-check-line"></i><p>Tidak ada data absensi</p></div></td></tr>
                <?php else: ?>
                    <?php foreach ($absensi as $i => $a): ?>
                        <tr>
                            <td><?= (($pagination['page'] ?? 1) - 1) * ($pagination['per_page'] ?? 10) + $i + 1 ?></td>
                            <td><?= esc(date('d/m/Y', strtotime($a['tanggal']))) ?></td>
                            <td><strong><?= esc($a['nama_siswa'] ?? '-') ?></strong><br><small><?= esc($a['nisn'] ?? '-') ?></small></td>
                            <td><?= esc($a['nama_kelas'] ?? '-') ?></td>
                            <td><span class="badge <?= $a['status'] == 'Hadir' ? 'badge-aktif' : 'badge-warning' ?>"><?= esc($a['status']) ?></span></td>
                            <td><?= esc($a['keterangan'] ?? '-') ?></td>
                            <td>
                                <a href="<?= base_url('absensi/edit/' . $a['id_absensi']) ?>" class="btn btn-edit btn-sm"><i class="ri-edit-line"></i>Edit</a>
                                <button onclick="confirmDelete('<?= base_url('absensi/hapus/' . $a['id_absensi']) ?>')" class="btn btn-danger btn-sm"><i class="ri-delete-bin-line"></i>Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?= view('Template/partials/pagination', ['pagination' => $pagination ?? null]) ?>
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