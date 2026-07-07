<?php

$title  = 'Uitleg: Router';
$huidig = 'router';

$zonderRouter = <<<'PHP'
// Zonder router: je linkt rechtstreeks naar losse .php-bestanden
// URL: event.php?id=5   -> de .php en je mappen staan in de URL
<a href="event.php?id=5">Bekijk event</a>

// event.php regelt vervolgens alles zelf
$id = $_GET['id'];
// ...database, logica en HTML door elkaar
PHP;

$metRouter = <<<'PHP'
// src/Routes/Web.php
// URL: /events/5
$router->get('/events/(\d+)', function ($eventId) use ($eventController) {
    $eventController->show($eventId);
});
PHP;

require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/uitleg_nav.php';
?>

<a class="back-link" href="<?= BASE_URL ?>">&larr; Terug naar het overzicht</a>

<h1>Router: welke URL hoort bij welke code?</h1>
<p>De Router is de wegwijzer van je app. Voor elke URL staat op één plek welke code er moet
    draaien. Opent iemand <code>/events/5</code>? Dan zoekt de Router de bijbehorende regel op
    en roept de juiste methode van de Controller aan.</p>

<h2>Waarom een router?</h2>
<p>Zonder router maak je voor elke pagina een los <code>.php</code>-bestand
    (<code>events.php</code>, <code>event.php</code>) en link je daar rechtstreeks naartoe.
    Dan staat de <code>.php</code> in je URL en laat je meteen je hele mappenstructuur zien.
    Verplaats of hernoem je een bestand, dan klopt de link niet meer.</p>

<div class="compare">
    <div class="box">
        <div class="label">Zonder router</div>
        <pre><code><?= htmlspecialchars($zonderRouter) ?></code></pre>
    </div>
    <div class="box">
        <div class="label">Met router</div>
        <pre><code><?= htmlspecialchars($metRouter) ?></code></pre>
    </div>
</div>

<p>De winst:</p>
<ul>
    <li><strong>Nette URLs:</strong> <code>/events/5</code> in plaats van
        <code>event.php?id=5</code>. Geen <code>.php</code> en geen mappen in de URL.</li>
    <li><strong>URL los van je bestanden:</strong> de Router bepaalt welke URL bij welke code
        hoort. Waar je bestand toevallig staat, maakt voor de URL niet meer uit.</li>
    <li><strong>Alles op één plek:</strong> in <code>Web.php</code> zie je in één oogopslag
        welke URLs de app kent.</li>
</ul>

<p>Het stukje <code>(\d+)</code> in de route betekent "een of meer cijfers". De Router pakt
    dat getal uit de URL en geeft het als <code>$eventId</code> door aan de Controller. Zo
    weet <code>/events/5</code> automatisch dat het om event 5 gaat.</p>

<h2>Hoe werkt het samen met de rest?</h2>
<p>De Router doet zelf niks inhoudelijks. Hij koppelt alleen een URL aan een methode van de
    <strong>Controller</strong> en geeft eventueel een stukje uit de URL mee (zoals het id).
    Daarna neemt de Controller het over.</p>

<?php require __DIR__ . '/../partials/footer.php'; ?>
