<?php

/** @var \App\Domain\Event $event */
/** @var \App\Domain\User|null $currentUser */

$title = 'Event bewerken';

require __DIR__ . '/partials/header.php';
?>

<h1>Event bewerken</h1>

<?php foreach ($errors ?? [] as $error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>

<form action="" method="post">
    <label for="title">Titel</label>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($event->title) ?>">

    <label for="description">Beschrijving</label>
    <input type="text" name="description" id="description" value="<?= htmlspecialchars($event->description) ?>">

    <input type="submit" value="Opslaan">
</form>

<?php require __DIR__ . '/partials/footer.php'; ?>
