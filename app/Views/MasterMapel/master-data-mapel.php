<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Kelola Mata Pelajaran</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / Mata Pelajaran</div>
    </div>
    <a href="<?= base_url('mapel/tambah') ?>" class="btn btn-primary btn-sm">
        <i class="ri-add-line"></i> Tambah Mapel
    </a>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Mata Pelajaran</div>
        <form method="get" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <select name="status" class="form-control" style="width:160px; padding:8px 10px; font-size:0.9rem;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Produktif"     <?= ($filter_status??'') == 'Produktif'     ? 'selected':'' ?>>Produktif</option>
                <option value="Umum" <?= ($filter_status??'') == 'Umum' ? 'selected':'' ?>>Umum</option>
            </select>
            <div class="search-box">
                <i class="ri-search-line"></i>
                <input type="text" name="q" placeholder="Cari mapel / guru..." value="<?= esc($keyword ?? '') ?>">
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Status</th>
                    <th width="130">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($mapel)): ?>
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="ri-book-read-line"></i>
                            <p>Tidak ada data mata pelajaran</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($mapel as $i => $m): ?>
                <tr>
                    <td><?= (($pagination['page'] ?? 1) - 1) * ($pagination['per_page'] ?? 10) + $i + 1 ?></td>
                    <td><?= esc($m['nama_mapel']) ?></td>
                    <td><?= esc($m['nama_guru']) ?></td>
                    <td>
                        <span class="badge <?= $m['status'] == 'Produktif' ? 'badge-prod' : 'badge-non-prod' ?>">
                            <?= esc($m['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= base_url('mapel/edit/' . $m['id_mapel']) ?>" class="btn btn-edit btn-sm">
                            <i class="ri-edit-line"></i> Edit
                        </a>
                        <button onclick="confirmDelete('<?= base_url('mapel/hapus/' . $m['id_mapel']) ?>')" class="btn btn-danger btn-sm">
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
    'title'    => 'Daftar Mata Pelajaran',
    'subtitle' => 'Sistem Informasi Sekolah',
    'content'  => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [
        'total_mapel' => count($mapel ?? []),
    ],
]);
?>
