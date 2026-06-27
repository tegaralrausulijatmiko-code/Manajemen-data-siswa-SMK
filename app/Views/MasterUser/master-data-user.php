<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Kelola Data User</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / User</div>
    </div>
    <a href="<?= base_url('user/tambah') ?>" class="btn btn-primary btn-sm">
        <i class="ri-user-add-line"></i> Tambah User
    </a>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar User</div>
        <form method="get" style="display:flex; gap:8px; align-items:center;">
            <div class="search-box">
                <i class="ri-search-line"></i>
                <input type="text" name="q" placeholder="Cari nama / username..." value="<?= esc($keyword ?? '') ?>">
            </div>
            <select name="role" class="form-control" style="width:130px; height:36px; font-size:13px;" onchange="this.form.submit()">
                <option value="">Semua Role</option>
                <option value="admin" <?= ($role ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="guru"  <?= ($role ?? '') === 'guru'  ? 'selected' : '' ?>>Guru</option>
                <option value="bk"    <?= ($role ?? '') === 'bk'    ? 'selected' : '' ?>>BK</option>
            </select>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th width="100">Role</th>
                    <th width="100">Status</th>
                    <th width="270">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr><td colspan="6">
                    <div class="empty-state">
                        <i class="ri-group-line"></i>
                        <p>Tidak ada data user</p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($users as $i => $u): ?>
                <tr>
                    <td><?= (($pagination['page'] ?? 1) - 1) * ($pagination['per_page'] ?? 10) + $i + 1 ?></td>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">

                            <div>
                                <strong><?= esc($u['nama']) ?></strong>
                            </div>
                        </div>
                    </td>
                    <td>
                        <code style="background:var(--bg-secondary); padding:2px 7px; border-radius:4px; font-size:12px;">
                            <?= esc($u['username']) ?>
                        </code>
                    </td>
                    <td>
                        <?php
                        $roleMap = [
                            'admin' => ['label' => 'Admin', 'class' => 'badge-admin'],
                            'guru'  => ['label' => 'Guru',  'class' => 'badge-l'],
                            'bk'    => ['label' => 'BK',    'class' => 'badge-bk'],
                        ];
                        $r = $roleMap[$u['role']] ?? ['label' => $u['role'], 'class' => ''];
                        ?>
                        <span class="badge <?= $r['class'] ?>"><?= $r['label'] ?></span>
                    </td>
                    <td>
                        <span class="badge <?= $u['status'] === 'aktif' ? 'badge-aktif' : 'badge-nonaktif' ?>">
                            <?= $u['status'] === 'aktif' ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= base_url('user/edit/' . $u['id_user']) ?>" class="btn btn-edit btn-sm" title="Edit">
                            <i class="ri-edit-line"></i> Edit
                        </a>

                        <!-- Toggle Status -->
                        <?php if ($u['role'] !== 'admin'): ?>
                          <form method="post" action="<?= base_url('user/toggle-status/' . $u['id_user']) ?>" style="display:inline;">
                              <?= csrf_field() ?>
                              <button type="submit" class="btn btn-sm <?= $u['status'] === 'aktif' ? 'btn-warning' : 'btn-success' ?>" title="<?= $u['status'] === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                  <i class="ri-<?= $u['status'] === 'aktif' ? 'forbid' : 'checkbox-circle' ?>-line"></i>
                                  <?= $u['status'] === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                              </button>
                          </form>

                          <button onclick="confirmDelete('<?= base_url('user/hapus/' . $u['id_user']) ?>')" class="btn btn-danger btn-sm" title="Hapus">
                              <i class="ri-delete-bin-line"></i>
                          </button>
                        <?php endif; ?>

                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?= view('Template/partials/pagination', ['pagination' => $pagination ?? null]) ?>
</div>

<style>
.avatar-circle {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: var(--primary);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    flex-shrink: 0;
}

.badge-admin   { background: #6f42c1; color: #fff; }
.badge-bk      { background: #0d6efd; color: #fff; }
.badge-aktif   { background: #198754; color: #fff; }
.badge-nonaktif{ background: #6c757d; color: #fff; }

.btn-warning {
    background: #ffc107;
    color: #212529;
    border: none;
}
.btn-warning:hover { background: #e0a800; }

.btn-success {
    background: #198754;
    color: #fff;
    border: none;
}
.btn-success:hover { background: #157347; }
</style>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'       => 'Daftar User',
    'subtitle'    => 'Sistem Informasi Sekolah',
    'content'     => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [
        'total_guru' => count($users ?? []),
    ],
]);
?>