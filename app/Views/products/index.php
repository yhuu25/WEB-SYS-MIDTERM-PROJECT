<?= $this->extend('layouts/main') ?>

<?= $this->section('pageActions') ?>
<a class="btn btn-primary" href="<?= site_url('products/new') ?>">Add Product</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card table-wrap">
    <?php if ($products === []): ?>
        <div class="empty">No products yet. Add your first product.</div>
    <?php else: ?>
        <table>
            <thead><tr><th>Image</th><th>Name</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td>
                        <?php if ($product['image']): ?>
                            <img class="thumb" src="<?= base_url('uploads/products/' . rawurlencode($product['image'])) ?>" alt="">
                        <?php else: ?>
                            <span class="thumb placeholder">P</span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= esc($product['name']) ?></strong></td>
                    <td>₱<?= number_format((float) $product['price'], 2) ?></td>
                    <td>
                        <?= esc($product['stock_quantity']) ?>
                        <?php if ((int) $product['stock_quantity'] <= 5): ?><span class="badge badge-warning">Low</span><?php endif; ?>
                    </td>
                    <td><span class="badge <?= $product['is_active'] ? 'badge-success' : 'badge-muted' ?>"><?= $product['is_active'] ? 'Active' : 'Archived' ?></span></td>
                    <td class="actions">
                        <a class="btn btn-secondary btn-small" href="<?= site_url('products/' . $product['id'] . '/edit') ?>">Edit</a>
                        <?php if ($product['is_active']): ?>
                            <form class="inline-form" method="post" action="<?= site_url('products/' . $product['id'] . '/archive') ?>" onsubmit="return confirm('Archive this product?')">
                                <?= csrf_field() ?><button class="btn btn-danger btn-small" type="submit">Archive</button>
                            </form>
                        <?php else: ?>
                            <form class="inline-form" method="post" action="<?= site_url('products/' . $product['id'] . '/restore') ?>">
                                <?= csrf_field() ?><button class="btn btn-secondary btn-small" type="submit">Restore</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
