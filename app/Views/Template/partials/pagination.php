<?php if (($pagination['total_pages'] ?? 1) > 1): ?>
    <?php
        $query = service('request')->getGet();
        $currentPage = (int) ($pagination['page'] ?? 1);
        $totalPages = (int) ($pagination['total_pages'] ?? 1);
    ?>
    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; padding:16px 20px; border-top:1px solid var(--border); flex-wrap:wrap;">
        <span style="font-size:0.85rem; color:var(--text-light);">
            Total <?= esc($pagination['total'] ?? 0) ?> data, halaman <?= $currentPage ?> dari <?= $totalPages ?>
        </span>
        <div style="display:flex; gap:6px; flex-wrap:wrap;">
            <?php if ($currentPage > 1): ?>
                <?php $query['page'] = $currentPage - 1; ?>
                <a class="btn btn-secondary btn-sm" href="<?= current_url() . '?' . http_build_query($query) ?>">Sebelumnya</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i === 1 || $i === $totalPages || abs($i - $currentPage) <= 1): ?>
                    <?php $query['page'] = $i; ?>
                    <a class="btn btn-sm <?= $i === $currentPage ? 'btn-primary' : 'btn-secondary' ?>" href="<?= current_url() . '?' . http_build_query($query) ?>"><?= $i ?></a>
                <?php elseif (abs($i - $currentPage) === 2): ?>
                    <span style="padding:5px 2px; color:var(--text-light);">...</span>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <?php $query['page'] = $currentPage + 1; ?>
                <a class="btn btn-secondary btn-sm" href="<?= current_url() . '?' . http_build_query($query) ?>">Berikutnya</a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
