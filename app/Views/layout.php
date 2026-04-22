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
    </style>
</head>
<?php $t = $t ?? []; $tr = static fn($key, $fallback) => $t[$key] ?? $fallback; ?>
<body class="<?= esc($theme['class'] ?? 'theme-default') ?>">
<div class="site-shell">
    <nav class="navbar navbar-expand-lg sticky-top glass-nav">
        <div class="container py-2">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= site_url('/') ?>">
                <span class="brand-dot"></span>
                <?= esc($blog['name'] ?? 'Multi Blog CMS') ?>
            </a>
            <div class="d-flex flex-wrap align-items-center gap-3">
                <a class="small fw-semibold" href="<?= site_url('pl') ?>">PL</a>
                <a class="small fw-semibold" href="<?= site_url('en') ?>">EN</a>
                <a class="small fw-semibold" href="<?= site_url('de') ?>">DE</a>
                <?php if (session('is_logged_in')): ?>
                    <a class="small fw-semibold" href="<?= site_url('admin/posts') ?>">Admin</a>
                    <a class="small fw-semibold" href="<?= site_url('admin/translations') ?>">Translations</a>
                    <a class="btn btn-sm btn-outline-dark" href="<?= site_url('logout') ?>">Logout</a>
                <?php else: ?>
                    <a class="btn btn-sm btn-outline-dark" href="<?= site_url('login') ?>"><?= esc($tr('nav.login', 'Login')) ?></a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="container py-4 py-lg-5">
        <?= $this->renderSection('content') ?>
    </main>
</div>
</body>
</html>