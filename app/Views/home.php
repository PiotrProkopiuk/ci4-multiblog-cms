<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php
$tr = static fn($key, $fallback) => $t[$key] ?? $fallback;
$layout = $theme['layout'] ?? 'variant_a';
$isAdminUser = (session('is_logged_in') && session('role') === 'admin');
?>

<?php if ($layout === 'variant_b'): ?>
<?php /* ── VARIANT B: Split hero ── */ ?>
<section class="row g-0 mb-5 rounded-4 overflow-hidden" style="min-height:400px">
    <div class="col-lg-6 d-flex align-items-center p-5">
        <div>
            <div class="eyebrow mb-3" style="color:var(--accent)"><?= esc($tr('hero.eyebrow', 'Fresh stories')) ?></div>
            <h1 class="display-5 fw-bold mb-3"><?= esc($tr('hero.title', $blog['name'])) ?></h1>
            <?php if (! empty($blog['tagline'])): ?>
                <p class="lead mb-2 text-muted"><?= esc($blog['tagline']) ?></p>
            <?php endif; ?>
            <p class="mb-4 text-muted"><?= esc($tr('hero.subtitle', 'A curated blog with practical inspiration and calm reading.')) ?></p>
            <a class="btn btn-primary btn-lg rounded-pill px-4" href="#latest"><?= esc($tr('hero.cta', 'Read latest posts')) ?></a>
        </div>
    </div>
    <div class="col-lg-6 rounded-end-4" style="background:linear-gradient(135deg,rgba(20,30,45,.25),rgba(20,30,45,.05)),url('<?= esc($theme['image']) ?>') center/cover no-repeat;min-height:320px"></div>
</section>
<section class="row g-4 mb-5">
    <div class="col-md-4"><div class="feature-card h-100 p-4"><h2 class="h5"><?= esc($tr('feature.one.title', 'Practical guides')) ?></h2><p class="text-muted mb-0"><?= esc($tr('feature.one.text', 'Clear ideas you can use.')) ?></p></div></div>
    <div class="col-md-4"><div class="feature-card h-100 p-4"><h2 class="h5"><?= esc($tr('feature.two.title', 'Seasonal inspiration')) ?></h2><p class="text-muted mb-0"><?= esc($tr('feature.two.text', 'Fresh topics for everyday life.')) ?></p></div></div>
    <div class="col-md-4"><div class="feature-card h-100 p-4"><h2 class="h5"><?= esc($tr('feature.three.title', 'Simple language')) ?></h2><p class="text-muted mb-0"><?= esc($tr('feature.three.text', 'Written for people, not algorithms.')) ?></p></div></div>
</section>

<?php elseif ($layout === 'variant_c'): ?>
<?php /* ── VARIANT C: Editorial / Magazine ── */ ?>
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <section class="hero d-flex align-items-end p-4 p-lg-5" style="min-height:340px;border-radius:24px">
            <div class="hero-copy">
                <div class="eyebrow mb-2"><?= esc($tr('hero.eyebrow', "Editor's Pick")) ?></div>
                <h1 class="display-5 fw-bold mb-2"><?= esc($tr('hero.title', $blog['name'])) ?></h1>
                <?php if (! empty($blog['tagline'])): ?>
                    <p class="mb-3" style="color:rgba(255,255,255,.85)"><?= esc($blog['tagline']) ?></p>
                <?php endif; ?>
                <a class="btn btn-primary rounded-pill px-4" href="#latest"><?= esc($tr('hero.cta', 'Latest stories')) ?></a>
            </div>
        </section>
    </div>
    <div class="col-lg-4">
        <div class="cms-panel h-100 p-4 d-flex flex-column gap-3">
            <div class="fw-bold text-uppercase small" style="letter-spacing:.1em;color:var(--accent)">O blogu</div>
            <p class="text-muted mb-0"><?= esc($blog['description'] ?? $tr('hero.subtitle', 'Practical inspiration and calm reading.')) ?></p>
            <hr class="my-1">
            <div class="fw-bold small mb-1">Języki</div>
            <div class="d-flex gap-2">
                <?php foreach (['pl','en','de'] as $lang): ?>
                    <a class="btn btn-sm <?= $language === $lang ? 'btn-primary' : 'btn-outline-secondary' ?>" href="<?= site_url($lang) ?>"><?= strtoupper($lang) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<?php /* ── VARIANT A: Fullscreen hero (default) ── */ ?>
<section class="hero d-flex align-items-end p-4 p-lg-5 mb-5">
    <div class="hero-copy">
        <div class="eyebrow mb-3"><?= esc($tr('hero.eyebrow', 'Fresh stories')) ?></div>
        <h1 class="display-4 fw-bold mb-3"><?= esc($tr('hero.title', $blog['name'])) ?></h1>
        <?php if (! empty($blog['tagline'])): ?>
            <p class="lead mb-2" style="color:rgba(255,255,255,.9)"><?= esc($blog['tagline']) ?></p>
        <?php endif; ?>
        <p class="lead mb-4"><?= esc($tr('hero.subtitle', 'A light, curated blog with practical inspiration and calm reading.')) ?></p>
        <a class="btn btn-primary btn-lg rounded-pill px-4" href="#latest"><?= esc($tr('hero.cta', 'Read latest posts')) ?></a>
    </div>
