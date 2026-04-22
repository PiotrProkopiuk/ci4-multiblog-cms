<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center gap-3 mb-4">
    <h1 class="h2 mb-0">Posts</h1>
    <a class="btn btn-primary" href="<?= site_url('admin/posts/new') ?>">New post</a>
</div>
<?php if ($posts): ?>
    <table class="table table-hover align-middle bg-white rounded-4 overflow-hidden">
        <thead>
        <tr>
            <th>Title</th>
            <th>Status</th>
            <th>Language</th>
            <th>Updated</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= esc($post['title']) ?></td>
                <td><?= esc($post['status']) ?></td>
                <td><?= esc($post['language']) ?></td>
                <td><?= esc($post['updated_at']) ?></td>
                <td>
                    <a class="btn btn-sm btn-outline-primary" href="<?= site_url('admin/posts/' . $post['id'] . '/edit') ?>">Edit</a>
                    <form method="post" action="<?= site_url('admin/posts/' . $post['id'] . '/delete') ?>" style="display:inline">
                        <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="cms-panel">No posts yet.</div>
<?php endif; ?>
<?= $this->endSection() ?>