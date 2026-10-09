<?= $this->extend('layouts/main') ?>

<?= $this->section('pageActions') ?>
<a class="btn btn-secondary" href="<?= site_url('sales') ?>">View Sales History</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card">
    <?php if ($products === []): ?>
        <div class="empty">
            No active products have stock available.<br><br>
            <a class="btn btn-primary" href="<?= site_url('products/new') ?>">Add or Restock a Product</a>
        </div>
    <?php else: ?>
        <form method="post" action="<?= site_url('sales') ?>" id="sale-form">
            <?= csrf_field() ?>
            <div class="form-grid">
                <div class="field field-full">
                    <label for="product_id">Product</label>
                    <select id="product_id" name="product_id" required>
                        <option value="">Select a product</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= esc($product['id']) ?>" data-price="<?= esc($product['price']) ?>" data-stock="<?= esc($product['stock_quantity']) ?>" <?= (string) old('product_id') === (string) $product['id'] ? 'selected' : '' ?>>
                                <?= esc($product['name']) ?> — ₱<?= number_format((float) $product['price'], 2) ?> (<?= esc($product['stock_quantity']) ?> in stock)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="customer_id">Customer (optional)</label>
                    <select id="customer_id" name="customer_id">
                        <option value="">Walk-in customer</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= esc($customer['id']) ?>" <?= (string) old('customer_id') === (string) $customer['id'] ? 'selected' : '' ?>><?= esc($customer['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="quantity">Quantity</label>
                    <input id="quantity" name="quantity" type="number" min="1" step="1" value="<?= old('quantity', 1) ?>" required>
                    <span id="stock-help" class="help">Select a product to see available stock.</span>
                </div>
                <div class="field-full price-preview">Total: <strong id="total-preview">₱0.00</strong></div>
                <div class="field-full actions">
                    <button class="btn btn-primary" type="submit">Complete Sale</button>
                    <a class="btn btn-secondary" href="<?= site_url('dashboard') ?>">Cancel</a>
                </div>
            </div>
        </form>
        <script>
            (() => {
                const product = document.getElementById('product_id');
                const quantity = document.getElementById('quantity');
                const total = document.getElementById('total-preview');
                const help = document.getElementById('stock-help');
                const update = () => {
                    const option = product.options[product.selectedIndex];
                    const price = Number(option?.dataset.price || 0);
                    const stock = Number(option?.dataset.stock || 0);
                    const qty = Number(quantity.value || 0);
                    quantity.max = stock || '';
                    help.textContent = option?.dataset.stock ? `${stock} unit(s) available.` : 'Select a product to see available stock.';
                    total.textContent = new Intl.NumberFormat('en-PH', {style: 'currency', currency: 'PHP'}).format(price * qty);
                };
                product.addEventListener('change', update);
                quantity.addEventListener('input', update);
                update();
            })();
        </script>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
