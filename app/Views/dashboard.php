<?= $this->extend('layouts/main') ?>

<?= $this->section('pageActions') ?>
<a class="btn btn-primary" href="<?= site_url('sales/new') ?>">Record a Sale</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="stats">
    <div class="card stat"><span class="muted">Active products</span><strong><?= esc($productCount) ?></strong></div>
    <div class="card stat"><span class="muted">Customers</span><strong><?= esc($customerCount) ?></strong></div>
    <div class="card stat"><span class="muted">Staff members</span><strong><?= esc($staffCount) ?></strong></div>
    <div class="card stat"><span class="muted">Sales today</span><strong><?= esc($todaySales) ?></strong></div>
</section>

<section class="card">
    <h2>Recent Sales</h2>
    <?php if ($recentSales === []): ?>
        <div class="empty">No sales have been recorded yet.</div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Product</th><th>Customer</th><th>Staff</th><th>Qty</th><th>Total</th><th>Date</th></tr></thead>
                <tbody>
                <?php foreach ($recentSales as $sale): ?>
                    <tr>
                        <td><?= esc($sale['product_name']) ?></td>
                        <td><?= esc($sale['customer_name'] ?? 'Walk-in') ?></td>
                        <td><?= esc($sale['staff_name']) ?></td>
                        <td><?= esc($sale['quantity']) ?></td>
                        <td>₱<?= number_format((float) $sale['total_price'], 2) ?></td>
                        <td><?= esc(date('M j, Y g:i A', strtotime($sale['created_at']))) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
