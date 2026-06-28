<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Kelola Data Guru</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> / Guru
        </div>
    </div>

    <div style="display:flex; gap:10px; align-items:center;">
        <?= view('Template/partials/_modal_import', ['modul' => 'guru']) ?>

        <a href="<?= base_url('guru/tambah') ?>" class="btn btn-primary btn-sm">
            <i class="ri-add-line"></i> Tambah Guru
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Guru</div>
        <form method="get">
            <div class="search-box">
                <i class="ri-search-line"></i>
                <input type="text" name="q" placeholder="Cari nama / NIP..." value="<?= esc($keyword ?? '') ?>">
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Jenis Kelamin</th>
                    <th>No. HP</th>
                    <th width="250">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($guru)): ?>
                <tr><td colspan="6">
                    <div class="empty-state">
                        <i class="ri-user-star-line"></i>
                        <p>Tidak ada data guru</p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($guru as $i => $g): ?>
                <tr>
                    <td><?= (($pagination['page'] ?? 1) - 1) * ($pagination['per_page'] ?? 10) + $i + 1 ?></td>
                    <td><?= esc($g['nip']) ?></td>
                    <td><strong><?= esc($g['nama_guru']) ?></strong></td>
                    <td>
                        <span class="badge <?= $g['jenis_kelamin'] == 'L' ? 'badge-l' : 'badge-p' ?>">
                            <?= $g['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                        </span>
                    </td>
                    <td><?= esc($g['no_hp'] ?? '-') ?></td>
                    <td>
                        <a href="<?= base_url('guru/edit/' . $g['id_guru']) ?>" class="btn btn-edit btn-sm">
                            <i class="ri-edit-line"></i> Edit
                        </a>
                        <!-- <form method="post" action="<?= base_url('guru/buat-user/' . $g['id_guru']) ?>" style="display:inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="ri-user-add-line"></i> Buat User
                            </button>
                        </form> -->
                        <button onclick="confirmDelete('<?= base_url('guru/hapus/' . $g['id_guru']) ?>')" class="btn btn-danger btn-sm">
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
    'title'    => 'Daftar Guru',
    'subtitle' => 'Sistem Informasi Sekolah',
    'content'  => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [
        'total_guru' => count($guru ?? []),
    ],
]);
?>
