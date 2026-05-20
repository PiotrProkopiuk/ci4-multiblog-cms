<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <a class="small text-muted" href="<?= site_url('admin/posts/review') ?>">← Review queue</a>
        <h1 class="h2 mt-1 mb-0">Recenzja wpisu</h1>
    </div>
    <span class="badge rounded-pill bg-warning text-dark fs-6 mt-2">Do recenzji</span>
</div>

<?php if (! empty($errors)): ?>
    <div class="alert alert-danger rounded-3 mb-4">
        <?php foreach ($errors as $e): ?><div><?= esc($e) ?></div><?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="cms-panel mb-4">
            <p class="text-muted small fw-bold text-uppercase mb-2"><?= esc(strtoupper($post['language'])) ?> · <?= esc(date('d.m.Y', strtotime($post['updated_at']))) ?></p>
            <h2 class="h3 fw-bold mb-3"><?= esc($post['title']) ?></h2>
            <?php if (! empty($post['featured_image_url'])): ?>
                <img src="<?= esc($post['featured_image_url']) ?>" alt="<?= esc($post['featured_image_alt'] ?? '') ?>" class="img-fluid rounded-4 mb-3" style="max-height:260px;width:100%;object-fit:cover">
            <?php endif; ?>
            <div class="border-top pt-3" style="line-height:1.8"><?= $post['content'] ?></div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="cms-panel mb-4">
            <h3 class="h5 fw-bold mb-1">🖼 Zdjęcie główne</h3>
            <p class="text-muted small mb-3">Wybierz jedno zdjęcie przed zatwierdzeniem. Kliknij tytuł żeby zaproponować nowe.</p>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-semibold small">Sugerowane zdjęcia z Pexels</span>
                <button class="btn btn-sm btn-outline-primary" id="suggest-btn" type="button">Zaproponuj zdjęcia</button>
            </div>
            <div id="suggest-status" class="small text-muted mb-2"></div>
            <div id="suggest-grid" class="row g-2 mb-3"></div>

            <?php if (! empty($post['featured_image_url'])): ?>
                <div id="selected-preview" class="mb-3">
                    <img src="<?= esc($post['featured_image_url']) ?>" alt="" class="img-fluid rounded-3 mb-1" style="height:120px;width:100%;object-fit:cover">
                    <p class="small text-muted mb-0">Aktualne zdjęcie<?= ! empty($post['featured_image_author']) ? ' · ' . esc($post['featured_image_author']) : '' ?></p>
                </div>
            <?php else: ?>
                <div id="selected-preview" class="d-none mb-3"></div>
            <?php endif; ?>
        </div>

        <form method="post" action="<?= site_url('admin/posts/' . $post['id'] . '/approve') ?>" id="approve-form">
            <input type="hidden" name="featured_image_url" id="img-url" value="<?= esc($post['featured_image_url'] ?? '') ?>">
            <input type="hidden" name="featured_image_alt" id="img-alt" value="<?= esc($post['featured_image_alt'] ?? '') ?>">
            <input type="hidden" name="featured_image_source" id="img-source" value="<?= esc($post['featured_image_source'] ?? '') ?>">
            <input type="hidden" name="featured_image_author" id="img-author" value="<?= esc($post['featured_image_author'] ?? '') ?>">
            <button type="submit" class="btn btn-success w-100 fw-semibold mb-2" style="height:48px">
                ✅ Zatwierdź i opublikuj
            </button>
        </form>

        <div class="cms-panel border-danger" style="border-color:#f1c2bd!important">
            <h3 class="h6 fw-bold mb-2 text-danger">Odrzuć wpis</h3>
            <form method="post" action="<?= site_url('admin/posts/' . $post['id'] . '/reject') ?>">
                <textarea class="form-control mb-2" name="reject_reason" rows="3" placeholder="Powód odrzucenia (opcjonalny — autor zobaczy ten komentarz)"></textarea>
                <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Odrzucić ten wpis?')">
                    ✗ Odrzuć
                </button>
            </form>
        </div>

        <div class="mt-3 text-center">
            <a class="btn btn-outline-secondary btn-sm" href="<?= site_url('admin/posts/' . $post['id'] . '/edit') ?>">
                ✎ Edytuj wpis
            </a>
        </div>
    </div>
</div>

<script>
const suggestBtn = document.getElementById('suggest-btn');
const suggestGrid = document.getElementById('suggest-grid');
const suggestStatus = document.getElementById('suggest-status');
const selectedPreview = document.getElementById('selected-preview');
const imgUrl = document.getElementById('img-url');
const imgAlt = document.getElementById('img-alt');
const imgSource = document.getElementById('img-source');
const imgAuthor = document.getElementById('img-author');

suggestBtn.addEventListener('click', async () => {
    suggestGrid.innerHTML = '';
    suggestStatus.textContent = 'Szukam pasujących zdjęć z Pexels…';
    suggestBtn.disabled = true;

    try {
        const res = await fetch('<?= site_url('admin/stock-images/suggest') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ title: <?= json_encode($post['title']) ?>, content: <?= json_encode(strip_tags($post['content'])) ?> })
        });
        const data = await res.json();

        if (!data.success) {
            suggestStatus.textContent = data.message || 'Nie udało się pobrać zdjęć.';
            return;
        }

        suggestStatus.textContent = `Dostawca: ${data.provider} · Zapytanie: "${data.query}"`;

        if (!data.images.length) {
            suggestStatus.textContent += ' · Brak wyników.';
            return;
        }

        data.images.forEach(img => {
            const col = document.createElement('div');
            col.className = 'col-4';
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'p-0 border-0 bg-transparent w-100 position-relative';
            btn.style.cssText = 'cursor:pointer';
            btn.innerHTML = `<img src="${img.thumb}" alt="${img.alt || ''}" class="img-fluid rounded-3 w-100" style="height:80px;object-fit:cover">
                <span class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white rounded-bottom-3 px-1" style="font-size:10px;padding:2px 4px">${img.author || img.source}</span>`;
            btn.addEventListener('click', () => {
                imgUrl.value = img.url;
                imgAlt.value = img.alt || '';
                imgSource.value = img.source || '';
                imgAuthor.value = img.author || '';
                selectedPreview.classList.remove('d-none');
                selectedPreview.innerHTML = `<img src="${img.url}" alt="${img.alt || ''}" class="img-fluid rounded-3 mb-1" style="height:120px;width:100%;object-fit:cover">
                    <p class="small text-muted mb-0">Wybrane · ${img.source}${img.author ? ' · ' + img.author : ''}</p>`;
                document.querySelectorAll('#suggest-grid button').forEach(b => b.style.outline = '');
                btn.style.outline = '3px solid #0d6efd';
            });
            col.appendChild(btn);
            suggestGrid.appendChild(col);
        });
    } catch (e) {
        suggestStatus.textContent = 'Błąd połączenia z serwisem zdjęć.';
    } finally {
        suggestBtn.disabled = false;
    }
});
</script>
<?= $this->endSection() ?>
