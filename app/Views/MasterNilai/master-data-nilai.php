<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Kelola Nilai</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / Nilai</div>
    </div>
    <a href="<?= base_url('nilai/tambah') ?>" class="btn btn-primary btn-sm"><i class="ri-add-line"></i> Tambah Nilai</a>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Nilai</div>
        <form method="get" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <select name="tahun" class="form-control" style="width:190px; padding:8px 10px;" onchange="this.form.submit()">
                <option value="">Semua Tahun</option>
                <?php foreach ($tahun_list as $t): ?>
                    <option value="<?= $t['id_tahun_ajaran'] ?>" <?= ($filter_tahun ?? '') == $t['id_tahun_ajaran'] ? 'selected' : '' ?>><?= esc($t['tahun_ajaran'] . ' - ' . $t['semester']) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="search-box">
                <i class="ri-search-line"></i>
                <input type="text" name="q" placeholder="Cari siswa / mapel..." value="<?= esc($keyword ?? '') ?>">
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Mapel</th>
                    <th>Tahun</th>
                    <th>Tugas</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Akhir</th>
                    <th>Predikat</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($nilai)): ?>
                    <tr><td colspan="11"><div class="empty-state"><i class="ri-bar-chart-box-line"></i><p>Tidak ada data nilai</p></div></td></tr>
                <?php else: ?>
                    <?php foreach ($nilai as $i => $n): ?>
                        <tr>
                            <td><?= (($pagination['page'] ?? 1) - 1) * ($pagination['per_page'] ?? 10) + $i + 1 ?></td>
                            <td><strong><?= esc($n['nama_siswa'] ?? '-') ?></strong><br><small><?= esc($n['nisn'] ?? '-') ?></small></td>
                            <td><?= esc($n['nama_kelas'] ?? '-') ?></td>
                            <td><?= esc($n['nama_mapel'] ?? '-') ?></td>
                            <td><?= esc(($n['tahun_ajaran'] ?? '-') . ' ' . ($n['semester'] ?? '')) ?></td>
                            <td><?= esc($n['nilai_tugas']) ?></td>
                            <td><?= esc($n['nilai_uts']) ?></td>
                            <td><?= esc($n['nilai_uas']) ?></td>
                            <td><strong><?= esc($n['nilai_akhir']) ?></strong></td>
                            <td><span class="badge badge-prod"><?= esc($n['predikat']) ?></span></td>
                            <td>
                                <a href="<?= base_url('nilai/edit/' . $n['id_nilai']) ?>" class="btn btn-edit btn-sm"><i class="ri-edit-line"></i>Edit</a>
                                <button onclick="confirmDelete('<?= base_url('nilai/hapus/' . $n['id_nilai']) ?>')" class="btn btn-danger btn-sm"><i class="ri-delete-bin-line"></i>Hapus</button>
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
echo view('Template/layout', ['title' => 'Nilai Siswa', 'subtitle' => 'Akademik', 'content' => $content]);
?>
