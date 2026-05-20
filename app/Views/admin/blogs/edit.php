<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php if (session('flash_success')): ?>
    <div class="alert alert-success rounded-3 mb-3"><?= esc(session('flash_success')) ?></div>
<?php endif; ?>
<?php if (session('flash_error')): ?>
    <div class="alert alert-danger rounded-3 mb-3"><?= esc(session('flash_error')) ?></div>
<?php endif; ?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('admin/blogs') ?>">← Powrót</a>
    <h1 class="h2 mb-0"><?= esc($title) ?></h1>
    <span class="badge bg-light text-dark border" style="font-family:monospace"><?= esc($blog['slug']) ?></span>
</div>

<form method="post" action="<?= site_url('admin/blogs/' . $blog['id'] . '/update') ?>">
<div class="row g-4">

    <div class="col-lg-7">

        <div class="cms-panel mb-4">
            <h2 class="h5 fw-bold mb-3">📝 Podstawowe informacje</h2>

            <label class="form-label fw-semibold small">Nazwa bloga</label>
            <input class="form-control mb-3" type="text" name="name" value="<?= esc($blog['name']) ?>" required>

            <label class="form-label fw-semibold small">Opis</label>
            <textarea class="form-control mb-3" name="description" rows="2"
                      placeholder="Krótki opis..."><?= esc($blog['description'] ?? '') ?></textarea>

            <label class="form-label fw-semibold small">Hasło / tagline</label>
            <input class="form-control mb-3" type="text" name="tagline"
                   value="<?= esc($blog['tagline'] ?? '') ?>"
                   placeholder="np. Twój przewodnik po życiu z kotem">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Domena</label>
                    <input class="form-control" type="text" name="domain"
                           value="<?= esc($blog['domain'] ?? '') ?>" placeholder="tailo.eu" id="domainInput">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Domyślny język</label>
                    <select class="form-select" name="default_language">
                        <option value="pl" <?= $blog['default_language'] === 'pl' ? 'selected' : '' ?>>🇵🇱 Polski</option>
                        <option value="en" <?= $blog['default_language'] === 'en' ? 'selected' : '' ?>>🇬🇧 English</option>
                        <option value="de" <?= $blog['default_language'] === 'de' ? 'selected' : '' ?>>🇩🇪 Deutsch</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="cms-panel mb-4">
            <h2 class="h5 fw-bold mb-3">🌐 Podpinanie domeny</h2>

            <?php if (empty($blog['domain'])): ?>
            <div class="alert alert-secondary mb-3" style="border-radius:12px">
                Wpisz domenę powyżej i zapisz — pojawią się instrukcje DNS.
            </div>
            <?php else: ?>

            <?php
            $status = $dnsStatus['status'] ?? 'none';
            $statusInfo = match($status) {
                'ok'         => ['cls' => 'success', 'icon' => '✅', 'txt' => 'Domena poprawnie wskazuje na serwer!'],
                'unresolved' => ['cls' => 'warning', 'icon' => '⏳', 'txt' => 'Domena nie jest jeszcze rozwiązywalna w DNS. Zmiany DNS mogą propagować do 48h.'],
                'wrong'      => ['cls' => 'warning', 'icon' => '⚠', 'txt' => 'Domena wskazuje na inny serwer. Sprawdź konfigurację DNS poniżej.'],
                default      => ['cls' => 'secondary', 'icon' => 'ℹ', 'txt' => 'Skonfiguruj DNS zgodnie z instrukcją poniżej.'],
            };
            ?>
            <div class="alert alert-<?= $statusInfo['cls'] ?> rounded-3 mb-3">
                <?= $statusInfo['icon'] ?> <?= $statusInfo['txt'] ?>
            </div>

            <p class="fw-semibold small mb-2">Jak podpiąć domenę <strong><?= esc($blog['domain']) ?></strong>:</p>

            <div class="d-flex flex-column gap-3 mb-3">
                <div class="p-3 rounded-3 border">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <span class="badge bg-primary">Opcja 1 — CNAME (zalecane)</span>
                        <small class="text-muted">u rejestratora domeny</small>
                    </div>
                    <table class="table table-sm mb-0 mt-2" style="font-family:monospace;font-size:.82rem">
                        <thead><tr><th>Typ</th><th>Host</th><th>Wartość</th></tr></thead>
                        <tbody>
                            <tr>
                                <td>CNAME</td>
                                <td><?= esc($blog['domain']) ?></td>
                                <td class="d-flex align-items-center gap-2">
                                    <span id="cnameVal"><?= esc($cmsHost) ?></span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1"
                                            onclick="navigator.clipboard.writeText('<?= esc($cmsHost) ?>');this.textContent='✓'">📋</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="text-muted mb-0 mt-2" style="font-size:.78rem">Dla subdomeny <code>www.<?= esc($blog['domain']) ?></code> — host: <code>www</code>, wartość: <code><?= esc($cmsHost) ?></code></p>
                </div>

                <div class="p-3 rounded-3 border">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <span class="badge bg-secondary">Opcja 2 — Replit Custom Domain</span>
                        <small class="text-muted">dla deploymentu produkcyjnego</small>
                    </div>
                    <p class="text-muted mb-0 mt-2" style="font-size:.82rem">
                        Po kliknięciu <strong>„Publish"</strong> w Replit → przejdź do ustawień deploymentu →
                        <strong>Custom domains</strong> → wpisz <code><?= esc($blog['domain']) ?></code>.
                        Replit automatycznie wygeneruje certyfikat SSL i skieruje ruch na właściwy blog
                        na podstawie nagłówka <code>Host</code>.
                    </p>
                </div>
            </div>

            <details class="text-muted" style="font-size:.8rem">
                <summary class="fw-semibold" style="cursor:pointer">Popularne rejestratory — gdzie znaleźć DNS?</summary>
                <ul class="mt-2 ps-3 mb-0">
                    <li><strong>OVH / OVH.pl</strong>: Panel → Domeny → Strefa DNS → Dodaj rekord</li>
                    <li><strong>home.pl</strong>: Panel → Domeny → Strefa DNS → Edytuj</li>
                    <li><strong>Cloudflare</strong>: DNS → Records → Add record (CNAME, Proxy OFF)</li>
                    <li><strong>GoDaddy</strong>: My Products → DNS → Add</li>
                    <li><strong>Namecheap</strong>: Domain List → Manage → Advanced DNS → Add New Record</li>
                </ul>
            </details>
            <?php endif; ?>
        </div>

        <div class="cms-panel mb-4">
            <h2 class="h5 fw-bold mb-3">🎨 Wygląd</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Kolor akcentu</label>
                    <div class="d-flex gap-2 align-items-center">
                        <input type="color" class="form-control form-control-color" name="accent_color"
                               id="colorPicker" value="<?= esc($blog['accent_color'] ?? '#f97316') ?>"
                               style="width:54px;height:38px;padding:2px">
                        <input type="text" class="form-control" id="colorText"
                               value="<?= esc($blog['accent_color'] ?? '#f97316') ?>"
                               style="font-family:monospace">
                    </div>
                </div>
            </div>
            <label class="form-label fw-semibold small mt-3">URL zdjęcia hero</label>
            <input class="form-control mb-1" type="url" name="hero_image_url"
                   value="<?= esc($blog['hero_image_url'] ?? '') ?>"
                   placeholder="https://images.unsplash.com/...">
            <p class="text-muted small mb-0">Polecamy <a href="https://unsplash.com" target="_blank">unsplash.com</a> (bezpłatne) lub Pexels.</p>
        </div>

        <div class="text-end">
            <a class="btn btn-outline-secondary me-2" href="<?= site_url('admin/blogs') ?>">Anuluj</a>
            <button class="btn btn-primary px-4" type="submit">💾 Zapisz</button>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="cms-panel">
            <h2 class="h5 fw-bold mb-3">📐 Szablon strony głównej</h2>
            <div class="d-flex flex-column gap-3">
            <?php foreach ($layouts as $key => $layout): ?>
                <?php $active = ($blog['homepage_layout'] ?? 'variant_a') === $key; ?>
                <label class="d-block" style="cursor:pointer">
                    <input type="radio" name="homepage_layout" value="<?= esc($key) ?>"
                        <?= $active ? 'checked' : '' ?>
                        class="d-none layout-radio" data-preview="<?= esc($layout['preview']) ?>">
                    <div class="layout-card p-3 rounded-3 border <?= $active ? 'border-primary bg-primary bg-opacity-10' : '' ?>">
                        <div class="d-flex align-items-start gap-2">
                            <div class="layout-check rounded-circle border border-2 flex-shrink-0 mt-1"
                                 style="width:18px;height:18px;background:<?= $active ? 'var(--bs-primary)' : 'white' ?>"></div>
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
                <iframe id="layout-preview"
                    src="<?= esc($layouts[$blog['homepage_layout'] ?? 'variant_a']['preview']) ?>"
                    style="width:100%;height:300px;border:1px solid #e2e8f0;border-radius:12px" loading="lazy"></iframe>
            </div>
        </div>
    </div>

</div>
</form>

<script>
const cp = document.getElementById('colorPicker');
const ct = document.getElementById('colorText');
cp.addEventListener('input', () => ct.value = cp.value);
ct.addEventListener('input', () => { if (/^#[0-9a-f]{6}$/i.test(ct.value)) cp.value = ct.value; });

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
