<?php
/** @var App\Domain\Event $event */
$title = $event->title;
require __DIR__ . '/../partials/header.php';
?>
<article class="detail">
    <h1><?= e($event->title) ?></h1>
    <p class="meta"><?= e($event->date) ?> &middot; <?= e($event->location) ?></p>
    <p class="description"><?= nl2br(e($event->description)) ?></p>
    <p><a href="<?= e(url('/')) ?>">&larr; Terug naar de agenda</a></p>
</article>

<?php require __DIR__ . '/../partials/footer.php'; ?>
