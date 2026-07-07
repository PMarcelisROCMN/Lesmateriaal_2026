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

    <label for="date">Datum</label>
    <input type="date" name="date" id="date">

    <label for="description">Korte beschrijving</label>
    <input type="text" name="description" id="description" placeholder="Een korte samenvatting voor het overzicht...">

    <label for="content">Volledige tekst</label>
    <textarea name="content" id="content" rows="8" placeholder="De volledige informatie over het event..."></textarea>

    <input type="submit" value="Aanmaken">
</form>

<?php require __DIR__ . '/partials/footer.php'; ?>
