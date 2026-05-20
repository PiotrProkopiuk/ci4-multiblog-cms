<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php if (session('flash_success')): ?>
    <div class="alert alert-success rounded-3 mb-3"><?= esc(session('flash_success')) ?></div>
<?php endif; ?>
<?php if (session('flash_error')): ?>
    <div class="alert alert-danger rounded-3 mb-3"><?= esc(session('flash_error')) ?></div>
<?php endif; ?>
<div class="d-flex justify-content-between align-items-center gap-3 mb-4">
    <h1 class="h2 mb-0">Posts</h1>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-warning" href="<?= site_url('admin/posts/review') ?>">
            Review queue
            <?php $pending = array_filter($posts, static fn($p) => $p['status'] === 'review_pending'); ?>
            <?php if ($pending): ?>
                <span class="badge bg-warning text-dark ms-1"><?= count($pending) ?></span>
            <?php endif; ?>
        </a>
        <a class="btn btn-primary" href="<?= site_url('admin/posts/new') ?>">New post</a>
    </div>
</div>
<?php if ($posts): ?>
    <div class="cms-panel p-0 overflow-hidden">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th class="ps-4">Title</th>
                <th>Status</th>
                <th>Language</th>
                <th>Updated</th>
                <th class="pe-4">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($posts as $post): ?>
                <?php $s = $statuses[$post['status']] ?? ['label' => $post['status'], 'color' => 'secondary']; ?>
                <tr>
                    <td class="ps-4">
                        <span class="fw-semibold"><?= esc($post['title']) ?></span>
                        <?php if (! empty($post['reject_reason'])): ?>
                            <br><small class="text-danger">⚠ <?= esc($post['reject_reason']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge rounded-pill bg-<?= esc($s['color']) ?> text-<?= $s['color'] === 'warning' ? 'dark' : 'white' ?>">
                            <?= esc($s['label']) ?>
                        </span>
                    </td>
                    <td><span class="badge bg-light text-dark border"><?= esc(strtoupper($post['language'])) ?></span></td>
                    <td class="text-muted small"><?= esc(date('d.m.Y H:i', strtotime($post['updated_at']))) ?></td>
                    <td class="pe-4">
                        <div class="d-flex flex-wrap gap-1">
                            <?php if (in_array($post['status'], ['draft', 'rejected'], true)): ?>
                                <form method="post" action="<?= site_url('admin/posts/' . $post['id'] . '/submit-review') ?>">
                                    <button class="btn btn-sm btn-warning text-dark" type="submit" title="Wyślij do recenzji">
                                        ↑ Do recenzji
                                    </button>
                                </form>
                            <?php endif; ?>
                            <?php if ($post['status'] === 'review_pending'): ?>
                                <a class="btn btn-sm btn-info text-white" href="<?= site_url('admin/posts/' . $post['id'] . '/review') ?>">Recenzja</a>
                            <?php endif; ?>
                            <a class="btn btn-sm btn-outline-primary" href="<?= site_url('admin/posts/' . $post['id'] . '/edit') ?>">Edit</a>
                            <form method="post" action="<?= site_url('admin/posts/' . $post['id'] . '/delete') ?>" onsubmit="return confirm('Delete?')">
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="cms-panel">No posts yet.</div>
<?php endif; ?>
<?= $this->endSection() ?>
