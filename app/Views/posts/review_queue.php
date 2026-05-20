<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php if (session('flash_success')): ?>
    <div class="alert alert-success rounded-3 mb-3"><?= esc(session('flash_success')) ?></div>
<?php endif; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 mb-1">Review queue</h1>
        <p class="text-muted mb-0">Wpisy czekające na Twoją akceptację przed publikacją.</p>
    </div>
    <a class="btn btn-outline-secondary" href="<?= site_url('admin/posts') ?>">← Wszystkie wpisy</a>
</div>

<?php if ($posts): ?>
    <div class="cms-panel p-0 overflow-hidden">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th class="ps-4">Tytuł</th>
                <th>Język</th>
                <th>Oczekuje od</th>
                <th>Zdjęcie</th>
                <th class="pe-4">Akcja</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td class="ps-4 fw-semibold"><?= esc($post['title']) ?></td>
                    <td><span class="badge bg-light text-dark border"><?= esc(strtoupper($post['language'])) ?></span></td>
                    <td class="text-muted small"><?= esc(date('d.m.Y H:i', strtotime($post['updated_at']))) ?></td>
                    <td>
                        <?php if (! empty($post['featured_image_url'])): ?>
                            <img src="<?= esc($post['featured_image_url']) ?>" alt="" class="rounded-3" style="height:40px;width:64px;object-fit:cover">
                        <?php else: ?>
                            <span class="text-muted small">brak</span>
                        <?php endif; ?>
                    </td>
                    <td class="pe-4">
                        <a class="btn btn-sm btn-primary" href="<?= site_url('admin/posts/' . $post['id'] . '/review') ?>">
                            Recenzuj →
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="cms-panel text-center py-5">
        <div style="font-size:3rem">✅</div>
        <h2 class="h4 mt-3 mb-1">Kolejka pusta</h2>
        <p class="text-muted mb-0">Żadnych wpisów do recenzji. Dobra robota!</p>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
