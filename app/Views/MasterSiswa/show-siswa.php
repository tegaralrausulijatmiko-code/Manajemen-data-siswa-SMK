<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Detail Siswa</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('siswa') ?>">Siswa</a> /
            <?= esc($siswa['nama_siswa']) ?>
        </div>
    </div>
    <div style="display:flex; gap:8px;">
        <a href="<?= base_url('siswa/edit/' . $siswa['id_siswa']) ?>" class="btn btn-edit btn-sm">
            <i class="ri-edit-line"></i> Edit
        </a>
        <a href="<?= base_url('siswa') ?>" class="btn btn-secondary btn-sm">
            <i class="ri-arrow-left-line"></i> Kembali
        </a>
    </div>
</div>

<div style="display:grid; grid-template-columns: 280px 1fr; gap:20px; align-items:start;">

    <!-- Kartu Foto & Identitas Singkat -->
    <div class="card" style="text-align:center; padding:30px 20px;">
        <?php if (! empty($siswa['foto'])): ?>
            <img src="<?= base_url('uploads/' . $siswa['foto']) ?>"
                 alt="Foto <?= esc($siswa['nama_siswa']) ?>"
                 style="width:120px; height:120px; border-radius:50%; object-fit:cover; margin:0 auto 16px; display:block; border:4px solid var(--bg-secondary);">
        <?php else: ?>
            <div style="width:120px; height:120px; border-radius:50%; background:var(--bg-secondary);
                        display:flex; align-items:center; justify-content:center;
                        margin:0 auto 16px; font-size:3rem; color:var(--text-muted);">
                <i class="ri-user-line"></i>
            </div>
        <?php endif; ?>

        <h4 style="margin:0 0 4px; font-size:1.1rem;"><?= esc($siswa['nama_siswa']) ?></h4>
        <p style="margin:0 0 12px; color:var(--text-muted); font-size:0.85rem;">
            <?= esc($siswa['nisn']) ?>
        </p>

        <span class="badge <?= $siswa['jenis_kelamin'] === 'L' ? 'badge-l' : 'badge-p' ?>" style="font-size:0.85rem; padding:6px 16px;">
            <?= $siswa['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
        </span>

        <hr style="margin:20px 0; border:none; border-top:1px solid var(--border);">

        <div style="text-align:left; display:flex; flex-direction:column; gap:12px;">
            <div>
                <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:2px;">Kelas</div>
                <strong><?= esc($siswa['nama_kelas'] ?? '-') ?></strong>
            </div>
            <div>
                <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:2px;">Tingkat</div>
                <span class="badge <?= $siswa['tingkat'] === 'X' ? 'badge-l' : ($siswa['tingkat'] === 'XI' ? 'badge-aktif' : 'badge-p') ?>">
                    Kelas <?= esc($siswa['tingkat'] ?? '-') ?>
                </span>
            </div>
            <div>
                <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:2px;">Jurusan</div>
                <strong><?= esc($siswa['kode_jurusan'] ?? '') ?></strong>
                <div style="font-size:0.82rem; color:var(--text-muted);"><?= esc($siswa['nama_jurusan'] ?? '-') ?></div>
            </div>
            <div>
                <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:2px;">Wali Kelas</div>
                <strong><?= esc($siswa['nama_guru'] ?? '-') ?></strong>
            </div>
            <div>
                <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:2px;">Status</div>
                <span class="badge badge-aktif">Aktif</span>
            </div>
        </div>
    </div>

    <!-- Detail Lengkap -->
    <div style="display:flex; flex-direction:column; gap:20px;">

        <!-- Info Kontak -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="ri-contacts-line" style="color:var(--primary);"></i> Informasi Siswa</div>
            </div>
            <div style="padding:20px; display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                <div>
                    <div style="font-size:0.78rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px;">No. HP</div>
                    <?php if (! empty($siswa['no_hp'])): ?>
                        <a href="tel:<?= esc($siswa['no_hp']) ?>" style="color:var(--primary); font-weight:600; text-decoration:none;">
                            <i class="ri-phone-line"></i> <?= esc($siswa['no_hp']) ?>
                        </a>
                    <?php else: ?>
                        <span style="color:var(--text-muted);">-</span>
                    <?php endif; ?>
                </div>
                <div>
                    <div style="font-size:0.78rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px;">NISN</div>
                    <code style="font-size:0.95rem; background:var(--bg-secondary); padding:3px 8px; border-radius:6px;">
                        <?= esc($siswa['nisn']) ?>
                    </code>
                </div>
                <div style="grid-column:1/-1;">
                    <div style="font-size:0.78rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px;">Alamat</div>
                    <p style="margin:0; line-height:1.6;">
                        <?= ! empty($siswa['alamat']) ? esc($siswa['alamat']) : '<span style="color:var(--text-muted);">-</span>' ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Info Akademik -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="ri-book-open-line" style="color:var(--primary);"></i> Informasi Akademik</div>
                <a href="<?= base_url('kelas/show/' . $siswa['id_kelas']) ?>" class="btn btn-secondary btn-sm">
                    <i class="ri-building-4-line"></i> Lihat Kelas
                </a>
            </div>
            <div style="padding:20px; display:grid; grid-template-columns:repeat(3,1fr); gap:20px;">
                <div style="background:var(--bg-secondary); border-radius:10px; padding:16px; text-align:center;">
                    <div style="font-size:1.5rem; font-weight:700; color:var(--primary);"><?= esc($siswa['nama_kelas'] ?? '-') ?></div>
                    <div style="font-size:0.8rem; color:var(--text-muted); margin-top:4px;">Nama Kelas</div>
                </div>
                <div style="background:var(--bg-secondary); border-radius:10px; padding:16px; text-align:center;">
                    <div style="font-size:1.5rem; font-weight:700; color:var(--primary);"><?= esc($siswa['tingkat'] ?? '-') ?></div>
                    <div style="font-size:0.8rem; color:var(--text-muted); margin-top:4px;">Tingkat</div>
                </div>
                <div style="background:var(--bg-secondary); border-radius:10px; padding:16px; text-align:center;">
                    <div style="font-size:1.5rem; font-weight:700; color:var(--primary);"><?= esc($siswa['kode_jurusan'] ?? '-') ?></div>
                    <div style="font-size:0.8rem; color:var(--text-muted); margin-top:4px;"><?= esc($siswa['nama_jurusan'] ?? 'Jurusan') ?></div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'       => 'Detail Siswa — ' . $siswa['nama_siswa'],
    'subtitle'    => 'Sistem Informasi Sekolah',
    'content'     => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [],
]);
?>