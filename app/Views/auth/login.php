<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="card">
    <h1>Login</h1>
    <?php if ($error): ?>
        <div class="errors"><?= esc($error) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('login') ?>">
        <label>Email</label>
        <input type="email" name="email" value="admin@example.com" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</div>
<?= $this->endSection() ?>