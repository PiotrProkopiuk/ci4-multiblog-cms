<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php if (session('flash_error')): ?>
    <div class="alert alert-danger rounded-3 mb-3"><?= esc(session('flash_error')) ?></div>
<?php endif; ?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('admin/blogs') ?>">← Powrót</a>
    <h1 class="h2 mb-0">Nowy blog</h1>
</div>

<form method="post" action="<?= site_url('admin/blogs/store') ?>">
<div class="row g-4">

    <div class="col-lg-7">
        <div class="cms-panel mb-4">
            <h2 class="h5 fw-bold mb-3">📝 Podstawowe informacje</h2>

            <label class="form-label fw-semibold small">Nazwa bloga <span class="text-danger">*</span></label>
            <input class="form-control mb-3" type="text" name="name" id="nameInput"
                   value="<?= esc(old('name')) ?>" required placeholder="np. Mój Pies">

            <label class="form-label fw-semibold small">Slug (URL) <span class="text-danger">*</span></label>
            <div class="input-group mb-1">
                <span class="input-group-text text-muted" style="font-size:.85rem">/</span>
                <input class="form-control" type="text" name="slug" id="slugInput"
                       value="<?= esc(old('slug')) ?>" required
                       placeholder="moj-pies" pattern="[a-z0-9\-]+"
                       style="font-family:monospace">
            </div>
            <p class="text-muted small mb-3">Tylko małe litery, cyfry i myślniki. Nie można zmienić po zapisaniu.</p>

            <label class="form-label fw-semibold small">Opis</label>
            <textarea class="form-control mb-3" name="description" rows="2"
                      placeholder="Krótki opis bloga..."><?= esc(old('description')) ?></textarea>

            <label class="form-label fw-semibold small">Hasło / tagline</label>
            <input class="form-control mb-3" type="text" name="tagline"
                   value="<?= esc(old('tagline')) ?>" placeholder="np. Wszystko o życiu z psem">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Domena (opcjonalna)</label>
                    <input class="form-control" type="text" name="domain"
                           value="<?= esc(old('domain')) ?>" placeholder="mojpies.pl">
                    <div class="form-text">Możesz dodać lub zmienić domenę później.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Domyślny język</label>
                    <select class="form-select" name="default_language">
                        <option value="pl" selected>🇵🇱 Polski</option>
                        <option value="en">🇬🇧 English</option>
                        <option value="de">🇩🇪 Deutsch</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="cms-panel mb-4">
            <h2 class="h5 fw-bold mb-3">🎨 Wygląd</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Kolor akcentu</label>
                    <div class="d-flex gap-2 align-items-center">
                        <input type="color" class="form-control form-control-color" name="accent_color"
                               value="#3b82f6" id="colorPicker" style="width:54px;height:38px;padding:2px">
                        <input type="text" class="form-control" id="colorText" value="#3b82f6" style="font-family:monospace">
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end">
            <a class="btn btn-outline-secondary me-2" href="<?= site_url('admin/blogs') ?>">Anuluj</a>
            <button class="btn btn-primary px-4" type="submit">✅ Utwórz blog</button>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="cms-panel">
            <h2 class="h5 fw-bold mb-3">📐 Szablon strony głównej</h2>
            <div class="d-flex flex-column gap-3">
            <?php foreach ($layouts as $key => $layout): ?>
                <label class="d-block" style="cursor:pointer">
                    <input type="radio" name="homepage_layout" value="<?= esc($key) ?>"
                        <?= $key === 'variant_a' ? 'checked' : '' ?>
                        class="d-none layout-radio" data-preview="<?= esc($layout['preview']) ?>">
                    <div class="layout-card p-3 rounded-3 border <?= $key === 'variant_a' ? 'border-primary bg-primary bg-opacity-10' : '' ?>">
                        <div class="d-flex align-items-start gap-2">
                            <div class="layout-check rounded-circle border border-2 flex-shrink-0 mt-1"
                                 style="width:18px;height:18px;background:<?= $key === 'variant_a' ? 'var(--bs-primary)' : 'white' ?>"></div>
                            <div>
                                <div class="fw-semibold small"><?= esc($layout['label']) ?></div>
                                <div class="text-muted" style="font-size:.78rem"><?= esc($layout['description']) ?></div>
                            </div>
                        </div>
                    </div>
                </label>
            <?php endforeach; ?>
            </div>
            <div class="mt-3">
                <p class="text-muted small mb-2">👀 Podgląd:</p>
                <iframe id="layout-preview" src="<?= esc($layouts['variant_a']['preview']) ?>"
                    style="width:100%;height:300px;border:1px solid #e2e8f0;border-radius:12px" loading="lazy"></iframe>
            </div>
        </div>
    </div>

</div>
</form>

<script>
// Auto-generate slug from name
const nameInput = document.getElementById('nameInput');
const slugInput = document.getElementById('slugInput');
nameInput.addEventListener('input', () => {
    if (! slugInput.dataset.manual) {
        slugInput.value = nameInput.value
            .toLowerCase().normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }
});
slugInput.addEventListener('input', () => { slugInput.dataset.manual = '1'; });

// Color sync
const cp = document.getElementById('colorPicker');
const ct = document.getElementById('colorText');
cp.addEventListener('input', () => ct.value = cp.value);
ct.addEventListener('input', () => { if (/^#[0-9a-f]{6}$/i.test(ct.value)) cp.value = ct.value; });

// Layout selector
document.querySelectorAll('.layout-radio').forEach(r => {
    r.addEventListener('change', () => {
        document.querySelectorAll('.layout-card').forEach(c => {
            c.classList.remove('border-primary','bg-primary','bg-opacity-10');
            c.querySelector('.layout-check').style.background = 'white';
        });
        r.nextElementSibling.classList.add('border-primary','bg-primary','bg-opacity-10');
        r.nextElementSibling.querySelector('.layout-check').style.background = 'var(--bs-primary)';
        document.getElementById('layout-preview').src = r.dataset.preview;
    });
});
</script>
<?= $this->endSection() ?>
