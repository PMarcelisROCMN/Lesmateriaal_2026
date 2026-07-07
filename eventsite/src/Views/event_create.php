<?php

/** @var \App\Domain\User|null $currentUser */

$title = 'Nieuw event';

require __DIR__ . '/partials/header.php';
?>

<h1>Nieuw event aanmaken</h1>

<?php foreach ($errors ?? [] as $error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>

<form action="" method="post">
    <label for="title">Titel</label>
    <input type="text" name="title" id="title" placeholder="Vul een titel in...">

    <label for="description">Beschrijving</label>
    <input type="text" name="description" id="description" placeholder="Vul een beschrijving in...">

    <input type="submit" value="Aanmaken">
</form>

<?php require __DIR__ . '/partials/footer.php'; ?>
