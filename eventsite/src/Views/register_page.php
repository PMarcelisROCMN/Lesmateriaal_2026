<?php

/** @var \App\Domain\User|null $currentUser */

$title = 'Registreren';

require __DIR__ . '/partials/header.php';
?>

<h1>Registreren</h1>

<?php foreach ($errors ?? [] as $error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>

<form action="<?= BASE_URL ?>register" method="post">
    <label for="username">Gebruikersnaam</label>
    <input type="text" name="username" id="username">

    <label for="password">Wachtwoord</label>
    <input type="password" name="password" id="password">

    <label for="confirmPassword">Wachtwoord herhalen</label>
    <input type="password" name="confirmPassword" id="confirmPassword">

    <button>Registreren</button>
</form>

<p class="muted">Al een account? <a href="<?= BASE_URL ?>login">Inloggen</a></p>

<?php require __DIR__ . '/partials/footer.php'; ?>
