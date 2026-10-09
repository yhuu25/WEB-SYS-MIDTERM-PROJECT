<?= $this->extend('layouts/main') ?>

<?= $this->section('pageActions') ?>
<a class="btn btn-primary" href="<?= site_url('sales/new') ?>">Record Sale</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card table-wrap">
    <?php if ($sales === []): ?>
        <div class="empty">No transactions have been recorded.</div>
    <?php else: ?>
        <table>
            <thead><tr><th>#</th><th>Product</th><th>Customer</th><th>Staff</th><th>Quantity</th><th>Total price</th><th>Date</th></tr></thead>
            <tbody>
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?= esc($sale['id']) ?></td>
                    <td><strong><?= esc($sale['product_name']) ?></strong></td>
                    <td><?= esc($sale['customer_name'] ?? 'Walk-in') ?></td>
                    <td><?= esc($sale['staff_name']) ?></td>
                    <td><?= esc($sale['quantity']) ?></td>
                    <td>₱<?= number_format((float) $sale['total_price'], 2) ?></td>
                    <td><?= esc(date('M j, Y g:i A', strtotime($sale['created_at']))) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
