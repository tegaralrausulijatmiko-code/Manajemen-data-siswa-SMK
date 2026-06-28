<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Kelola Data Jurusan</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> / Jurusan
        </div>
    </div>

    <div class="page-header-actions">
        <?= view('Template/partials/_modal_import', ['modul' => 'jurusan']) ?>

        <a href="<?= base_url('jurusan/tambah') ?>" class="btn btn-primary btn-sm">
            <i class="ri-add-line"></i> Tambah Jurusan
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Jurusan</div>
        <form method="get" action="">
            <div class="search-box">
                <i class="ri-search-line"></i>
                <input type="text" name="q" placeholder="Cari jurusan..." value="<?= esc($keyword ?? '') ?>">
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th width="50">No<</th>
                    <th>Kode</th>
                    <th>Nama Jurusan</th>
                    <th>Kepala Program</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($jurusan)): ?>
                <tr><td colspan="5">
                    <div class="empty-state">
                        <i class="ri-folder-open-line"></i>
                        <p>Tidak ada data jurusan</p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($jurusan as $i => $j): ?>
                <tr>
                    <td><?= (($pagination['page'] ?? 1) - 1) * ($pagination['per_page'] ?? 10) + $i + 1 ?></td>
                    <td><strong><?= esc($j['kode_jurusan']) ?></strong></td>
                    <td><?= esc($j['nama_jurusan']) ?></td>
                    <td>
                        <?php if (empty($j['nama_guru'])): ?>
                            <span style="color:var(--text-light);">Belum dipilih</span>
                            <?php else: ?>
                            <?= esc($j['nama_guru']) ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= base_url('jurusan/edit/' . $j['id_jurusan']) ?>" class="btn btn-edit btn-sm">
                            <i class="ri-edit-line"></i> Edit
                        </a>
                        <button onclick="confirmDelete('<?= base_url('jurusan/hapus/' . $j['id_jurusan']) ?>', '<?= esc($j['nama_jurusan']) ?>')" class="btn btn-danger btn-sm">
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
    'title'    => 'Kelola Data Jurusan',
    'subtitle' => 'Sistem Informasi Sekolah',
    'content'  => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [
        'total_jurusan' => count($jurusan ?? []),
    ],
]);
?>
