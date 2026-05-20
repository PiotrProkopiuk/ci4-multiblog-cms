<!doctype html>
<html lang="<?= esc($language ?? 'en') ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Multi Blog CMS') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{--accent:<?= esc($theme['accent'] ?? '#1455d9') ?>;--hero-image:url('<?= $theme['image'] ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=1800&q=80' ?>')}
        body{font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;background:#f6f3ee;color:#18202a}
        a{color:var(--accent);text-decoration:none}
        .site-shell{min-height:100vh}
        .glass-nav{background:rgba(255,255,255,.88);backdrop-filter:blur(16px);border-bottom:1px solid rgba(20,30,45,.08)}
        .brand-dot{width:12px;height:12px;border-radius:999px;background:var(--accent);display:inline-block}
        .hero{background:linear-gradient(90deg,rgba(12,18,28,.78),rgba(12,18,28,.22)),var(--hero-image);background-size:cover;background-position:center;border-radius:32px;min-height:430px;color:white;overflow:hidden}
        .hero-copy{max-width:660px}
        .eyebrow{letter-spacing:.16em;text-transform:uppercase;font-size:.78rem;font-weight:800;color:rgba(255,255,255,.78)}
        .btn-primary{--bs-btn-bg:var(--accent);--bs-btn-border-color:var(--accent);--bs-btn-hover-bg:var(--accent);--bs-btn-hover-border-color:var(--accent)}
        .feature-card,.post-card,.cms-panel{background:white;border:1px solid rgba(20,30,45,.08);border-radius:24px;box-shadow:0 18px 50px rgba(20,30,45,.07)}
        .post-card{transition:transform .18s ease,box-shadow .18s ease}
        .post-card:hover{transform:translateY(-3px);box-shadow:0 24px 60px rgba(20,30,45,.11)}
        .theme-tailo{background:linear-gradient(180deg,#fff7ed,#fffaf5 45%,#f8fafc)}
        .theme-garden{background:linear-gradient(180deg,#effaf1,#fbf8ee 46%,#f8fafc)}
        .theme-zen{background:linear-gradient(180deg,#fff1f6,#fffaf4 46%,#f8fafc)}
        .cms-panel{padding:24px}
        .errors{background:#fff1f0;color:#8a1f16;border:1px solid #f1c2bd;border-radius:12px;padding:12px}
        .muted{color:#64748b}
        input,select,textarea{width:100%;box-sizing:border-box;padding:10px;border:1px solid #cfd6df;border-radius:8px;margin:6px 0 14px}
        .button{background:var(--accent);color:white;border:0;border-radius:8px;padding:10px 14px;display:inline-block;cursor:pointer}
        .button.secondary{background:#586575}
        .button.danger{background:#b42318}
        /* Floating Admin Button */
        .admin-fab{position:fixed;left:16px;bottom:16px;z-index:1080;border-radius:999px;padding:10px 14px;box-shadow:0 8px 20px rgba(20,30,45,.2)}
    </style>
</head>
<?php $t = $t ?? []; $tr = static fn($key, $fallback) => $t[$key] ?? $fallback; ?>
<body class="<?= esc($theme['class'] ?? 'theme-default') ?>">
<?php $uri = service('uri'); $isAdmin = ($uri->getSegment(1) === 'admin'); $isAdminUser = (session('is_logged_in') && session('role') === 'admin'); ?>
<?php $langs = $languagesAvailable ?? ($blog['languages'] ?? ['pl','en','de']); $langs = array_values(array_unique(array_filter((array) $langs))); ?>
<div class="site-shell">
    <nav class="navbar navbar-expand-lg sticky-top glass-nav">
        <div class="container py-2">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= site_url('/') ?>">
                <span class="brand-dot"></span>
                <?= esc($blog['name'] ?? 'Multi Blog CMS') ?>
            </a>
            <div class="d-flex flex-wrap align-items-center gap-3">
                <?php if (count($langs) > 1): ?>
                    <select class="form-select form-select-sm" aria-label="Language selector" onchange="if(this.value){location.href='<?= site_url('') ?>'+this.value;}">
                        <?php foreach ($langs as $lang): ?>
                            <option value="<?= esc($lang) ?>" <?= isset($language) && $language === $lang ? 'selected' : '' ?>><?= strtoupper(esc($lang)) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
                <?php /* Ukryty przycisk logowania – dostęp wyłącznie przez bezpośredni link */ ?>
            </div>
        </div>
    </nav>

    <?php if ($isAdmin && session('is_logged_in')): ?>
        <div class="container-fluid">
            <div class="row">
                <aside class="col-12 col-md-3 col-lg-2 py-4">
                    <div class="cms-panel sticky-top" style="top:80px">
                        <div class="fw-bold mb-2">Panel</div>
                        <ul class="nav flex-column small">
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/posts') ?>">Wpisy</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/posts/review') ?>">Recenzja</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/generate') ?>">✨ AI Generator</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/blogs') ?>">⚙️ Blogi</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/translations') ?>">Tłumaczenia</a></li>
                            <li class="nav-item mt-2"><a class="nav-link text-danger" href="<?= site_url('logout') ?>">Wyloguj</a></li>
                        </ul>
                    </div>
                </aside>
                <main class="col-12 col-md-9 col-lg-10 py-4 py-lg-5">
                    <?= $this->renderSection('content') ?>
                </main>
            </div>
        </div>
    <?php else: ?>
        <main class="container py-4 py-lg-5">
            <?= $this->renderSection('content') ?>
        </main>
    <?php endif; ?>
</div>

<?php if ($isAdminUser): ?>
    <!-- Offcanvas admin menu accessible anywhere for logged-in admin -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="adminOffcanvas" aria-labelledby="adminOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="adminOffcanvasLabel">Panel administracyjny</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="list-unstyled small mb-4">
                <li><a class="d-block py-1" href="<?= site_url('admin/posts') ?>">Wpisy</a></li>
                <li><a class="d-block py-1" href="<?= site_url('admin/posts/review') ?>">Recenzja</a></li>
                <li><a class="d-block py-1" href="<?= site_url('admin/generate') ?>">✨ AI Generator</a></li>
                <li><a class="d-block py-1" href="<?= site_url('admin/blogs') ?>">⚙️ Blogi</a></li>
                <li><a class="d-block py-1" href="<?= site_url('admin/translations') ?>">Tłumaczenia</a></li>
                <li class="mt-2"><a class="d-block py-1 text-danger" href="<?= site_url('logout') ?>">Wyloguj</a></li>
            </ul>
            <div class="text-muted small">Szybki dostęp dla zalogowanego admina.</div>
        </div>
    </div>

    <!-- Floating button to toggle offcanvas; hidden on large screens if already on /admin -->
    <button type="button" class="btn btn-primary admin-fab <?= $isAdmin ? 'd-lg-none' : '' ?>" data-bs-toggle="offcanvas" data-bs-target="#adminOffcanvas" aria-controls="adminOffcanvas">☰ Admin</button>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>