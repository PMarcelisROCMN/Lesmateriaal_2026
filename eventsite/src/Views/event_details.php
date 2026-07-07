<?php

/** @var \App\Domain\Event $event */
/** @var \App\Domain\User|null $currentUser */

$title = $event->title;

require __DIR__ . '/partials/header.php';
?>

<a class="back-link" href="<?= BASE_URL ?>events">&larr; back to events</a>

<h1><?= htmlspecialchars($event->title) ?></h1>
<p class="event-date"><?= htmlspecialchars(date('d-m-Y', strtotime($event->date))) ?></p>

<p class="lead"><?= htmlspecialchars($event->description) ?></p>

<div class="event-content"><?= nl2br(htmlspecialchars($event->content)) ?></div>

<?php require __DIR__ . '/partials/footer.php'; ?>
