<?php
/**
 * Wordt gebruikt voor zowel aanmaken als wijzigen.
 * @var string      $heading  titel boven het formulier
 * @var string      $action   de URL waar het formulier naartoe post
 * @var string|null $error    foutmelding (of null)
 * @var array       $values   huidige waardes: title, location, date, description
 */
$title = $heading;
require __DIR__ . '/../partials/header.php';
?>
<h1><?= e($heading) ?></h1>

<?php if ($error !== null): ?>
    <p class="error"><?= e($error) ?></p>
<?php endif; ?>

<form action="<?= e($action) ?>" method="post" class="form">
    <label>
        Titel
        <input type="text" name="title" value="<?= e($values['title']) ?>">
    </label>
    <label>
        Locatie
        <input type="text" name="location" value="<?= e($values['location']) ?>">
    </label>
    <label>
        Datum
        <input type="date" name="date" value="<?= e($values['date']) ?>">
    </label>
    <label>
        Omschrijving
        <textarea name="description" rows="5"><?= e($values['description']) ?></textarea>
    </label>
    <div class="form-actions">
        <button type="submit" class="btn">Opslaan</button>
        <a href="<?= e(url('/admin/events')) ?>">Annuleren</a>
    </div>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>
