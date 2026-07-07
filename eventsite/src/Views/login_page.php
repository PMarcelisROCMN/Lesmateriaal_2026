<?php

/** @var \App\Domain\User|null $currentUser */

$title = 'Inloggen';

require __DIR__ . '/partials/header.php';
?>

<h1>Inloggen</h1>

<?php foreach ($errors ?? [] as $error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>

<form action="<?= BASE_URL ?>login" method="post">
    <label for="username">Gebruikersnaam</label>
    <input type="text" name="username" id="username">

    <label for="password">Wachtwoord</label>
    <input type="password" name="password" id="password">

    <button>Inloggen</button>
</form>

<p class="muted">Nog geen account? <a href="<?= BASE_URL ?>register">Registreren</a></p>

<?php require __DIR__ . '/partials/footer.php'; ?>
