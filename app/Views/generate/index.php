<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php if (session('flash_success')): ?>
    <div class="alert alert-success rounded-3 mb-3"><?= esc(session('flash_success')) ?></div>
<?php endif; ?>
<?php if (session('flash_error')): ?>
    <div class="alert alert-danger rounded-3 mb-3"><?= esc(session('flash_error')) ?></div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="h2 mb-1">AI Article Generator</h1>
        <p class="text-muted mb-0">Wklej słowa kluczowe → GPT-4o grupuje je w tematy → generuje gotowe artykuły do recenzji.</p>
    </div>
    <?php if ($clusters && array_filter($clusters, static fn($c) => ! $c['post_id'])): ?>
        <form method="post" action="<?= site_url('admin/generate/batch') ?>">
            <button class="btn btn-primary" type="submit" onclick="return confirm('Wygenerować artykuły dla wszystkich klastrów bez artykułu? Może to potrwać kilka minut.')">
                ⚡ Generuj wszystkie
            </button>
        </form>
    <?php endif; ?>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="cms-panel">
            <h2 class="h5 fw-bold mb-3">1. Wklej słowa kluczowe</h2>
            <form method="post" action="<?= site_url('admin/generate/cluster') ?>">
                <label class="form-label fw-semibold small">Słowa kluczowe <span class="text-muted fw-normal">(jedno per linia lub oddzielone przecinkami)</span></label>
                <textarea class="form-control mb-3" name="keywords" rows="10" required
                    placeholder="jak pielęgnować kota&#10;karma dla kociąt&#10;szczepienia kotów&#10;kot a alergia&#10;zabawki dla kota&#10;jak wybrać żwirek&#10;choroby kotów objawy"></textarea>

                <label class="form-label fw-semibold small">Język artykułów</label>
                <select class="form-select mb-3" name="language">
                    <option value="pl">🇵🇱 Polski</option>
                    <option value="en">🇬🇧 English</option>
                    <option value="de">🇩🇪 Deutsch</option>
                </select>

                <button class="btn btn-outline-primary w-100" type="submit">
                    🧠 Grupuj słowa kluczowe
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-7">
        <h2 class="h5 fw-bold mb-3">2. Klastry tematyczne</h2>

        <?php if (! $clusters): ?>
            <div class="cms-panel text-center py-5 text-muted">
                <div style="font-size:3rem">🔑</div>
                <p class="mt-3 mb-0">Wklej słowa kluczowe i kliknij „Grupuj" — AI podzieli je na tematy artykułów.</p>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
            <?php foreach ($clusters as $cluster): ?>
                <div class="cms-panel <?= $cluster['post_id'] ? 'opacity-75' : '' ?>">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h3 class="h6 fw-bold mb-0"><?= esc($cluster['name']) ?></h3>
                                <span class="badge bg-light text-dark border"><?= esc(strtoupper($cluster['language'])) ?></span>
                                <?php if ($cluster['post_id']): ?>
                                    <span class="badge bg-success">✓ Wygenerowano</span>
                                <?php endif; ?>
                            </div>
                            <?php if ($cluster['description']): ?>
                                <p class="text-muted small mb-2"><?= esc($cluster['description']) ?></p>
                            <?php endif; ?>
                            <div class="d-flex flex-wrap gap-1">
                                <?php foreach ($cluster['keywords_array'] as $kw): ?>
                                    <span class="badge rounded-pill" style="background:#f0f4ff;color:#3b5bf6;font-weight:500;font-size:.75rem"><?= esc($kw) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <?php if ($cluster['post_id']): ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('admin/posts/' . $cluster['post_id'] . '/review') ?>">
                                    Recenzja →
                                </a>
                            <?php else: ?>
                                <form method="post" action="<?= site_url('admin/generate/' . $cluster['id']) ?>">
                                    <button class="btn btn-sm btn-primary" type="submit">
                                        ✨ Generuj artykuł
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.opacity-75 { opacity: .75 }
</style>
<?= $this->endSection() ?>
