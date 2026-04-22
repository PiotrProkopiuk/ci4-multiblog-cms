<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<article class="post-card p-4 p-lg-5">
    <?php if (! empty($post['featured_image_url'])): ?>
        <img src="<?= esc($post['featured_image_url']) ?>" alt="<?= esc($post['featured_image_alt'] ?? $post['title']) ?>" class="img-fluid rounded-4 mb-4" style="max-height:420px;width:100%;object-fit:cover">
    <?php endif; ?>
    <h1><?= esc($post['title']) ?></h1>
    <p class="muted"><?= esc($post['language']) ?> · <?= esc($post['updated_at']) ?></p>
    <?php if (! empty($post['featured_image_source'])): ?>
        <p class="small text-muted">Image: <?= esc($post['featured_image_source']) ?><?= ! empty($post['featured_image_author']) ? ' · ' . esc($post['featured_image_author']) : '' ?></p>
    <?php endif; ?>
    <div><?= $post['content'] ?></div>
</article>
<?= $this->endSection() ?>