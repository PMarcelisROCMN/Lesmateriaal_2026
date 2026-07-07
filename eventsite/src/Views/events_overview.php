<?php

/** @var \App\Domain\Event[] $events */
/** @var \App\Domain\User|null $currentUser */

$title = 'Alle events';

require __DIR__ . '/partials/header.php';
?>

<h1>Alle events</h1>

<form action="" class="search">
    <input type="text" placeholder="Zoek op titel..." name="q">
    <button>Zoeken</button>
</form>

<?php if (!$events): ?>
    <p class="muted">Er zijn geen events gevonden.</p>
<?php else: ?>
    <ul class="event-list">
        <?php foreach ($events as $event): ?>
            <li>
                <span class="event-title"><?= htmlspecialchars($event->title) ?></span><br>
                <a href="<?= BASE_URL ?>events/<?= $event->id ?>">Bekijk details</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
