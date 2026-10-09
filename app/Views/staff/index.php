<?= $this->extend('layouts/main') ?>

<?= $this->section('pageActions') ?>
<a class="btn btn-primary" href="<?= site_url('staff/new') ?>">Add Staff Member</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="card table-wrap">
    <table>
        <thead><tr><th>Avatar</th><th>Full name</th><th>Username</th><th>Created</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($staff as $member): ?>
            <tr>
                <td>
                    <?php if ($member['avatar']): ?>
                        <img class="avatar" src="<?= base_url('uploads/avatars/' . rawurlencode($member['avatar'])) ?>" alt="">
                    <?php else: ?>
                        <span class="avatar placeholder"><?= esc(strtoupper(substr($member['full_name'], 0, 1))) ?></span>
                    <?php endif; ?>
                </td>
                <td><strong><?= esc($member['full_name']) ?></strong></td>
                <td><?= esc($member['username']) ?></td>
                <td><?= esc(date('M j, Y', strtotime($member['created_at']))) ?></td>
                <td class="actions">
                    <a class="btn btn-secondary btn-small" href="<?= site_url('staff/' . $member['id'] . '/edit') ?>">Edit</a>
                    <?php if ((int) $currentUser['id'] !== (int) $member['id']): ?>
                        <form class="inline-form" method="post" action="<?= site_url('staff/' . $member['id'] . '/delete') ?>" onsubmit="return confirm('Delete this staff member?')">
                            <?= csrf_field() ?><button class="btn btn-danger btn-small" type="submit">Delete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?= $this->endSection() ?>
