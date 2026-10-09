<?= $this->extend('layouts/main') ?>

<?= $this->section('pageActions') ?>
<a class="btn btn-secondary" href="<?= site_url('products') ?>">Back</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $editing = $product !== null; ?>
<section class="card">
    <form method="post" enctype="multipart/form-data" action="<?= $editing ? site_url('products/' . $product['id']) : site_url('products') ?>">
        <?= csrf_field() ?>
        <div class="form-grid">
            <div class="field field-full">
                <label for="name">Product name</label>
                <input id="name" name="name" maxlength="100" value="<?= old('name', $product['name'] ?? '') ?>" required>
            </div>
            <div class="field">
                <label for="price">Price (PHP)</label>
                <input id="price" name="price" type="number" min="0" step="0.01" value="<?= old('price', $product['price'] ?? '') ?>" required>
            </div>
            <div class="field">
                <label for="stock_quantity">Stock quantity</label>
                <input id="stock_quantity" name="stock_quantity" type="number" min="0" step="1" value="<?= old('stock_quantity', $product['stock_quantity'] ?? 0) ?>" required>
            </div>
            <div class="field field-full">
                <label for="image">Product image</label>
                <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
                <span class="help">JPG, PNG, or WebP; maximum 2 MB. Images are cropped to 800 × 800 for display.</span>
            </div>
            <div class="field-full actions">
                <button class="btn btn-primary" type="submit"><?= $editing ? 'Save Changes' : 'Add Product' ?></button>
                <a class="btn btn-secondary" href="<?= site_url('products') ?>">Cancel</a>
            </div>
        </div>
    </form>
</section>
<?= $this->endSection() ?>
