<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMK Harapan Bangsa</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: #f1f5f9; color: #1e293b; }
        .login-card { width: min(420px, calc(100% - 32px)); background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08); padding: 28px; }
        .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; color: #1e40af; }
        .brand i { font-size: 2rem; }
        .brand h1 { margin: 0; font-size: 1.35rem; }
        .brand p { margin: 3px 0 0; color: #64748b; font-size: 0.88rem; }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-size: 0.9rem; font-weight: 600; }
        input { width: 100%; padding: 11px 12px; border: 1px solid #e2e8f0; border-radius: 7px; font-size: 0.95rem; }
        input:focus { outline: none; border-color: #1e40af; box-shadow: 0 0 0 3px rgba(30,64,175,0.1); }
        button { width: 100%; border: 0; border-radius: 7px; padding: 11px 14px; background: #1e40af; color: #fff; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px; }
        .alert { padding: 11px 12px; border-radius: 7px; margin-bottom: 16px; font-size: 0.9rem; }
        .alert-danger { background: #fee2e2; color: #991b1b; }
        .alert-success { background: #dcfce7; color: #166534; }
        .hint { margin-top: 16px; color: #64748b; font-size: 0.82rem; text-align: center; }
    </style>
</head>
<body>
    <main class="login-card">
        <div class="brand">
            <i class="ri-graduation-cap-fill"></i>
            <div>
                <h1>SMK Harapan Bangsa</h1>
                <p>Sistem Informasi Sekolah</p>
            </div>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('login') ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= old('username') ?>" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit"><i class="ri-login-circle-line"></i> Login</button>
        </form>
        <p class="hint"></p>
    </main>
</body>
</html>
