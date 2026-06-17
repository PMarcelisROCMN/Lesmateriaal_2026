<?php
/** @var string|null $error */
$title = 'Inloggen';
require __DIR__ . '/../partials/header.php';
?>
<h1>Inloggen (beheer)</h1>

<?php if ($error !== null): ?>
    <p class="error"><?= e($error) ?></p>
<?php endif; ?>

<form action="<?= e(url('/login')) ?>" method="post" class="form">
    <label>
        Gebruikersnaam
        <input type="text" name="username">
    </label>
    <label>
        Wachtwoord
        <input type="password" name="password">
    </label>
    <div class="form-actions">
        <button type="submit" class="btn">Inloggen</button>
    </div>
</form>

<p class="hint">Demo-account: <code>admin</code> / <code>admin123</code></p>

<?php require __DIR__ . '/../partials/footer.php'; ?>
