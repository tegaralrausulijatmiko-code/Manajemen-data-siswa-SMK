<?php ob_start(); ?>

<div class="page-header">
    <div>
        <h3>Edit User</h3>
        <div class="breadcrumb">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a> /
            <a href="<?= base_url('user') ?>">User</a> /
            Edit
        </div>
    </div>
</div>

<div class="card" style="max-width:640px;">
    <div class="card-header">
        <div class="card-title">
            Edit User &mdash;
            <span style="font-weight:400; color:var(--text-muted);"><?= esc($user['nama']) ?></span>
        </div>
    </div>
    <div style="padding:24px;">

        <form method="post" action="<?= base_url('user/update/' . $user['id_user']) ?>">
            <?= csrf_field() ?>
            <!-- <input type="hidden" name="_method" value="PUT"> -->
            
            <!-- Role -->
            <div class="form-group">
                <label class="form-label">Role <span class="text-danger">*</span></label>
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                  <?php if ($user['role'] !== 'admin'): ?>
                    <?php
                      $roles = ['guru' => 'Guru', 'bk' => 'BK'];
                      foreach ($roles as $val => $label):
                          $checked = ($user['role'] === $val) ? 'checked' : '';
                    ?>
                  <label class="role-card <?= $checked ? 'selected' : '' ?>" data-role="<?= $val ?>">
                      <input type="radio" name="role" value="<?= $val ?>" <?= $checked ?> hidden>
                      <i class="ri-<?= $val === 'guru' ? 'book-open' : 'hearts' ?>-line" style="font-size:20px;"></i>
                      <span><?= $label ?></span>
                  </label>
                  <?php endforeach; ?>
                  
                  <?php else: ?>
                    <div class="role-card selected" style="cursor:default;">
                        <i class="ri-shield-user-line" style="font-size:20px;"></i>
                        <span>Admin</span>
                    </div>

                    <input type="hidden" name="role" value="admin">
                  <?php endif; ?>
                </div>
                  <?php if (isset($errors['role'])): ?>
                      <small style="color:var(--danger);"><?= $errors['role'] ?></small>
                  <?php endif; ?>
            </div>

            <!-- Pilih Guru -->
            <?php if ($user['role'] !== 'admin'): ?>
            <div class="form-group" id="groupGuru">
                <label class="form-label">Pilih Guru <span class="text-danger">*</span></label>
                <select name="id_guru" class="form-control" id="selectGuru">
                    <option value="">-- Pilih Guru --</option>
                    <?php foreach ($guruList as $g): ?>
                    <option value="<?= $g['id_guru'] ?>"
                        data-nama="<?= esc($g['nama_guru']) ?>"
                        data-nip="<?= esc($g['nip']) ?>"
                        <?= ($user['id_guru'] == $g['id_guru']) ? 'selected' : '' ?>>
                        <?= esc($g['nama_guru']) ?> — <?= esc($g['nip']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                  <?php if (isset($errors['id_guru'])): ?>
                      <small style="color:var(--danger);"><?= $errors['id_guru'] ?></small>
                  <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Nama -->
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" id="inputNama"
                       value="<?= esc($user['nama']) ?>"
                       <?= in_array(($user['role'] ?? ''), ['guru', 'bk']) ? 'readonly style="background:var(--bg-secondary);"' : '' ?> readonly>
            </div>

            <!-- Username -->
            <div class="form-group">
                <label class="form-label">Username <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control" id="inputUsername"
                       value="<?= esc($user['username']) ?>"
                       placeholder="Username otomatis dari NIP guru"
                       <?= in_array(($user['role'] ?? ''), ['guru', 'bk']) ? 'readonly style="background:var(--bg-secondary);"' : '' ?> readonly>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <div style="position:relative;">
                    <input type="password" name="password" class="form-control" id="inputPassword"
                           placeholder="Kosongkan jika tidak ingin mengubah password">
                    <button type="button" onclick="togglePassword('inputPassword', this)"
                            style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:var(--text-muted);">
                        <i class="ri-eye-line"></i>
                    </button>
                </div>
                <small class="text-muted">Kosongkan jika tidak ingin mengganti password. Minimal 6 karakter jika diisi.</small>
            </div>

            <!-- Status -->
            <?php if ($user['role'] !== 'admin'): ?>
              <div class="form-group">
                  <label class="form-label">Status <span class="text-danger">*</span></label>
                  <div style="display:flex; gap:12px;">
                      <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
                          <input type="radio" name="status" value="aktif"
                                <?= $user['status'] === 'aktif' ? 'checked' : '' ?>>
                          <span class="badge badge-aktif">Aktif</span>
                      </label>
                      <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
                          <input type="radio" name="status" value="nonaktif"
                                <?= $user['status'] === 'nonaktif' ? 'checked' : '' ?>>
                          <span class="badge badge-nonaktif">Nonaktif</span>
                      </label>
                  </div>
              </div>
            <?php endif; ?>
            

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i> Perbarui User
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
.role-card:hover   { border-color: var(--primary); color: var(--primary); }
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
    const roleCards  = document.querySelectorAll('.role-card');
    const groupGuru  = document.getElementById('groupGuru');
    const selectGuru = document.getElementById('selectGuru');
    const inputNama  = document.getElementById('inputNama');
    const inputUsername = document.getElementById('inputUsername');

    function applyRole(role) {
        roleCards.forEach(c => c.classList.toggle('selected', c.dataset.role === role));
    }

    roleCards.forEach(card => {
        card.addEventListener('click', () => {
            card.querySelector('input[type=radio]').checked = true;
            applyRole(card.dataset.role);
        });
    });

    selectGuru.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];

        inputNama.value = opt.dataset.nama ?? '';
        inputUsername.value = opt.dataset.nip ?? '';
    });
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
    'title'       => 'Edit User',
    'subtitle'    => 'Sistem Informasi Sekolah',
    'content'     => $content,
    'header_view' => 'Template/partials/header',
    'header_data' => [],
]);
?>