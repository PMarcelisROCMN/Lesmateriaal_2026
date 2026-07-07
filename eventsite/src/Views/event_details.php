<?php

/** @var \App\Domain\Event $event */
/** @var \App\Domain\User|null $currentUser */

$title = $event->title;

require __DIR__ . '/partials/header.php';
?>

<a href=<?= BASE_URL . '/events' ?>>&larr; back to events</a>

<h1><?= htmlspecialchars($event->title) ?></h1>

<h3>Beschrijving</h3>
<p><?= htmlspecialchars($event->description) ?></p>

<?php require __DIR__ . '/partials/footer.php'; ?>
