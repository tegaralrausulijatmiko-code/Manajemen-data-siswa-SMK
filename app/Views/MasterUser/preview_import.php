<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Preview Import User</h3>
        <div class="breadcrumb"><a href="<?= base_url('dashboard') ?>">Dashboard</a> / <a href="<?= base_url('user') ?>">User</a> / Preview</div>
    </div>
    <a href="<?= base_url('user') ?>" class="btn btn-secondary btn-sm">
        <i class="ri-arrow-go-back-line"></i> Batalkan & Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Konfirmasi Data User yang Akan Dibuat</div>
    </div>
    
    <div style="padding: 20px;">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="alert alert-info" style="background:#e7f5ff; border:1px solid #bde0fe; color:#0b5394; padding:12px 16px; border-radius:8px; margin-bottom:15px; font-size:13px;">
            <i class="ri-information-line"></i> Periksa data di bawah ini. Jika ada yang berstatus <strong>Gagal</strong>, data tersebut tidak akan ikut disimpan. Jika sudah yakin, klik tombol <strong>Konfirmasi & Simpan</strong>.
        </div>

        <form action="<?= base_url('user/import/save') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>NIP (Username)</th>
                            <th>Nama Lengkap</th>
                            <th width="100">Role</th>
                            <th width="200">Status Pengecekan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $canSave = false; ?>
                        <?php if (!empty($preview_data)): ?>
                            <?php foreach ($preview_data as $i => $row): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><code style="background:var(--bg-secondary); padding:2px 7px; border-radius:4px; font-size:12px;"><?= esc($row['nip']) ?></code></td>
                                    <td><strong><?= esc($row['nama']) ?></strong></td>
                                    <td>
                                        <span class="badge <?= $row['role'] == 'bk' ? 'badge-bk' : 'badge-l' ?>">
                                            <?= strtoupper(esc($row['role'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] === 'OK'): ?>
                                            <span class="badge badge-aktif"><?= $row['status'] ?></span>
                                            <input type="hidden" name="users[<?= $i ?>][nip]" value="<?= esc($row['nip']) ?>">
                                            <input type="hidden" name="users[<?= $i ?>][nama]" value="<?= esc($row['nama']) ?>">
                                            <input type="hidden" name="users[<?= $i ?>][role]" value="<?= esc($row['role']) ?>">
                                            <input type="hidden" name="users[<?= $i ?>][password]" value="<?= esc($row['password']) ?>">
                                            <?php $canSave = true; ?>
                                        <?php else: ?>
                                            <span class="badge badge-nonaktif" style="background:#dc3545;"><?= $row['status'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="ri-file-excel-line"></i>
                                        <p>Tidak ada data untuk dipratinjau.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <?php if ($canSave): ?>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="ri-save-3-line"></i> Konfirmasi & Simpan Data Valid
                    </button>
                <?php else: ?>
                    <div class="alert alert-danger" style="margin:0;">Tidak ada data yang valid untuk disimpan. Silakan periksa kembali Excel Anda.</div>
                <?php endif; ?>
                <a href="<?= base_url('user') ?>" class="btn btn-danger btn-sm">
                    <i class="ri-close-line"></i> Batalkan
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.badge-bk { background: #0d6efd; color: #fff; }
.badge-l { background: #198754; color: #fff; }
.badge-aktif { background: #198754; color: #fff; }
</style>

<?php
 $content = ob_get_clean();
echo view('Template/layout', [
    'title'       => 'Preview Import User',
    'subtitle'    => 'Sistem Informasi Sekolah',
    'content'     => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [],
]);
?>