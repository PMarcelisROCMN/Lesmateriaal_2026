<?php
/** @var App\Domain\Event[] $events */
$title = 'Beheer';
require __DIR__ . '/../partials/header.php';
?>
<div class="admin-head">
    <h1>Evenementen beheren</h1>
    <a class="btn" href="<?= e(url('/admin/events/create')) ?>">+ Nieuw evenement</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Titel</th>
            <th>Datum</th>
            <th>Locatie</th>
            <th class="right">Acties</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($events)): ?>
            <tr><td colspan="4" class="empty">Nog geen evenementen. Maak er een aan!</td></tr>
        <?php endif; ?>
        <?php foreach ($events as $event): ?>
            <tr>
                <td><?= e($event->title) ?></td>
                <td><?= e($event->date) ?></td>
                <td><?= e($event->location) ?></td>
                <td class="actions">
                    <a href="<?= e(url('/admin/events/' . $event->id . '/edit')) ?>">Wijzigen</a>
                    <form action="<?= e(url('/admin/events/' . $event->id . '/delete')) ?>"
                          method="post"
                          onsubmit="return confirm('Weet je zeker dat je dit evenement wilt verwijderen?');">
                        <button type="submit" class="danger">Verwijderen</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../partials/footer.php'; ?>
