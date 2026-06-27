<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Tambah User</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('user') ?>">User</a> /
            Tambah
        </div>
    </div>
</div>

<div class="card" style="max-width:640px;">
    <div class="card-header">
        <div class="card-title">Form Tambah User</div>
    </div>
    <div style="padding:24px;">

        <form method="post" action="<?= base_url('user/simpan') ?>" id="formUser">
            <?= csrf_field() ?>

            <!-- Role — ditampilkan pertama agar field guru muncul dinamis -->
            <div class="form-group">
                <label class="form-label">Role <span class="text-danger">*</span></label>
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <?php
                    $roles = ['guru' => 'Guru', 'bk' => 'BK'];
                    foreach ($roles as $val => $label):
                        $checked = (($old['role'] ?? '') === $val) ? 'checked' : '';
                    ?>
                    <label class="role-card <?= $checked ? 'selected' : '' ?>" data-role="<?= $val ?>">
                        <input type="radio" name="role" value="<?= $val ?>" <?= $checked ?> hidden>
                        <i class="ri-<?= $val === 'guru' ? 'book-open' : 'hearts' ?>-line" style="font-size:20px;"></i>
                        <span><?= $label ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                  <?php if (isset($errors['role'])): ?>
                      <small style="color:var(--danger);"><?= $errors['role'] ?></small>
                  <?php endif; ?>
            </div>

            <!-- Pilih Guru  -->
            <div class="form-group" id="groupGuru">
                <label class="form-label">Pilih Guru <span class="text-danger">*</span></label>
                <select name="id_guru" class="form-control" id="selectGuru">
                    <option value="">-- Pilih Guru --</option>
                    <?php foreach ($guruList as $g): ?>
                    <option value="<?= $g['id_guru'] ?>"
                        data-nama="<?= esc($g['nama_guru']) ?>"
                        data-nip="<?= esc($g['nip']) ?>"
                        <?= (($old['id_guru'] ?? '') == $g['id_guru']) ? 'selected' : '' ?>>
                        <?= esc($g['nama_guru']) ?> — <?= esc($g['nip']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                  <?php if (isset($errors['id_guru'])): ?>
                      <small style="color:var(--danger);"><?= $errors['id_guru'] ?></small>
                  <?php endif; ?>
            </div>

            <!-- Nama -->
            <div class="form-group" id="groupNama">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" id="inputNama"
                       value="<?= esc($old['nama'] ?? '') ?>" placeholder="Masukkan nama lengkap" readonly>
                <small class="text-muted">Nama akan otomatis terisi dari data guru yang dipilih.</small>

            </div>

            <!-- Username -->
            <div class="form-group">
                <label class="form-label">Username <span class="text-danger">*</span></label>
                <div style="position:relative;">
                    <input type="text" name="username" class="form-control" id="inputUsername"
                           value="<?= esc($old['username'] ?? '') ?>"
                           placeholder="Username otomatis dari NIP guru"
                           <?= in_array(($old['role'] ?? ''), ['guru', 'bk']) ? 'readonly style="background:var(--bg-secondary);"' : '' ?> readonly>
                    <button type="button" id="btnAutoUsername" class="btn btn-sm"
                            style="position:absolute; right:6px; top:50%; transform:translateY(-50%); background:var(--bg-secondary); border:1px solid var(--border); font-size:11px; display:none;">
                        <i class="ri-magic-line"></i> Auto
                    </button>
                </div>
                <small class="text-muted" id="hintUsername">Username otomatis dari NIP guru saat guru dipilih.</small>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label">Password <span class="text-danger">*</span></label>
                <div style="position:relative;">
                    <input type="password" name="password" class="form-control" id="inputPassword"
                           placeholder="Minimal 6 karakter">
                    <button type="button" onclick="togglePassword('inputPassword', this)"
                            style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:var(--text-muted);">
                        <i class="ri-eye-line"></i>
                    </button>
                </div>
                <small class="text-muted" id="hintPassword">Minimal 6 karakter.</small>
                  <?php if (isset($errors['password'])): ?>
                      <small style="color:var(--danger);"><?= $errors['password'] ?></small>
                  <?php endif; ?>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <div style="display:flex; gap:12px;">
                    <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
                        <input type="radio" name="status" value="aktif"
                               <?= (($old['status'] ?? 'aktif') === 'aktif') ? 'checked' : '' ?>>
                        <span class="badge badge-aktif">Aktif</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
                        <input type="radio" name="status" value="nonaktif"
                               <?= (($old['status'] ?? '') === 'nonaktif') ? 'checked' : '' ?>>
                        <span class="badge badge-nonaktif">Nonaktif</span>
                    </label>
                </div>
            </div>

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i> Simpan User
                </button>
                <a href="<?= base_url('user') ?>" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.role-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    padding: 12px 20px;
    border: 2px solid var(--border);
    border-radius: 10px;
    cursor: pointer;
    font-size: 13px;
    color: var(--text-muted);
    transition: all .15s;
    min-width: 80px;
}
.role-card:hover  { border-color: var(--primary); color: var(--primary); }
.role-card.selected { border-color: var(--primary); background: var(--primary); color: #fff; }

.badge-aktif    { background:#198754; color:#fff; }
.badge-nonaktif { background:#6c757d; color:#fff; }

.alert-danger {
    background: #fff5f5;
    border: 1px solid #fca5a5;
    border-radius: 8px;
    padding: 12px 16px;
    color: #b91c1c;
    display: flex;
    gap: 8px;
    align-items: flex-start;
}
</style>

<script>
(function () {
    const roleCards    = document.querySelectorAll('.role-card');
    const groupGuru    = document.getElementById('groupGuru');
    const groupNama    = document.getElementById('groupNama');
    const selectGuru   = document.getElementById('selectGuru');
    const inputNama    = document.getElementById('inputNama');
    const inputUsername= document.getElementById('inputUsername');
    const btnAuto      = document.getElementById('btnAutoUsername');
    const hintUser     = document.getElementById('hintUsername');
    const hintPass     = document.getElementById('hintPassword');

    function applyRole(role) {
        roleCards.forEach(c => c.classList.toggle('selected', c.dataset.role === role));

        // Guru dan BK wajib memilih data guru
        if (role === 'guru' || role === 'bk') {
            groupGuru.style.display = 'block';
        } else {
            groupGuru.style.display = 'none';
            selectGuru.value = '';
        }
    }

    // Klik role card
    roleCards.forEach(card => {
        card.addEventListener('click', () => {
            card.querySelector('input[type=radio]').checked = true;
            applyRole(card.dataset.role);
        });
    });

    // Auto-fill nama dari dropdown guru
    selectGuru.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];

        inputNama.value = opt.dataset.nama ?? '';
        inputUsername.value = opt.dataset.nip ?? '';
    });

    // Auto-fill username dari NIP
    btnAuto.addEventListener('click', function () {
        const opt = selectGuru.options[selectGuru.selectedIndex];
        const nip = opt ? (opt.dataset.nip || '') : '';
        if (nip) {
            inputUsername.value = nip;
        } else {
            alert('Pilih guru terlebih dahulu.');
        }
    });

    // Init saat page load (jika ada old input dari validasi)
    const activeRadio = document.querySelector('input[name="role"]:checked');
    if (activeRadio) applyRole(activeRadio.value);
}());

function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.innerHTML = isText ? '<i class="ri-eye-line"></i>' : '<i class="ri-eye-off-line"></i>';
}
</script>

<?php
$content = ob_get_clean();
echo view('Template/layout', [
    'title'       => 'Tambah User',
    'subtitle'    => 'Sistem Informasi Sekolah',
    'content'     => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [],
]);
?>