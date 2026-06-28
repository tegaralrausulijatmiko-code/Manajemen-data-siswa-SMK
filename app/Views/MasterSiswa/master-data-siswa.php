<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Kelola Data Siswa</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> / Siswa
        </div>
    </div>

    <div class="page-header-actions">
        <?= view('Template/partials/_modal_import', ['modul' => 'siswa']) ?>

        <a href="<?= base_url('siswa/tambah') ?>" class="btn btn-primary btn-sm">
            <i class="ri-add-line"></i> Tambah Siswa
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Siswa <span style="font-size:0.85rem; color:var(--text-light); font-weight:400;">(Total: <?= $total ?? 0 ?> siswa)</span></div>
        <form method="get" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <select name="jk" class="form-control" style="width:130px; padding:8px 10px; font-size:0.9rem;" onchange="this.form.submit()">
                <option value="">Semua JK</option>
                <option value="L" <?= ($filter_jk??'') == 'L' ? 'selected':'' ?>>Laki-laki</option>
                <option value="P" <?= ($filter_jk??'') == 'P' ? 'selected':'' ?>>Perempuan</option>
            </select>
            <div class="search-box">
                <i class="ri-search-line"></i>
                <input type="text" name="q" placeholder="Cari nama / nisn..." value="<?= esc($keyword ?? '') ?>">
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th width="70">Foto</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>L/P</th>
                    <th>Status</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($siswa)): ?>
                <tr><td colspan="8">
                    <div class="empty-state">
                        <i class="ri-user-smile-line"></i>
                        <p>Tidak ada data siswa</p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($siswa as $i => $s): ?>
                <tr>
                    <td><?= (($pagination['page'] ?? 1) - 1) * ($pagination['per_page'] ?? 10) + $i + 1 ?></td>
                    <td>
                        <?php if (! empty($s['foto'])): ?>
                            <img src="<?= base_url('uploads/' . $s['foto']) ?>" alt="Foto <?= esc($s['nama_siswa']) ?>" style="width:45px; height:45px; border-radius:10px; object-fit:cover; display:block;">
                        <?php else: ?>
                            <div style="width:44px; height:44px; border-radius:8px; background:#eff6ff; color:var(--primary); display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                                <i class="ri-user-line"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($s['nisn']) ?></td>
                    <td><strong><?= esc($s['nama_siswa']) ?></strong></td>
                    <td><?= esc($s['nama_kelas'] ?? '-') ?></td>
                    <td>
                        <span class="badge <?= $s['jenis_kelamin'] == 'L' ? 'badge-l' : 'badge-p' ?>">
                            <?= $s['jenis_kelamin'] == 'L' ? 'L' : 'P' ?>
                        </span>
                    </td>
                    <td><span class="badge badge-aktif">Aktif</span></td>
                    <td>
                            <a href="<?= base_url('siswa/show/' . $s['id_siswa']) ?>" class="btn btn-info btn-sm">
                                <i class="ri-eye-line"></i> Show
                            </a>
                        <a href="<?= base_url('siswa/edit/' . $s['id_siswa']) ?>" class="btn btn-edit btn-sm">
                            <i class="ri-edit-line"></i>Edit
                        </a>
                        <button onclick="confirmDelete('<?= base_url('siswa/hapus/' . $s['id_siswa']) ?>')" class="btn btn-danger btn-sm">
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
    'title'    => 'Daftar Siswa',
    'subtitle' => 'Sistem Informasi Sekolah',
    'content'  => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [
        'total_siswa' => count($siswa ?? []),
    ],
]);
?>
