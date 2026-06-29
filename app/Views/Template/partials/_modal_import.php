
<?php
/**
 * Partial View: Partials/_modal_import.php
 *
 * Variabel yang dibutuhkan:
 *   $modul  (string) — nama modul, misal 'guru', 'siswa', dll.
 */

$labelMap = [
    'guru'    => 'Guru',
    'jurusan' => 'Jurusan',
    'kelas'   => 'Kelas',
    'siswa'   => 'Siswa',
    'mapel'   => 'Mata Pelajaran',
    'jadwal'  => 'Jadwal Pelajaran',
    'user'    => 'User',

];
$label    = $labelMap[$modul] ?? ucfirst($modul);
$actionUrl = base_url("import/$modul");
$templateUrl = base_url("import/template/$modul");
?>

<!-- ══════════════════════════════════════════

     ══════════════════════════════════════════ -->

<!-- Flash: import_errors -->
<?php if (session()->getFlashdata('import_errors')): ?>
<div class="alert alert-warning" style="
    background: #fff8e1; border: 1px solid #f9a825; border-radius: 10px;
    padding: 14px 18px; margin-bottom: 16px; font-size: 13px;">
    <div style="font-weight: 600; margin-bottom: 8px; color: #7b4f00;">
        <i class="ri-error-warning-line"></i> Detail baris yang dilewati/gagal:
    </div>
    <ul style="margin: 0; padding-left: 18px; color: #555;">
        <?php foreach (session()->getFlashdata('import_errors') as $err): ?>
            <li><?= esc($err) ?></li>
        <?php endforeach ?>
    </ul>
</div>
<?php endif ?>

<!-- Trigger Button -->
<button type="button"
        class="btn-import-trigger"
        onclick="document.getElementById('modalImport_<?= $modul ?>').classList.add('open')"
        title="Import <?= $label ?> dari Excel">
    <i class="ri-upload-2-line"></i>
    <span>Import Excel</span>
</button>

<!-- ══════════════════════════════════════════  MODAL  ══════════════════════════════════════════ -->
<div class="import-modal-overlay" id="modalImport_<?= $modul ?>">
    <div class="import-modal-box">

        <!-- Header -->
        <div class="import-modal-header">
            <div class="import-modal-title">
                <i class="ri-file-excel-2-line"></i>
                Import Data <?= $label ?>
            </div>
            <button type="button"
                    class="import-modal-close"
                    onclick="closeImportModal('<?= $modul ?>')">
                <i class="ri-close-line"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="import-modal-body">

            <!-- Step 1: Download template -->
            <div class="import-step">
                <div class="import-step-num">1</div>
                <div class="import-step-content">
                    <div class="import-step-title">Unduh Template Excel</div>
                    <p class="import-step-desc">
                        Gunakan template resmi agar kolom dan format data sesuai.
                        Jangan mengubah baris header (baris 1) dan baris contoh (baris 2).
                    </p>
                    <a href="<?= $templateUrl ?>" class="btn-download-template">
                        <i class="ri-download-2-line"></i>
                        Download Template <?= $label ?>
                    </a>
                </div>
            </div>

            <!-- Divider -->
            <div class="import-step-divider"></div>

            <!-- Step 2: Upload file -->
            <div class="import-step">
                <div class="import-step-num">2</div>
                <div class="import-step-content">
                    <div class="import-step-title">Upload File yang Sudah Diisi</div>
                    <p class="import-step-desc">
                        Format yang didukung: <strong>.xlsx</strong> atau <strong>.csv</strong>.
                        Baris yang duplikat atau tidak valid akan dilewati dan ditampilkan laporannya.
                    </p>

                    <form action="<?= $actionUrl ?>" method="post" enctype="multipart/form-data" id="formImport_<?= $modul ?>">
                        <?= csrf_field() ?>

                        <!-- Drop Zone -->
                        <div class="import-dropzone" id="dropzone_<?= $modul ?>"
                             onclick="document.getElementById('fileInput_<?= $modul ?>').click()">
                            <i class="ri-upload-cloud-2-line" style="font-size: 36px; color: var(--primary);"></i>
                            <div class="import-dropzone-text">
                                Klik atau <strong>drag & drop</strong> file di sini
                            </div>
                            <div class="import-dropzone-hint">.xlsx atau .csv, maks. 5 MB</div>
                            <div class="import-dropzone-filename" id="filename_<?= $modul ?>"></div>
                        </div>

                        <input type="file"
                               id="fileInput_<?= $modul ?>"
                               name="file_import"
                               accept=".xlsx,.csv"
                               style="display:none"
                               onchange="onFileSelected(this, '<?= $modul ?>')">

                        <!-- Submit -->
                        <div style="margin-top: 16px; display: flex; gap: 10px; justify-content: flex-end;">
                            <button type="button"
                                    class="btn-import-cancel"
                                    onclick="closeImportModal('<?= $modul ?>')">
                                Batal
                            </button>
                            <button type="submit"
                                    class="btn-import-submit"
                                    id="btnSubmit_<?= $modul ?>"
                                    disabled>
                                <i class="ri-upload-2-line"></i>
                                Mulai Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div><!-- /body -->
    </div>
</div>

