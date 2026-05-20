<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php if (session('flash_success')): ?>
    <div class="alert alert-success rounded-3 mb-3"><?= esc(session('flash_success')) ?></div>
<?php endif; ?>
<?php if (session('flash_error')): ?>
    <div class="alert alert-danger rounded-3 mb-3"><?= esc(session('flash_error')) ?></div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 mb-1">Konfiguracja blogów</h1>
        <p class="text-muted mb-0">Zarządzaj ustawieniami, szablonami i domenami każdego bloga.</p>
    </div>
    <a class="btn btn-primary" href="<?= site_url('admin/blogs/create') ?>">+ Nowy blog</a>
</div>

<div class="d-flex flex-column gap-3">
<?php
$layoutLabels = [
    'variant_a' => 'A — Fullscreen hero',
    'variant_b' => 'B — Split hero',
    'variant_c' => 'C — Editorial',
];
$themeMap = [
    'tailo'       => ['bg' => '#f97316', 'emoji' => '🐾'],
    'gardenhaven' => ['bg' => '#2f8f46', 'emoji' => '🌿'],
    'zenvitality' => ['bg' => '#be3b74', 'emoji' => '✨'],
    'main'        => ['bg' => '#1455d9', 'emoji' => '📝'],
];
?>
<?php foreach ($blogs as $blog): ?>
<?php $tc = $themeMap[$blog['slug']] ?? ['bg' => '#64748b', 'emoji' => '🌐']; ?>
<div class="cms-panel">
    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
        <div class="flex-grow-1">
            <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                <span style="font-size:1.3rem"><?= $tc['emoji'] ?></span>
                <h2 class="h5 fw-bold mb-0"><?= esc($blog['name']) ?></h2>
                <span class="badge rounded-pill text-white" style="background:<?= $tc['bg'] ?>"><?= esc($blog['slug']) ?></span>
                <?php if (! empty($blog['domain'])): ?>
                    <span class="badge bg-light text-dark border">🌐 <?= esc($blog['domain']) ?></span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">⚠ Brak domeny</span>
                <?php endif; ?>
            </div>
            <?php if (! empty($blog['description'])): ?>
                <p class="text-muted small mb-2 mb-0"><?= esc($blog['description']) ?></p>
            <?php endif; ?>
            <div class="d-flex flex-wrap gap-3 mt-1" style="font-size:.8rem;color:#64748b">
                <span>📐 <?= esc($layoutLabels[$blog['homepage_layout'] ?? 'variant_a'] ?? 'A') ?></span>
                <span>🌍 <?= strtoupper(esc($blog['default_language'])) ?></span>
                <?php if (! empty($blog['tagline'])): ?>
                    <span>💬 „<?= esc(mb_strimwidth($blog['tagline'], 0, 60, '…')) ?>"</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="d-flex gap-2 flex-shrink-0">
            <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('?blog=' . $blog['slug']) ?>" target="_blank">👁 Podgląd</a>
            <a class="btn btn-sm btn-outline-primary" href="<?= site_url('admin/blogs/' . $blog['id'] . '/edit') ?>">✏ Edytuj</a>
            <?php if (! in_array($blog['slug'], ['main','tailo','gardenhaven','zenvitality'])): ?>
            <form method="post" action="<?= site_url('admin/blogs/' . $blog['id'] . '/delete') ?>" class="d-inline"
                  onsubmit="return confirm('Usunąć blog „<?= esc($blog['name']) ?>"? Tej operacji nie można cofnąć.')">
                <button class="btn btn-sm btn-outline-danger" type="submit">🗑</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>
<?= $this->endSection() ?>
