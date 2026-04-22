<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h1 class="h2 mb-4">Dashboard</h1>
<div class="cms-panel">
    <p><strong>Blog:</strong> <?= esc($blog['name']) ?></p>
    <p><strong>Published:</strong> <?= esc($publishedCount) ?></p>
    <p><strong>Drafts:</strong> <?= esc($draftCount) ?></p>
    <a class="btn btn-primary" href="<?= site_url('admin/posts') ?>">Manage posts</a>
    <a class="btn btn-outline-primary" href="<?= site_url('admin/translations') ?>">Manage translations</a>
</div>
<?= $this->endSection() ?>