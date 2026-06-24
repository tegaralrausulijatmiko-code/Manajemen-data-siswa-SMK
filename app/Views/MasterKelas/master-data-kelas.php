<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Kelola Data Kelas</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / Kelas</div>
    </div>
    <a href="<?= base_url('kelas/tambah') ?>" class="btn btn-primary btn-sm">
        <i class="ri-add-line"></i> Tambah Kelas
    </a>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Kelas</div>
        <form method="get" style="display:flex; gap:10px; align-items:center;">
            <select name="tingkat" class="form-control" style="width:140px; padding:8px 10px; font-size:0.9rem;" onchange="this.form.submit()">
                <option value="">Semua Tingkat</option>
                <option value="X"   <?= ($filter_tingkat??'') == 'X'   ? 'selected':'' ?>>Kelas X</option>
                <option value="XI"  <?= ($filter_tingkat??'') == 'XI'  ? 'selected':'' ?>>Kelas XI</option>
                <option value="XII" <?= ($filter_tingkat??'') == 'XII' ? 'selected':'' ?>>Kelas XII</option>
            </select>
            <div class="search-box">
                <i class="ri-search-line"></i>
                <input type="text" name="q" placeholder="Cari kelas / wali..." value="<?= esc($keyword ?? '') ?>">
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Nama Kelas</th>
                    <th>Jurusan</th>
                    <th>Tingkat</th>
                    <th>Wali Kelas</th>
                    <th>Jumlah Siswa</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kelas)): ?>
                <tr><td colspan="7">
                    <div class="empty-state">
                        <i class="ri-building-4-line"></i>
                        <p>Tidak ada data kelas</p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($kelas as $i => $k): ?>
                <tr>
                    <td><?= (($pagination['page'] ?? 1) - 1) * ($pagination['per_page'] ?? 10) + $i + 1 ?></td>
                    <td><strong><?= esc($k['nama_kelas']) ?></strong></td>
                    <td><?= esc($k['nama_jurusan'] ?? '-') ?></td>
                    <td><span class="badge <?= $k['tingkat'] === 'X' ? 'badge-l' : ($k['tingkat'] === 'XI' ? 'badge-aktif' : ($k['tingkat'] === 'XII' ? 'badge-p' : 'badge-nonaktif')) ?>"><?= esc($k['tingkat']) ?></span></td>
                    <td><?= esc($k['nama_guru'] ?? '-') ?></td>
                    <td><?= $k['jumlah_siswa'] ?></td>
                    <td>
                        <a href="<?= base_url('kelas/show/' . $k['id_kelas']) ?>" class="btn btn-info btn-sm">
                            <i class="ri-eye-line"></i> Show
                        </a>
                        <a href="<?= base_url('kelas/edit/' . $k['id_kelas']) ?>" class="btn btn-edit btn-sm">
                            <i class="ri-edit-line"></i> Edit
                        </a>
                        <button onclick="confirmDelete('<?= base_url('kelas/hapus/' . $k['id_kelas']) ?>')" class="btn btn-danger btn-sm">
                            <i class="ri-delete-bin-line"></i>Hapus
                        </button>
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
    'title'    => 'Daftar Kelas',
    'subtitle' => 'Sistem Informasi Sekolah',
    'content'  => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [
        'total_kelas' => count($kelas ?? []),
    ],
]);
?>
