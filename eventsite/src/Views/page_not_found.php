<?php

$title = 'Pagina niet gevonden';

require __DIR__ . '/partials/header.php';
?>

<h1>Pagina niet gevonden</h1>
<p class="muted">Deze pagina bestaat niet (fout 404).</p>
<p><a href="<?= BASE_URL ?>">Terug naar de homepage</a></p>

<?php require __DIR__ . '/partials/footer.php'; ?>
