<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Kelola Tahun Ajaran</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / Tahun Ajaran</div>
    </div>
    <a href="<?= base_url('tahun-ajaran/tambah') ?>" class="btn btn-primary btn-sm"><i class="ri-add-line"></i> Tambah Tahun Ajaran</a>
</div>

<div class="card">
    <div class="card-header"><div class="card-title">Daftar Tahun Ajaran & Semester</div></div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tahun_ajaran)): ?>
                    <tr><td colspan="5"><div class="empty-state"><i class="ri-calendar-line"></i><p>Tidak ada data tahun ajaran</p></div></td></tr>
                <?php else: ?>
                    <?php foreach ($tahun_ajaran as $i => $t): ?>
                        <tr>
                            <td><?= (($pagination['page'] ?? 1) - 1) * ($pagination['per_page'] ?? 10) + $i + 1 ?></td>
                            <td><strong><?= esc($t['tahun_ajaran']) ?></strong></td>
                            <td><?= esc($t['semester']) ?></td>
                            <td><span class="badge <?= $t['status'] == 'Aktif' ? 'badge-aktif' : 'badge-nonaktif' ?>"><?= esc($t['status']) ?></span></td>
                            <td>
                                <a href="<?= base_url('tahun-ajaran/edit/' . $t['id_tahun_ajaran']) ?>" class="btn btn-edit btn-sm"><i class="ri-edit-line"></i>Edit</a>
                                <button onclick="confirmDelete('<?= base_url('tahun-ajaran/hapus/' . $t['id_tahun_ajaran']) ?>')" class="btn btn-danger btn-sm"><i class="ri-delete-bin-line"></i>Hapus</button>
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
echo view('Template/layout', ['title' => 'Tahun Ajaran', 'subtitle' => 'Sistem Informasi Sekolah', 'content' => $content]);
?>
