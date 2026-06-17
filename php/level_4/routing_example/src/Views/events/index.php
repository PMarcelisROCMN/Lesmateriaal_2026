<?php
/** @var App\Domain\Event[] $events */
$title = 'Agenda';
require __DIR__ . '/../partials/header.php';
?>
<h1>Aankomende evenementen</h1>

<?php if (empty($events)): ?>
    <p class="empty">Er staan nog geen evenementen gepland.</p>
<?php else: ?>
    <div class="cards">
        <?php foreach ($events as $event): ?>
            <article class="card">
                <h2><a href="<?= e(url('/events/' . $event->id)) ?>"><?= e($event->title) ?></a></h2>
                <p class="meta"><?= e($event->date) ?> &middot; <?= e($event->location) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
