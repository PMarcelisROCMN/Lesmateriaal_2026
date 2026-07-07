<?php

$title  = 'Uitleg: View';
$huidig = 'view';

$controllerKant = <<<'PHP'
// src/Controllers/EventController.php
public function show(int $id): void
{
    // $event is hier een gewone lokale variabele
    $event = $this->eventService->getEvent($id);

    // require draait de view ALSOF hij hier staat, dus $event is er zichtbaar
    require __DIR__ . '/../Views/event_details.php';
}
PHP;

$viewKant = <<<'PHP'
// src/Views/event_details.php
<?php /** @var \App\Domain\Event $event */ ?>

<h1><?= htmlspecialchars($event->title) ?></h1>
<p><?= htmlspecialchars($event->description) ?></p>
PHP;

$zonderView = <<<'PHP'
// Alles in 1 bestand: database, logica en HTML door elkaar
$result = $pdo->query('SELECT * FROM events WHERE id = ' . $_GET['id']);
$row = $result->fetch();
echo '<h1>' . $row['title'] . '</h1>';
PHP;

$metView = <<<'PHP'
// De view krijgt een kant-en-klaar object en toont het alleen
<h1><?= htmlspecialchars($event->title) ?></h1>
PHP;

require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/uitleg_nav.php';
?>

<a class="back-link" href="<?= BASE_URL ?>">&larr; Terug naar het overzicht</a>

<h1>View: wat de gebruiker ziet</h1>
<p>De view is het enige stuk dat de gebruiker echt in de browser ziet. Het is HTML met hier
    en daar een beetje PHP om data in te vullen. Geen database, geen business-regels: alleen
    tonen wat er al klaarligt.</p>

<h2>Hoe komt de view aan de <code>$event</code>?</h2>
<p>Dat zit hem in <code>require</code>. De Controller maakt een variabele <code>$event</code>
    aan en doet daarna <code>require</code> van de view, ín dezelfde methode. Daardoor draait
    de view alsof hij op die plek staat, en kan hij bij alle lokale variabelen van de
    Controller. De view haalt dus zelf niks op, hij krijgt <code>$event</code> aangereikt.</p>

<div class="compare">
    <div class="box">
        <div class="label">Controller: maakt $event en laadt de view</div>
        <pre><code><?= htmlspecialchars($controllerKant) ?></code></pre>
    </div>
    <div class="box">
        <div class="label">View: gebruikt $event</div>
        <pre><code><?= htmlspecialchars($viewKant) ?></code></pre>
    </div>
</div>

<h2>En wat doet die <code>/** @var ... */</code> regel dan?</h2>
<p>Bovenaan de view staat vaak zoiets:</p>
<pre><code>&lt;?php /** @var \App\Domain\Event $event */ ?&gt;</code></pre>
<p>Dit is een <strong>comment</strong>. PHP doet er tijdens het draaien helemaal niks mee.
    Het is bedoeld voor je <strong>editor</strong>. Je vertelt ermee: "de variabele
    <code>$event</code> is een <code>Event</code>". De editor weet dan welke velden erbij
    horen en kan <code>$event->title</code> aanvullen en je waarschuwen bij
    <code>$event->titel</code>. Puur een hint voor de tooling en voor jezelf, dus.</p>

<h2>Waarom niet gewoon alles in een bestand?</h2>
<p>Je zou de query, de logica en de HTML in een bestand kunnen proppen. Dat werkt, maar het
    wordt snel een knoop waarin je niks meer terugvindt en waarin een foutje zo gemaakt is.</p>

<div class="compare">
    <div class="box">
        <div class="label">Zonder aparte view</div>
        <pre><code><?= htmlspecialchars($zonderView) ?></code></pre>
    </div>
    <div class="box">
        <div class="label">Met aparte view</div>
        <pre><code><?= htmlspecialchars($metView) ?></code></pre>
    </div>
</div>

<p>Let ook op <code>htmlspecialchars()</code> in de view. Dat zet tekens als
    <code>&lt;</code> en <code>&gt;</code> om naar onschadelijke tekst, zodat iemand geen
    HTML of scripts in bijvoorbeeld een titel kan smokkelen. In de view toon je data van
    buiten, dus daar hoort die bescherming thuis.</p>

<?php require __DIR__ . '/../partials/footer.php'; ?>
