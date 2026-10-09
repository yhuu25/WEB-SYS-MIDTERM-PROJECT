<?= $this->extend('layouts/main') ?>

<?= $this->section('pageActions') ?>
<a class="btn btn-primary" href="<?= site_url('customers/new') ?>">Add Customer</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card table-wrap">
    <?php if ($customers === []): ?>
        <div class="empty">No customers yet.</div>
    <?php else: ?>
        <table>
            <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Added</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><strong><?= esc($customer['full_name']) ?></strong></td>
                    <td><?= esc($customer['email']) ?></td>
                    <td><?= esc($customer['phone'] ?: '—') ?></td>
                    <td><?= esc(date('M j, Y', strtotime($customer['created_at']))) ?></td>
                    <td class="actions">
                        <a class="btn btn-secondary btn-small" href="<?= site_url('customers/' . $customer['id'] . '/edit') ?>">Edit</a>
                        <form class="inline-form" method="post" action="<?= site_url('customers/' . $customer['id'] . '/delete') ?>" onsubmit="return confirm('Delete this customer?')">
                            <?= csrf_field() ?><button class="btn btn-danger btn-small" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
