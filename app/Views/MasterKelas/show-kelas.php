<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Detail Kelas</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('kelas') ?>">Kelas</a> /
            <?= esc($kelas['nama_kelas']) ?>
        </div>
    </div>
    <a href="<?= base_url('kelas') ?>" class="btn btn-secondary btn-sm">
        <i class="ri-arrow-left-line"></i> Kembali
    </a>
</div>

<!-- Info Kelas -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-header">
        <div class="card-title">Informasi Kelas</div>
    </div>
    <div style="padding: 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        
        <div style="display:flex; flex-direction:column; gap:4px;">
            <span style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em;">Nama Kelas</span>
            <strong style="font-size:1.1rem;"><?= esc($kelas['nama_kelas']) ?></strong>
        </div>

        <div style="display:flex; flex-direction:column; gap:4px;">
            <span style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em;">Jurusan</span>
            <strong><?= esc($kelas['kode_jurusan'] . ' – ' . $kelas['nama_jurusan']) ?></strong>
        </div>

        <!-- <div style="display:flex; flex-direction:column; gap:4px;">
            <span style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em;">Tingkat</span>
            <span class="badge <?= $kelas['tingkat'] === 'X' ? 'badge-l' : ($kelas['tingkat'] === 'XI' ? 'badge-aktif' : 'badge-p') ?>">
                Kelas <?= esc($kelas['tingkat']) ?>
            </span>
        </div> -->

        <div style="display:flex; flex-direction:column; gap:4px;">
            <span style="font-size:0.8rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em;">Wali Kelas</span>
            <strong><?= esc($kelas['nama_guru'] ?? '-') ?></strong>
        </div>
    </div>

    <!-- Statistik Siswa -->
    <div style="padding: 0 20px 20px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
        <div style="background:var(--bg-secondary); border-radius:10px; padding:16px; text-align:center;">
            <div style="font-size:2rem; font-weight:700; color:var(--primary);"><?= $kelas['jumlah_siswa'] ?></div>
            <div style="font-size:0.85rem; color:var(--text-muted);">Total Siswa</div>
        </div>
        <div style="background:var(--bg-secondary); border-radius:10px; padding:16px; text-align:center;">
            <div style="font-size:2rem; font-weight:700; color:#3b82f6;"><?= $kelas['jumlah_laki'] ?></div>
            <div style="font-size:0.85rem; color:var(--text-muted);">Laki-laki</div>
        </div>
        <div style="background:var(--bg-secondary); border-radius:10px; padding:16px; text-align:center;">
            <div style="font-size:2rem; font-weight:700; color:#ec4899;"><?= $kelas['jumlah_perempuan'] ?></div>
            <div style="font-size:0.85rem; color:var(--text-muted);">Perempuan</div>
        </div>
    </div>
</div>

<!-- Tabel Siswa -->
<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Siswa — <?= esc($kelas['nama_kelas']) ?></div>
        <a href="<?= base_url('siswa/tambah?id_kelas=' . $kelas['id_kelas']) ?>" class="btn btn-primary btn-sm">
            <i class="ri-add-line"></i> Tambah Siswa
        </a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th width="70">Foto</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Jenis Kelamin</th>
                    <th>No. HP</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kelas['siswa'])): ?>
                <tr><td colspan="7">
                    <div class="empty-state">
                        <i class="ri-user-line"></i>
                        <p>Belum ada siswa di kelas ini</p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($kelas['siswa'] as $i => $s): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td>
                        <?php if ($s['foto']): ?>
                            <img src="<?= base_url('uploads/' . $s['foto']) ?>"
                                 style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                        <?php else: ?>
                            <div style="width:44px; height:44px; border-radius:8px; background:#eff6ff; color:var(--primary); display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                                <i class="ri-user-line"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><code><?= esc($s['nisn']) ?></code></td>
                    <td><strong><?= esc($s['nama_siswa']) ?></strong></td>
                    <td>
                        <span class="badge <?= $s['jenis_kelamin'] === 'L' ? 'badge-l' : 'badge-p' ?>">
                            <?= $s['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                        </span>
                    </td>
                    <td><?= esc($s['no_hp'] ?: '-') ?></td>
                    <td>

                        <a href="<?= base_url('siswa/edit/' . $s['id_siswa'] . '?id_kelas=' . $kelas['id_kelas']) ?>" class="btn btn-edit btn-sm">
                            <i class="ri-edit-line"></i> Edit
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
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'       => 'Detail Kelas — ' . $kelas['nama_kelas'],
    'subtitle'    => 'Sistem Informasi Sekolah',
    'content'     => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [],
]);
?>