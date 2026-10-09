<?= $this->extend('layouts/main') ?>

<?= $this->section('pageActions') ?>
<a class="btn btn-secondary" href="<?= site_url('staff') ?>">Back</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $editing = $staffMember !== null; ?>
<section class="card">
    <form method="post" enctype="multipart/form-data" action="<?= $editing ? site_url('staff/' . $staffMember['id']) : site_url('staff') ?>">
        <?= csrf_field() ?>
        <div class="form-grid">
            <div class="field">
                <label for="full_name">Full name</label>
                <input id="full_name" name="full_name" maxlength="100" value="<?= old('full_name', $staffMember['full_name'] ?? '') ?>" required>
            </div>
            <div class="field">
                <label for="username">Username</label>
                <input id="username" name="username" maxlength="50" value="<?= old('username', $staffMember['username'] ?? '') ?>" required autocomplete="username">
            </div>
            <div class="field">
                <label for="password">Password<?= $editing ? ' (leave blank to keep current)' : '' ?></label>
                <input id="password" name="password" type="password" minlength="8" <?= $editing ? '' : 'required' ?> autocomplete="new-password">
                <span class="help">Use at least 8 characters.</span>
            </div>
            <div class="field">
                <label for="password_confirm">Confirm password</label>
                <input id="password_confirm" name="password_confirm" type="password" <?= $editing ? '' : 'required' ?> autocomplete="new-password">
            </div>
            <div class="field field-full">
                <label for="avatar">Avatar</label>
                <input id="avatar" name="avatar" type="file" accept="image/jpeg,image/png,image/webp">
                <span class="help">JPG, PNG, or WebP; maximum 2 MB. Images are cropped to 800 × 800.</span>
            </div>
            <div class="field-full actions">
                <button class="btn btn-primary" type="submit"><?= $editing ? 'Save Changes' : 'Add Staff Member' ?></button>
                <a class="btn btn-secondary" href="<?= site_url('staff') ?>">Cancel</a>
            </div>
        </div>
    </form>
</section>
<?= $this->endSection() ?>
