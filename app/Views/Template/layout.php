<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa SMK Harapan Bangsa</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1e40af;
            --primary-light: #3b82f6;
            --primary-dark: #1e3a8a;
            --secondary: #64748b;
            --bg-light: #f1f5f9;
            --white: #ffffff;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --border: #e2e8f0;
            --sidebar-width: 260px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }

        body { background-color: var(--bg-light); color: var(--text-dark); display: flex; height: 100vh; overflow: hidden; }

        /* Sidebar */
        .sidebar { width: var(--sidebar-width); background-color: var(--primary); color: var(--white); display: flex; flex-direction: column; flex-shrink: 0; }
        .brand { padding: 20px; font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,0.1); line-height: 1.4; }
        .brand small { display: block; font-weight: 400; font-size: 0.8rem; opacity: 0.8; margin-top: 4px; }

        .nav-menu { list-style: none; padding: 15px; flex: 1; overflow-y: auto; }
        .nav-item { margin-bottom: 4px; }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 6px; text-decoration: none; color: rgba(255,255,255,0.8); transition: 0.2s; }
        .nav-link:hover, .nav-link.active { background-color: rgba(255,255,255,0.15); color: var(--white); }
        .nav-link i { font-size: 1.2rem; }

        .sidebar-footer { padding: 15px 20px; border-top: 1px solid rgba(255,255,255,0.1); font-size: 0.78rem; opacity: 0.6; }

        .nav-title{
            padding: 14px 18px 6px;
            font-size: 11px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .08em;
            pointer-events: none;
        }

        /* Main */
        .main-content { flex: 1; overflow-y: auto; display: flex; flex-direction: column; }

        header { background: var(--white); padding: 15px 30px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02); position: sticky; top: 0; z-index: 100; }
        .header-info h2 { font-size: 1.25rem; color: var(--primary); }
        .header-info p { font-size: 0.85rem; color: var(--text-light); }
        .header-right { font-size: 0.8rem; color: var(--text-light); text-align: right; line-height: 1.5; }
        .header-right strong { color: var(--text-dark); }

        .content-body { padding: 30px; }

        /* Card */
        .card { background: var(--white); border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 25px; }
        .card-header { padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .card-title { font-size: 1.1rem; font-weight: 600; }

        /* Start */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: var(--white); padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 15px; }
        .stat-icon { width: 48px; height: 48px; border-radius: 8px; background: #eff6ff; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
        .stat-text h4 { font-size: 1.5rem; font-weight: 700; }
        .stat-text p { font-size: 0.85rem; color: var(--text-light); }

        /* Table */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th, td { padding: 12px 20px; text-align: left; border-bottom: 1px solid var(--border); }
        th { background-color: #f8fafc; font-weight: 600; color: var(--text-light); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; white-space: nowrap; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: #f8fafc; }

        /* Search */
        .search-box { position: relative; }
        .search-box input { padding: 8px 12px 8px 35px; border: 1px solid var(--border); border-radius: 6px; font-size: 0.9rem; width: 250px; transition: 0.2s; }
        .search-box input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1); }
        .search-box i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--text-light); }

        /* Badges */
        .badge { padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; display: inline-block; }
        .badge-prod { background-color: #dcfce7; color: #166534; }
        .badge-non-prod { background-color: #fee2e2; color: #991b1b; }
        .badge-light { background-color: #dbeafe; color: #1e40af; }
        .badge-l { background-color: #dbeafe; color: #1e40af; }
        .badge-p { background-color: #fce7f3; color: #9d174d; }
        .badge-aktif { background-color: #dcfce7; color: #166534; }
        .badge-warning { background-color: #fef3c7; color: #f59e0b; }
        .badge-nonaktif { background-color: #fee2e2; color: #991b1b; }

        /* Button */
        .btn { padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s; text-decoration: none; font-weight: 500; }
        .btn-primary { background-color: var(--primary); color: var(--white); }
        .btn-primary:hover { background-color: var(--primary-dark); color: var(--white); }
        .btn-sm { padding: 5px 12px; font-size: 0.8rem; }
        .btn-danger { background-color: #fee2e2; color: var(--danger); }
        .btn-danger:hover { background-color: #fecaca; }
        .btn-edit { background-color: #e0f2fe; color: var(--primary); }
        .btn-edit:hover { background-color: #bae6fd; }
        .btn-warning { background-color: #fef3c7; color: #92400e; }
        .btn-warning:hover { background-color: #fde68a; }
        .btn-secondary { background-color: var(--bg-light); color: var(--text-dark); border: 1px solid var(--border); }
        .btn-info { background: #3b82f6; /* Biru */ color: #fff; }
        .btn-info:hover {background: #2563eb;}

        /* Forms */
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; margin-bottom: 6px; font-size: 0.9rem; font-weight: 500; color: var(--text-dark); }
        .form-label .required { color: var(--danger); margin-left: 2px; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 0.95rem; transition: 0.2s; background: var(--white); }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1); }
        select.form-control { cursor: pointer; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .form-actions { display: flex; gap: 10px; margin-top: 25px; }

        /* Alerts */
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 0.9rem; }
        .alert-success { background: #dcfce7; color: #166534; border-left: 4px solid var(--success); }
        .alert-danger { background: #fee2e2; color: #991b1b; border-left: 4px solid var(--danger); }
        .alert-warning { background: #fef3c7; color: #92400e; border-left: 4px solid var(--warning); }

        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; color: var(--text-light); }
        .empty-state i { font-size: 3rem; margin-bottom: 15px; opacity: 0.4; }
        .empty-state p { font-size: 0.95rem; }

        /* Page Header */
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .page-header h3 { font-size: 1.1rem; color: var(--text-dark); }
        .breadcrumb { font-size: 0.82rem; color: var(--text-light); }
        .breadcrumb a { color: var(--primary); text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }

        /* Modal */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; opacity: 0; visibility: hidden; transition: 0.2s; }
        .modal-overlay.open { opacity: 1; visibility: visible; }
        .modal { background: var(--white); width: 100%; max-width: 420px; border-radius: 12px; padding: 25px; transform: translateY(-20px); transition: 0.2s; text-align: center; }
        .modal-overlay.open .modal { transform: translateY(0); }
        .modal-footer { margin-top: 20px; display: flex; justify-content: center; gap: 10px; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    <?= $extra_css ?? '' ?>
</head>
<body>

    <!-- Sidebar -->
    <?= view('Template/partials/sidebar') ?>

    <!-- Main -->
    <div class="main-content">
        <?= view('Template/partials/header', [
            'title'       => $title ?? '',
            'subtitle'    => $subtitle ?? 'Sistem Informasi Sekolah',
            'header_view' => $header_view ?? null,
            'header_data' => $header_data ?? null,
            'header_html' => $header_html ?? null,
        ]) ?>

        <div class="content-body">

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="ri-checkbox-circle-line"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?= $content ?? '' ?>

        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div class="modal-overlay" id="delete-modal">
        <div class="modal">
            <i class="ri-error-warning-line" style="font-size:3rem; color:var(--danger);"></i>
            <h3 style="margin:10px 0;">Hapus Data?</h3>
            <p style="color:var(--text-light); font-size:0.9rem;">Data yang dihapus tidak dapat dikembalikan.</p>
            <form method="post" action="#" id="confirm-delete-form" class="modal-footer">
                <?= csrf_field() ?>
                <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('delete-modal').classList.remove('open')">Batal</button>
                <button type="submit" class="btn btn-danger btn-sm"><i class="ri-delete-bin-line"></i> Ya, Hapus</button>
            </form>
        </div>
    </div>

    <script>
        // Highlight active nav
        const currentPath = window.location.pathname.replace(/\/$/, '');
        document.querySelectorAll('.nav-link').forEach(link => {
            const href = link.getAttribute('href');
            if (!href) return;

            const linkUrl = new URL(href, window.location.origin);
            const linkPath = linkUrl.pathname.replace(/\/$/, '');

            if (currentPath === linkPath || (linkPath !== '' && currentPath.endsWith(linkPath))) {
                link.classList.add('active');
            }
        });

        // Confirm delete
        function confirmDelete(url, name = '') {
            const modal = document.getElementById('delete-modal');
            document.getElementById('confirm-delete-form').action = url;
            modal.classList.add('open');
        }
        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('open');
        });

        // Auto-dismiss alerts
        document.querySelectorAll('.alert').forEach(el => {
            setTimeout(() => el.style.transition = 'opacity 0.5s', 2500);
            setTimeout(() => el.style.opacity = '0', 3000);
            setTimeout(() => el.remove(), 3500);
        });
    </script>
    <?= $extra_js ?? '' ?>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
</body>
</html>