</section>
<section class="row g-4 mb-5">
    <div class="col-md-4"><div class="feature-card h-100 p-4"><h2 class="h5"><?= esc($tr('feature.one.title', 'Practical guides')) ?></h2><p class="text-muted mb-0"><?= esc($tr('feature.one.text', 'Clear ideas you can use without digging through noise.')) ?></p></div></div>
    <div class="col-md-4"><div class="feature-card h-100 p-4"><h2 class="h5"><?= esc($tr('feature.two.title', 'Seasonal inspiration')) ?></h2><p class="text-muted mb-0"><?= esc($tr('feature.two.text', 'Fresh topics matched to everyday life and current needs.')) ?></p></div></div>
    <div class="col-md-4"><div class="feature-card h-100 p-4"><h2 class="h5"><?= esc($tr('feature.three.title', 'Simple language')) ?></h2><p class="text-muted mb-0"><?= esc($tr('feature.three.text', 'Readable articles written for people, not algorithms.')) ?></p></div></div>
</section>
<?php endif; ?>

<div id="latest" class="d-flex justify-content-between align-items-end gap-3 mb-3">
    <div>
        <p class="eyebrow text-dark mb-2"><?= esc(strtoupper($language)) ?></p>
        <h2 class="fw-bold"><?= esc($tr('posts.heading', 'Latest posts')) ?></h2>
    </div>
</div>

<?php if ($posts): ?>
    <?php if ($layout === 'variant_c'): ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="row g-4">
            <?php foreach (array_slice($posts, 0, 6) as $i => $post): ?>
                <div class="<?= $i === 0 ? 'col-12' : 'col-md-6' ?>">
                    <article class="post-card h-100 p-4">
                        <?php if (! empty($post['featured_image_url'])): ?>
                            <img src="<?= esc($post['featured_image_url']) ?>" alt="<?= esc($post['title']) ?>"
                                class="img-fluid rounded-4 mb-3"
                                style="height:<?= $i === 0 ? '240px' : '150px' ?>;width:100%;object-fit:cover">
                        <?php endif; ?>
                        <p class="small text-uppercase text-muted fw-bold mb-2"><?= esc($post['language']) ?> · <?= esc(date('d.m.Y', strtotime($post['updated_at']))) ?></p>
                        <h3 class="<?= $i === 0 ? 'h3' : 'h5' ?>">
                            <a href="<?= site_url($language . '/' . ($post['category_slug'] ?: '') . '/' . $post['slug']) ?>"><?= esc($post['title']) ?></a>
                            <?php if ($isAdminUser): ?>
                                <a href="<?= site_url('admin/posts/' . $post['id'] . '/edit') ?>" class="ms-2 small text-muted" title="Edytuj" target="_blank" rel="noopener">✎</a>
                            <?php endif; ?>
                        </h3>
                        <p class="text-muted"><?= character_limiter(strip_tags($post['content']), $i === 0 ? 220 : 120) ?></p>
                        <a class="fw-semibold" href="<?= site_url($language . '/posts/' . $post['id']) ?>"><?= esc($tr('posts.read_more', 'Read more')) ?></a>
                    </article>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="cms-panel p-4">
                <div class="fw-bold text-uppercase small mb-3" style="letter-spacing:.1em;color:var(--accent)">Więcej artykułów</div>
                <div class="d-flex flex-column gap-3">
                <?php foreach (array_slice($posts, 6) as $post): ?>
                    <div class="border-bottom pb-2">
                        <p class="small text-muted mb-1"><?= esc(date('d.m.Y', strtotime($post['updated_at']))) ?></p>
                        <a class="fw-semibold small" href="<?= site_url($language . '/posts/' . $post['id']) ?>"><?= esc($post['title']) ?></a>
                        <?php if ($isAdminUser): ?>
                            <a href="<?= site_url('admin/posts/' . $post['id'] . '/edit') ?>" class="ms-2 small text-muted" title="Edytuj" target="_blank" rel="noopener">✎</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <?php if (count($posts) <= 6): ?><p class="text-muted small mb-0">Więcej wkrótce.</p><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="row g-4">
    <?php foreach ($posts as $post): ?>
        <div class="col-md-6 col-lg-4">
            <article class="post-card h-100 p-4">
                <?php if (! empty($post['featured_image_url'])): ?>
                    <img src="<?= esc($post['featured_image_url']) ?>" alt="<?= esc($post['featured_image_alt'] ?? $post['title']) ?>" class="img-fluid rounded-4 mb-3" style="height:180px;width:100%;object-fit:cover">
                <?php endif; ?>
                <p class="small text-uppercase text-muted fw-bold mb-2"><?= esc($post['language']) ?> · <?= esc(date('d.m.Y', strtotime($post['updated_at']))) ?></p>
                <h3 class="h4">
                    <a href="<?= site_url($language . '/' . $post['category_slug'] . '/' . $post['slug']) ?>"><?= esc($post['title']) ?></a>
                    <?php if ($isAdminUser): ?>
                        <a href="<?= site_url('admin/posts/' . $post['id'] . '/edit') ?>" class="ms-2 small text-muted" title="Edytuj" target="_blank" rel="noopener">✎</a>
                    <?php endif; ?>
                </h3>
                <p class="text-muted"><?= character_limiter(strip_tags($post['content']), 170) ?></p>
                <a class="fw-semibold" href="<?= site_url($language . '/' . $post['category_slug'] . '/' . $post['slug']) ?>"><?= esc($tr('posts.read_more', 'Read more')) ?></a>
            </article>
        </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>
<?php else: ?>
    <div class="post-card p-4"><?= esc($tr('posts.empty', 'No published posts for this language yet.')) ?></div>
<?php endif; ?>
<?= $this->endSection() ?>