<!-- ══════════════════════════════════════════  STYLE  ══════════════════════════════════════════ -->
<style>
/* Trigger button */
.btn-import-trigger {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 16px; border-radius: 8px; border: 1.5px solid var(--primary);
    background: transparent; color: var(--primary);
    font-size: 13px; font-weight: 600; cursor: pointer;
    transition: background .18s, color .18s;
}
.btn-import-trigger:hover { background: var(--primary); color: #fff; }

/* Overlay */
.import-modal-overlay {
    display: none; position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,.45); backdrop-filter: blur(2px);
    align-items: center; justify-content: center;
}
.import-modal-overlay.open { display: flex; }

/* Box */
.import-modal-box {
    background: #fff; border-radius: 14px; width: 100%; max-width: 540px;
    box-shadow: 0 20px 60px rgba(0,0,0,.22);
    animation: importSlideIn .22s ease;
    max-height: 92vh; overflow-y: auto;
}
@keyframes importSlideIn {
    from { transform: translateY(-24px); opacity: 0; }
    to   { transform: translateY(0);     opacity: 1; }
}

/* Header */
.import-modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 22px 14px; border-bottom: 1px solid #eee;
}
.import-modal-title {
    font-size: 15px; font-weight: 700; color: var(--text-dark);
    display: flex; align-items: center; gap: 8px;
}
.import-modal-title i { color: var(--primary); font-size: 18px; }
.import-modal-close {
    background: none; border: none; cursor: pointer;
    font-size: 20px; color: #888; line-height: 1;
    border-radius: 6px; padding: 2px 5px;
    transition: background .15s;
}
.import-modal-close:hover { background: #f0f0f0; color: #333; }

/* Body */
.import-modal-body { padding: 20px 22px 22px; }

/* Steps */
.import-step { display: flex; gap: 14px; }
.import-step-num {
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--primary); color: #fff;
    font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 1px;
}
.import-step-content { flex: 1; }
.import-step-title { font-size: 13px; font-weight: 700; color: var(--text-dark); margin-bottom: 4px; }
.import-step-desc  { font-size: 12.5px; color: #666; margin: 0 0 10px; line-height: 1.55; }
.import-step-divider { border-top: 1px dashed #ddd; margin: 16px 0; }

/* Download btn */
.btn-download-template {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 7px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;
    background: #e8f5e9; color: #2e7d32; border: 1.5px solid #a5d6a7;
    text-decoration: none; transition: background .15s;
}
.btn-download-template:hover { background: #c8e6c9; }

/* Dropzone */
.import-dropzone {
    border: 2px dashed #c5cae9; border-radius: 10px;
    padding: 28px 20px; text-align: center; cursor: pointer;
    background: #f8f9ff; transition: border-color .18s, background .18s;
}
.import-dropzone:hover, .import-dropzone.drag-over {
    border-color: var(--primary); background: #eff3ff;
}
.import-dropzone-text  { font-size: 13px; color: #555; margin: 8px 0 4px; }
.import-dropzone-hint  { font-size: 12px; color: #aaa; }
.import-dropzone-filename {
    margin-top: 10px; font-size: 12.5px; font-weight: 600;
    color: var(--primary); min-height: 16px;
}

/* Buttons */
.btn-import-cancel {
    padding: 8px 18px; border-radius: 8px; border: 1.5px solid #ddd;
    background: #f5f5f5; color: #555; font-size: 13px; font-weight: 600; cursor: pointer;
    transition: background .15s;
}
.btn-import-cancel:hover { background: #e8e8e8; }
.btn-import-submit {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 20px; border-radius: 8px; border: none;
    background: var(--primary); color: #fff;
    font-size: 13px; font-weight: 600; cursor: pointer;
    transition: opacity .15s;
}
.btn-import-submit:disabled { opacity: .45; cursor: not-allowed; }
.btn-import-submit:not(:disabled):hover { opacity: .88; }
</style>

<!-- ══════════════════════════════════════════  SCRIPT  ═════════════════════════════════════════ -->
<script>
function closeImportModal(modul) {
    document.getElementById('modalImport_' + modul).classList.remove('open');
}

function onFileSelected(input, modul) {
    const file = input.files[0];
    const nameEl = document.getElementById('filename_' + modul);
    const btn    = document.getElementById('btnSubmit_' + modul);
    if (file) {
        nameEl.textContent = '✓ ' + file.name;
        btn.disabled = false;
    } else {
        nameEl.textContent = '';
        btn.disabled = true;
    }
}

// Drag & Drop support
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.import-dropzone').forEach(function(zone) {
        const modul = zone.id.replace('dropzone_', '');
        const input = document.getElementById('fileInput_' + modul);

        zone.addEventListener('dragover', function(e) {
            e.preventDefault(); zone.classList.add('drag-over');
        });
        zone.addEventListener('dragleave', function() {
            zone.classList.remove('drag-over');
        });
        zone.addEventListener('drop', function(e) {
            e.preventDefault(); zone.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (file) {
                // inject ke input file
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                onFileSelected(input, modul);
            }
        });
    });

    // Tutup modal klik backdrop
    document.querySelectorAll('.import-modal-overlay').forEach(function(overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) overlay.classList.remove('open');
        });
    });
});
</script>