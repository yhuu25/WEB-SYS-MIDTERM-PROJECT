<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in | Complete POS</title>
    <link rel="stylesheet" href="<?= base_url('assets/app.css') ?>">
</head>
<body class="login-page">
<main class="login-card">
    <h1>Complete POS</h1>
    <div class="muted">Staff sign-in</div>
    <?= view('partials/flash') ?>
    <form method="post" action="<?= site_url('login') ?>">
        <?= csrf_field() ?>
        <div class="field">
            <label for="username">Username</label>
            <input id="username" name="username" value="<?= old('username') ?>" required autofocus autocomplete="username">
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">
        </div>
        <button class="btn btn-primary" type="submit">Log in</button>
    </form>
</main>
</body>
</html>
