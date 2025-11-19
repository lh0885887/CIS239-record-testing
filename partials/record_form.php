<?php $formats = formats_all(); ?>

<div class="container mt-3">
    <form method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" name="title">
        </div>
        <div class="mb-3">
            <label for="artist" class="form-label">Artist</label>
            <input type="text" class="form-control" name="artist">
        </div>
        <div class="col-md-3 mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" name="price">
        </div>
        <div class="col-md-3">
            <label class="form-label">Format</label>
            <select name="format_id" class="form-select" required>
                <option value="">Select...</option>
                <?php foreach ($formats as $f): ?>
                    <option value="<?= (int)$f['id'] ?>"><?= htmlspecialchars($f['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <input type="hidden" name="action" value="create">

        <div class="col-12 mt-3">
            <button class="btn btn-success">Create</button>
            <a href="?view=list" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>