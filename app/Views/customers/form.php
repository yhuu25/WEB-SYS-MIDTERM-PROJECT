<?= $this->extend('layouts/main') ?>

<?= $this->section('pageActions') ?>
<a class="btn btn-secondary" href="<?= site_url('customers') ?>">Back</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $editing = $customer !== null; ?>
<section class="card">
    <form method="post" action="<?= $editing ? site_url('customers/' . $customer['id']) : site_url('customers') ?>">
        <?= csrf_field() ?>
        <div class="form-grid">
            <div class="field field-full">
                <label for="full_name">Full name</label>
                <input id="full_name" name="full_name" maxlength="100" value="<?= old('full_name', $customer['full_name'] ?? '') ?>" required>
            </div>
            <div class="field">
                <label for="email">Email address</label>
                <input id="email" name="email" type="email" maxlength="100" value="<?= old('email', $customer['email'] ?? '') ?>" required>
            </div>
            <div class="field">
                <label for="phone">Phone number</label>
                <input id="phone" name="phone" maxlength="20" value="<?= old('phone', $customer['phone'] ?? '') ?>">
            </div>
            <div class="field-full actions">
                <button class="btn btn-primary" type="submit"><?= $editing ? 'Save Changes' : 'Add Customer' ?></button>
                <a class="btn btn-secondary" href="<?= site_url('customers') ?>">Cancel</a>
            </div>
        </div>
    </form>
</section>
<?= $this->endSection() ?>
