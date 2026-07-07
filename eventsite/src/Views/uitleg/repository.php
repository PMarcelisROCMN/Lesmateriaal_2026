<?php

$title  = 'Uitleg: Repository';
$huidig = 'repository';

$repoCode = <<<'PHP'
// src/Repositories/EventRepository.php
public function getById(int $id): ?Event
{
    $stmt = $this->pdo->prepare('SELECT id, title, description FROM events WHERE id = ?');
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row === false) {
        return null;   // niks gevonden
    }

    return $this->hydrate($row);   // rij -> Event-object
}
PHP;

$zonderRepo = <<<'PHP'
// Zonder repository: SQL staat overal, ook zo (onveilig!)
$id = $_GET['id'];
$row = $pdo->query("SELECT * FROM events WHERE id = $id")->fetch();
// de id wordt zo in de query geplakt -> ruimte voor SQL-injectie
PHP;

$metRepo = <<<'PHP'
// Met repository: alle SQL op 1 plek, met een prepared statement
$event = $eventRepository->getById($id);
// de rest van de app weet niet eens dat er een database is
PHP;

require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/uitleg_nav.php';
?>

<a class="back-link" href="<?= BASE_URL ?>">&larr; Terug naar het overzicht</a>

<h1>Repository: het enige dat de database aanraakt</h1>
<p>De Repository is de laag die met de database praat. Ophalen, opslaan, wijzigen,
    verwijderen: dat gebeurt allemaal hier. De rest van de app hoeft dus niks van SQL te
    weten.</p>

<pre><code><?= htmlspecialchars($repoCode) ?></code></pre>

<h2>Waarom alle database-code bij elkaar?</h2>
<div class="compare">
    <div class="box">
        <div class="label">Zonder repository</div>
        <pre><code><?= htmlspecialchars($zonderRepo) ?></code></pre>
    </div>
    <div class="box">
        <div class="label">Met repository</div>
        <pre><code><?= htmlspecialchars($metRepo) ?></code></pre>
    </div>
</div>

<ul>
    <li><strong>SQL op één plek:</strong> moet er iets aan een query veranderen? Je weet
        precies waar je moet zijn.</li>
    <li><strong>De rest weet nergens van:</strong> de Service en Controller vragen gewoon om
        een <code>Event</code>. Of dat uit SQLite, MySQL of straks iets anders komt, maakt
        ze niet uit. Wissel je van database, dan pas je alleen de Repository aan.</li>
    <li><strong>Veiliger:</strong> de Repository gebruikt <strong>prepared statements</strong>
        (het <code>?</code> in de query). De waarde wordt apart meegegeven in plaats van in de
        tekst geplakt, zodat niemand via een invoerveld eigen SQL kan meesturen.</li>
</ul>

<h2>Hoe werkt het samen met de rest?</h2>
<p>De <strong>Service</strong> roept de Repository aan als er echt iets met de database moet
    gebeuren. De Repository haalt de rijen op en zet ze om naar
    <strong>Domain</strong>-objecten (<code>Event</code>, <code>User</code>) en geeft die
    terug. Zo werkt de rest van de app altijd met nette objecten.</p>

<?php require __DIR__ . '/../partials/footer.php'; ?>
