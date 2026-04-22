<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php $tr = static fn($key, $fallback) => $t[$key] ?? $fallback; ?>
<section class="hero d-flex align-items-end p-4 p-lg-5 mb-5">
    <div class="hero-copy">
        <div class="eyebrow mb-3"><?= esc($tr('hero.eyebrow', 'Fresh stories')) ?></div>
        <h1 class="display-4 fw-bold mb-3"><?= esc($tr('hero.title', $blog['name'])) ?></h1>
        <p class="lead mb-4"><?= esc($tr('hero.subtitle', 'A light, curated blog with practical inspiration and calm reading.')) ?></p>
        <a class="btn btn-primary btn-lg rounded-pill px-4" href="#latest"><?= esc($tr('hero.cta', 'Read latest posts')) ?></a>
    </div>
</section>
<section class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="feature-card h-100 p-4">
            <h2 class="h5"><?= esc($tr('feature.one.title', 'Practical guides')) ?></h2>
            <p class="text-muted mb-0"><?= esc($tr('feature.one.text', 'Clear ideas you can use without digging through noise.')) ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="feature-card h-100 p-4">
            <h2 class="h5"><?= esc($tr('feature.two.title', 'Seasonal inspiration')) ?></h2>
            <p class="text-muted mb-0"><?= esc($tr('feature.two.text', 'Fresh topics matched to everyday life and current needs.')) ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="feature-card h-100 p-4">
            <h2 class="h5"><?= esc($tr('feature.three.title', 'Simple language')) ?></h2>
            <p class="text-muted mb-0"><?= esc($tr('feature.three.text', 'Readable articles written for people, not algorithms.')) ?></p>
        </div>
    </div>
</section>
<div id="latest" class="d-flex justify-content-between align-items-end gap-3 mb-3">
    <div>
        <p class="eyebrow text-dark mb-2"><?= esc(strtoupper($language)) ?></p>
        <h2 class="fw-bold"><?= esc($tr('posts.heading', 'Latest posts')) ?></h2>
    </div>
</div>
<?php if ($posts): ?>
    <div class="row g-4">
    <?php foreach ($posts as $post): ?>
        <div class="col-md-6 col-lg-4">
            <article class="post-card h-100 p-4">
                <?php if (! empty($post['featured_image_url'])): ?>
                    <img src="<?= esc($post['featured_image_url']) ?>" alt="<?= esc($post['featured_image_alt'] ?? $post['title']) ?>" class="img-fluid rounded-4 mb-3" style="height:180px;width:100%;object-fit:cover">
                <?php endif; ?>
                <p class="small text-uppercase text-muted fw-bold mb-2"><?= esc($post['language']) ?> · <?= esc(date('d.m.Y', strtotime($post['updated_at']))) ?></p>
                <h3 class="h4"><a href="<?= site_url($language . '/posts/' . $post['id']) ?>"><?= esc($post['title']) ?></a></h3>
                <p class="text-muted"><?= character_limiter(strip_tags($post['content']), 170) ?></p>
                <a class="fw-semibold" href="<?= site_url($language . '/posts/' . $post['id']) ?>"><?= esc($tr('posts.read_more', 'Read more')) ?></a>
            </article>
        </div>
    <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="post-card p-4"><?= esc($tr('posts.empty', 'No published posts for this language yet.')) ?></div>
<?php endif; ?>
<?= $this->endSection() ?>