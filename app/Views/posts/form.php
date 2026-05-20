<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h1 class="h2 mb-4"><?= esc($title) ?></h1>
<?php if ($errors): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
            <div><?= esc($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<form method="post" action="<?= esc($action) ?>" class="cms-panel">
    <label class="form-label fw-semibold">Title</label>
    <input class="form-control" id="post-title" type="text" name="title" value="<?= esc($post['title'] ?? '') ?>" required>
    <label class="form-label fw-semibold">Language</label>
    <select class="form-select" name="language">
        <option value="en" <?= ($post['language'] ?? 'en') === 'en' ? 'selected' : '' ?>>English</option>
        <option value="pl" <?= ($post['language'] ?? '') === 'pl' ? 'selected' : '' ?>>Polski</option>
        <option value="de" <?= ($post['language'] ?? '') === 'de' ? 'selected' : '' ?>>Deutsch</option>
    </select>
    <label class="form-label fw-semibold">Status</label>
    <?php $currentStatus = $post['status'] ?? 'draft'; ?>
    <?php if (in_array($currentStatus, ['review_pending', 'approved'], true)): ?>
        <div class="alert alert-info py-2 mb-2">
            Wpis jest w statusie <strong><?= esc($currentStatus === 'review_pending' ? 'Do recenzji' : 'Zatwierdzony') ?></strong> — zmiana treści cofnie go do szkicu.
        </div>
    <?php endif; ?>
    <?php if ($currentStatus === 'rejected' && ! empty($post['reject_reason'])): ?>
        <div class="alert alert-danger py-2 mb-2">
            <strong>Odrzucono:</strong> <?= esc($post['reject_reason']) ?>
        </div>
    <?php endif; ?>
    <select class="form-select" name="status">
        <option value="draft" <?= $currentStatus === 'draft' ? 'selected' : '' ?>>Szkic (draft)</option>
        <option value="rejected" <?= $currentStatus === 'rejected' ? 'selected' : '' ?> <?= ! in_array($currentStatus, ['rejected'], true) ? 'disabled style="display:none"' : '' ?>>Odrzucony</option>
        <option value="review_pending" <?= $currentStatus === 'review_pending' ? 'selected' : '' ?> <?= $currentStatus !== 'review_pending' ? 'disabled style="display:none"' : '' ?>>Do recenzji</option>
        <option value="approved" <?= $currentStatus === 'approved' ? 'selected' : '' ?> <?= $currentStatus !== 'approved' ? 'disabled style="display:none"' : '' ?>>Zatwierdzony</option>
        <option value="publish" <?= $currentStatus === 'publish' ? 'selected' : '' ?>>Opublikowany (publish)</option>
    </select>
    <label class="form-label fw-semibold">Content</label>
    <textarea class="form-control" id="content" name="content" rows="16"><?= esc($post['content'] ?? '') ?></textarea>

    <section class="mt-4 p-3 rounded-4 border bg-light">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h2 class="h5 mb-1">Stock image suggestions</h2>
                <p class="text-muted mb-0">Suggest images from Pexels or Pixabay based on this article.</p>
            </div>
            <button class="btn btn-outline-primary" type="button" id="suggest-images">Suggest images</button>
        </div>
        <input type="hidden" name="featured_image_url" id="featured-image-url" value="<?= esc($post['featured_image_url'] ?? '') ?>">
        <input type="hidden" name="featured_image_alt" id="featured-image-alt" value="<?= esc($post['featured_image_alt'] ?? '') ?>">
        <input type="hidden" name="featured_image_source" id="featured-image-source" value="<?= esc($post['featured_image_source'] ?? '') ?>">
        <input type="hidden" name="featured_image_author" id="featured-image-author" value="<?= esc($post['featured_image_author'] ?? '') ?>">
        <div id="selected-image" class="<?= empty($post['featured_image_url']) ? 'd-none' : '' ?> mb-3">
            <img src="<?= esc($post['featured_image_url'] ?? '') ?>" alt="<?= esc($post['featured_image_alt'] ?? '') ?>" class="img-fluid rounded-4" style="max-height:220px;object-fit:cover;width:100%">
            <p class="small text-muted mt-2 mb-0">
                Selected image
                <?php if (! empty($post['featured_image_source'])): ?>
                    from <?= esc($post['featured_image_source']) ?>
                <?php endif; ?>
                <?php if (! empty($post['featured_image_author'])): ?>
                    by <?= esc($post['featured_image_author']) ?>
                <?php endif; ?>
            </p>
        </div>
        <div id="image-suggestion-status" class="small text-muted"></div>
        <div id="image-suggestions" class="row g-3 mt-1"></div>
    </section>

    <div class="mt-4">
        <button class="btn btn-primary" type="submit">Save</button>
        <a class="btn btn-outline-secondary" href="<?= site_url('admin/posts') ?>">Cancel</a>
    </div>
</form>
<script src="https://cdn.tiny.cloud/1/aq87z61pev2lehzjmfl3aqvk4juyujeiwlhlcctx6id84pnd/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Bezpieczna inicjalizacja (na wypadek reloadów / walidacji)
    if (tinymce.get('content')) {
        tinymce.get('content').remove();
    }

    tinymce.init({
        selector: '#content',
        height: 500,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'fullscreen',
            'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | bold italic backcolor | ' +
                 'alignleft aligncenter alignright alignjustify | ' +
                 'bullist numlist outdent indent | removeformat | code | help'
    });

    const suggestButton = document.getElementById('suggest-images');
    const suggestions = document.getElementById('image-suggestions');
    const statusBox = document.getElementById('image-suggestion-status');
    const selectedImage = document.getElementById('selected-image');
    const imageUrl = document.getElementById('featured-image-url');
    const imageAlt = document.getElementById('featured-image-alt');
    const imageSource = document.getElementById('featured-image-source');
    const imageAuthor = document.getElementById('featured-image-author');

    suggestButton.addEventListener('click', async () => {

        // zapis treści z edytora do textarea
        if (tinymce.get('content')) {
            tinymce.triggerSave();
        }

        suggestions.innerHTML = '';
        statusBox.textContent = 'Searching for matching stock photos...';
        suggestButton.disabled = true;

        try {
            const response = await fetch('<?= site_url('admin/stock-images/suggest') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    title: document.getElementById('post-title').value,
                    content: document.getElementById('content').value
                })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                statusBox.textContent = data.message || 'Could not fetch image suggestions.';
                return;
            }

            statusBox.textContent = `Provider: ${data.provider}. Query: ${data.query}`;

            if (!data.images.length) {
                suggestions.innerHTML = '<div class="col-12"><div class="alert alert-warning">No images found.</div></div>';
                return;
            }

            data.images.forEach((image) => {
                const col = document.createElement('div');
                col.className = 'col-md-4';

                const card = document.createElement('button');
                card.type = 'button';
                card.className = 'border-0 bg-white rounded-4 shadow-sm overflow-hidden text-start w-100 h-100 p-0';

                card.innerHTML = `
                    <img src="${image.thumb}" style="height:140px;width:100%;object-fit:cover">
                    <div class="p-3">
                        <strong class="d-block">${image.source}</strong>
                        <span class="small text-muted">${image.author || 'Unknown author'}</span>
                    </div>
                `;

                card.addEventListener('click', () => {
                    imageUrl.value = image.url;
                    imageAlt.value = image.alt || '';
                    imageSource.value = image.source || '';
                    imageAuthor.value = image.author || '';

                    selectedImage.classList.remove('d-none');
                    selectedImage.innerHTML = `
                        <img src="${image.url}" class="img-fluid rounded-4" style="max-height:220px;object-fit:cover;width:100%">
                        <p class="small text-muted mt-2 mb-0">
                            Selected image from ${image.source}${image.author ? ' by ' + image.author : ''}
                        </p>
                    `;
                });

                col.appendChild(card);
                suggestions.appendChild(col);
            });

        } catch (error) {
            statusBox.textContent = 'Could not connect to the stock image service.';
        } finally {
            suggestButton.disabled = false;
        }
    });

});
</script>
<?= $this->endSection() ?>