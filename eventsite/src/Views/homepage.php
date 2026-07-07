<?php

/** @var \App\Domain\User|null $currentUser */

$title = 'Eventsite';

require __DIR__ . '/partials/header.php';
?>

<h1>Eventsite</h1>
<p class="muted">Een oefenproject om events te delen, en om te laten zien
    hoe je een PHP-app netjes in lagen opbouwt.</p>

<h2>Wat kun je op deze site doen?</h2>
<ul>
    <li><strong>Events bekijken:</strong> iedereen kan de lijst met events
        en de details van een event openen.</li>
    <li><strong>Events aanmaken en bewerken:</strong> een beheerder kan
        nieuwe events toevoegen en bestaande aanpassen.</li>
    <li><strong>Meedoen aan een event:</strong> ingelogde gebruikers kunnen
        zich aanmelden voor een event dat ze leuk vinden.</li>
</ul>

<h2>Hoe werkt de code? Het CSR-patroon</h2>
<p>De code is opgeknipt in <strong>lagen</strong>. Elke laag heeft één taak.
    Dat noemen we het <strong>CSR-patroon</strong>: <strong>C</strong>ontroller,
    <strong>S</strong>ervice, <strong>R</strong>epository. Daaromheen zitten nog
    de <strong>Router</strong>, het <strong>Domain</strong> (de modellen) en de
    <strong>Views</strong>.</p>
<p>Waarom zo veel lagen? Omdat elke laag maar één ding hoeft te doen, kun je
    makkelijker iets aanpassen zonder dat de rest omvalt. Hieronder volgen we
    één verzoek: <em>iemand opent de pagina met alle events</em>.</p>

<div class="flow">
    <div class="flow-step">
        <span class="num">1</span>
        <span>
            <span class="layer">Router</span>
            <span class="file">src/Routes/Web.php</span>
            <span class="what">Kijkt bij welke URL welke code hoort en roept de Controller aan.</span>
        </span>
    </div>
    <div class="flow-arrow">&darr;</div>

    <div class="flow-step">
        <span class="num">2</span>
        <span>
            <span class="layer">Controller</span>
            <span class="file">src/Controllers/EventController.php</span>
            <span class="what">Neemt het verzoek aan, controleert de invoer en vraagt de Service om data.</span>
        </span>
    </div>
    <div class="flow-arrow">&darr;</div>

    <div class="flow-step">
        <span class="num">3</span>
        <span>
            <span class="layer">Service</span>
            <span class="file">src/Services/EventService.php</span>
            <span class="what">Bevat de regels en logica. Bepaalt wat er wel en niet mag.</span>
        </span>
    </div>
    <div class="flow-arrow">&darr;</div>

    <div class="flow-step">
        <span class="num">4</span>
        <span>
            <span class="layer">Repository</span>
            <span class="file">src/Repositories/EventRepository.php</span>
            <span class="what">Praat met de database: ophalen, opslaan, wijzigen, verwijderen.</span>
        </span>
    </div>
    <div class="flow-arrow">&darr;</div>

    <div class="flow-step">
        <span class="num">5</span>
        <span>
            <span class="layer">Domain</span>
            <span class="file">src/Domain/Event.php</span>
            <span class="what">Het "ding" zelf: een Event of een User als eenvoudig object.</span>
        </span>
    </div>
    <div class="flow-arrow">&darr;</div>

    <div class="flow-step">
        <span class="num">6</span>
        <span>
            <span class="layer">View</span>
            <span class="file">src/Views/events_overview.php</span>
            <span class="what">Maakt van de data de HTML die de gebruiker in de browser ziet.</span>
        </span>
    </div>
</div>

<table class="legend">
    <tr><th>Laag</th><td>Waar het over gaat, in één zin</td></tr>
    <tr><th>Router</th><td>Welke URL hoort bij welke code?</td></tr>
    <tr><th>Controller</th><td>Neemt aan, controleert de invoer (de vorm) en geeft door.</td></tr>
    <tr><th>Service</th><td>Bepaalt of iets <em>mag</em>: de regels van de app.</td></tr>
    <tr><th>Repository</th><td>Het enige dat de database aanraakt.</td></tr>
    <tr><th>Domain</th><td>De vorm van je data: een Event, een User.</td></tr>
    <tr><th>View</th><td>Wat de gebruiker uiteindelijk ziet.</td></tr>
</table>

<p>Hieronder staat per laag een korte uitleg. Wil je echt de diepte in, met codevoorbeelden
    en waarom het beter is dan alles in één bestand proppen? Klik dan door naar de
    detailpagina van dat onderdeel.</p>

<h2>1. De Router</h2>
<p>De Router is de wegwijzer. Voor elke URL staat op één plek welke code moet draaien,
    zodat je nette URLs krijgt zoals <code>/events/5</code> in plaats van rechtstreeks naar
    losse bestanden linken zoals <code>event.php?id=5</code>.</p>
<p><a href="<?= BASE_URL ?>uitleg/router">Lees meer over de Router &rarr;</a></p>

<h2>2. De Controller</h2>
<p>De Controller is de schakel tussen het web en je app. Hij neemt het verzoek aan, doet
    de input-validatie (verplichte velden ingevuld? wachtwoorden gelijk?) en geeft het werk
    door aan de Service.</p>
<p><a href="<?= BASE_URL ?>uitleg/controller">Lees meer over de Controller &rarr;</a></p>

<h2>3. De Service</h2>
<p>In de Service staan de business-regels: mag dit volgens de regels van de app? Op één
    plek, zodat aanmaken en bewerken dezelfde controle gebruiken en je niks dubbel schrijft.</p>
<p><a href="<?= BASE_URL ?>uitleg/service">Lees meer over de Service &rarr;</a></p>

<h2>4. De Repository</h2>
<p>De Repository is de enige laag die de database aanraakt. Alle SQL staat hier bij elkaar,
    zodat de rest van de app niks van de database hoeft te weten.</p>
<p><a href="<?= BASE_URL ?>uitleg/repository">Lees meer over de Repository &rarr;</a></p>

<h2>5. Het Domain (de modellen)</h2>
<p>Een domainmodel is het "ding" waar je app over gaat, als net object: <code>Event</code>
    en <code>User</code>. Handiger dan losse database-rijen, want je krijgt type-veiligheid
    en autocomplete.</p>
<p><a href="<?= BASE_URL ?>uitleg/domain">Lees meer over het Domain &rarr;</a></p>

<h2>6. De View</h2>
<p>De view is het enige stuk dat de gebruiker echt ziet: de HTML met de data erin. Geen
    logica en geen database, alleen tonen wat er al klaarligt.</p>
<p><a href="<?= BASE_URL ?>uitleg/view">Lees meer over de View &rarr;</a></p>

<h2>Alles samen</h2>
<p>Eén verzoek reist dus netjes langs alle lagen en weer terug:</p>
<p><code>Router &rarr; Controller &rarr; Service &rarr; Repository &rarr; Domain &rarr; View</code></p>
<p>Elke laag doet één ding. Wil je een nieuwe regel toevoegen? Dan weet je precies
    waar: in de Service. Andere database? Alleen de Repository aanpassen. Zo blijft
    de code overzichtelijk, ook als het project groter wordt.</p>

<p style="margin-top:2rem;"><a href="<?= BASE_URL ?>events">Bekijk nu alle events &rarr;</a></p>

<?php require __DIR__ . '/partials/footer.php'; ?>
