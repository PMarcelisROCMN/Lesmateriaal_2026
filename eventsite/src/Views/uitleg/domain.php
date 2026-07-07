<?php

$title  = 'Uitleg: Domain';
$huidig = 'domain';

$eventClass = <<<'PHP'
// src/Domain/Event.php
class Event {
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
    ) {}
}
PHP;

$zonderDomain = <<<'PHP'
// Zonder Domain: je sleept overal database-rijen (arrays) mee
$row = $stmt->fetch();     // ['id' => 1, 'title' => 'Concert', ...]

echo $row['title'];        // werkt
echo $row['titel'];        // typfout, maar PHP klaagt pas tijdens het draaien
// geen autocomplete: je moet alle kolomnamen uit je hoofd kennen
PHP;

$metDomain = <<<'PHP'
// Met Domain: een net object met vaste eigenschappen
$event = new Event(1, 'Concert', 'Een leuke avond');

echo $event->title;        // werkt, met autocomplete in de editor
echo $event->titel;        // de editor onderstreept dit meteen: bestaat niet
$event->title = 'Anders';  // mag niet: readonly, niemand wijzigt het per ongeluk
PHP;

$hydrate = <<<'PHP'
// src/Repositories/EventRepository.php
// De Repository zet elke database-rij om in een Event-object. Dat heet "hydrateren".
private function hydrate(array $row): Event
{
    return new Event(
        (int) $row['id'],
        $row['title'],
        $row['description'],
    );
}
PHP;

require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/uitleg_nav.php';
?>

<a class="back-link" href="<?= BASE_URL ?>">&larr; Terug naar het overzicht</a>

<h1>Domain: de modellen</h1>
<p>Een domainmodel is het "ding" waar je app over gaat, als een eigen klasse. In dit
    project zijn dat <code>Event</code> en <code>User</code>. Het zijn simpele klassen die
    vooral data bewaren.</p>

<pre><code><?= htmlspecialchars($eventClass) ?></code></pre>

<h2>Waarom is dit handig?</h2>
<p>Je zou de gegevens van een event ook gewoon kunnen laten zoals ze uit de database komen:
    een rij, oftewel een array met <code>['title' =&gt; ...]</code>. Dat werkt, maar je levert
    er veel voor in. Vergelijk de twee:</p>

<div class="compare">
    <div class="box">
        <div class="label">Zonder Domain (database-rij)</div>
        <pre><code><?= htmlspecialchars($zonderDomain) ?></code></pre>
    </div>
    <div class="box">
        <div class="label">Met Domain (een Event-object)</div>
        <pre><code><?= htmlspecialchars($metDomain) ?></code></pre>
    </div>
</div>

<p>De winst van een eigen model:</p>
<ul>
    <li><strong>Type-veiligheid:</strong> de editor weet dat <code>$event</code> een
        <code>Event</code> is. Vraag je iets op wat niet bestaat (<code>$event->titel</code>),
        dan word je meteen gewaarschuwd in plaats van pas als de pagina draait.</li>
    <li><strong>Autocomplete:</strong> je hoeft de veldnamen niet te onthouden, de editor
        vult <code>->title</code> en <code>->description</code> voor je aan.</li>
    <li><strong>readonly:</strong> de velden staan op <code>readonly</code>, dus niemand
        past per ongeluk ergens halverwege de app een titel aan.</li>
    <li><strong>Eén plek voor de vorm:</strong> wil je weten welke gegevens een event heeft?
        Je kijkt in <code>Event.php</code>, niet in tien losse queries.</li>
</ul>

<h2>Hoe werkt het samen met de rest?</h2>
<p>De database geeft nog steeds gewone rijen terug. De <strong>Repository</strong> zet die
    om naar Event-objecten (hydrateren). Vanaf dat moment werkt de hele app met nette
    objecten: de Service, de Controller en de View krijgen allemaal een <code>Event</code>
    en nooit meer een kale array.</p>

<pre><code><?= htmlspecialchars($hydrate) ?></code></pre>

<?php require __DIR__ . '/../partials/footer.php'; ?>
