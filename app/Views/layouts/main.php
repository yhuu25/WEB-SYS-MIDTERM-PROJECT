<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'POS') ?> | Complete POS</title>
    <link rel="stylesheet" href="<?= base_url('assets/app.css') ?>">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="<?= site_url('dashboard') ?>">Complete POS<small>IT0049 MIDTERM PROJECT</small></a>
        <?php $path = trim(uri_string(), '/'); ?>
        <nav class="nav" aria-label="Main navigation">
            <a class="<?= $path === 'dashboard' ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>">Dashboard</a>
            <a class="<?= str_starts_with($path, 'products') ? 'active' : '' ?>" href="<?= site_url('products') ?>">Products</a>
            <a class="<?= str_starts_with($path, 'customers') ? 'active' : '' ?>" href="<?= site_url('customers') ?>">Customers</a>
            <a class="<?= str_starts_with($path, 'staff') ? 'active' : '' ?>" href="<?= site_url('staff') ?>">Staff</a>
            <a class="<?= $path === 'sales/new' ? 'active' : '' ?>" href="<?= site_url('sales/new') ?>">Record Sale</a>
            <a class="<?= $path === 'sales' ? 'active' : '' ?>" href="<?= site_url('sales') ?>">Sales History</a>
        </nav>
        <div class="sidebar-footer">
            <div>Signed in as</div>
            <strong><?= esc($currentUser['full_name'] ?? '') ?></strong>
            <form method="post" action="<?= site_url('logout') ?>" style="margin-top:12px">
                <?= csrf_field() ?>
                <button class="btn btn-secondary btn-small" type="submit">Log out</button>
            </form>
        </div>
    </aside>
    <main class="main">
        <header class="topbar">
            <div>
                <h1><?= esc($title ?? 'Complete POS') ?></h1>
                <?php if (isset($subtitle)): ?><div class="muted"><?= esc($subtitle) ?></div><?php endif; ?>
            </div>
            <?= $this->renderSection('pageActions') ?>
        </header>

        <?= $this->include('partials/flash') ?>
        <?= $this->renderSection('content') ?>
    </main>
</div>
</body>
</html>
