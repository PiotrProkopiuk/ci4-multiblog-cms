<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="h2 mb-1">Translations</h1>
        <p class="text-muted mb-0"><?= esc($blog['name']) ?> · <?= esc(strtoupper($language)) ?></p>
    </div>
    <div class="btn-group">
        <a class="btn btn-outline-primary" href="<?= site_url('admin/translations?language=pl') ?>">PL</a>
        <a class="btn btn-outline-primary" href="<?= site_url('admin/translations?language=en') ?>">EN</a>
        <a class="btn btn-outline-primary" href="<?= site_url('admin/translations?language=de') ?>">DE</a>
    </div>
</div>
<form method="post" action="<?= site_url('admin/translations') ?>" class="cms-panel">
    <input type="hidden" name="language" value="<?= esc($language) ?>">
    <?php foreach ($rows as $row): ?>
        <div class="mb-3">
            <label class="form-label fw-semibold"><?= esc($row['translation_key']) ?></label>
            <textarea class="form-control" name="translations[<?= esc($row['id']) ?>]" rows="2"><?= esc($row['value']) ?></textarea>
        </div>
    <?php endforeach; ?>
    <button class="btn btn-primary" type="submit">Save translations</button>
</form>
<?= $this->endSection() ?>