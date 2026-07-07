<?php

$title  = 'Uitleg: Service';
$huidig = 'service';

$serviceCode = <<<'PHP'
// src/Services/EventService.php
public function addEvent(string $title, string $description): array
{
    $errors = $this->checkValidation($title, $description);

    // fouten? niks opslaan, lijstje teruggeven
    if (!empty($errors)) {
        return $errors;
    }

    $this->repository->create($title, $description);

    return $errors;
}

private function checkValidation(string $title, string $description): array
{
    $errors = [];

    if (mb_strlen($title) < 5 || mb_strlen($title) > 20) {
        $errors[] = 'De titel moet tussen de 5 en 20 tekens zijn';
    }

    return $errors;
}
PHP;

require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/uitleg_nav.php';
?>

<a class="back-link" href="<?= BASE_URL ?>">&larr; Terug naar het overzicht</a>

<h1>Service: de regels van je app</h1>
<p>De Service klinkt vaag, maar het idee is simpel: hier staan de <strong>business-regels</strong>.
    Alles wat te maken heeft met "mag dit wel of niet volgens de regels van de app" hoort
    hier thuis. Niet in de Controller en niet in de Repository.</p>

<pre><code><?= htmlspecialchars($serviceCode) ?></code></pre>

<h2>Waarom een aparte laag voor die regels?</h2>
<p>Stel je zet de regel "een titel is tussen 5 en 20 tekens" gewoon in de Controller die een
    event aanmaakt. Dan moet je diezelfde regel nog een keer opschrijven in de Controller die
    een event bewerkt. Twee plekken, en dus twee plekken waar het uit de pas kan lopen.</p>
<p>Door de regel in de Service te zetten, geldt hij overal hetzelfde:</p>
<ul>
    <li><strong>Op één plek:</strong> aanmaken én bewerken gebruiken dezelfde
        <code>checkValidation()</code>. Pas je de regel aan, dan klopt hij meteen overal.</li>
    <li><strong>Los van het web:</strong> de Service weet niks van formulieren of URLs. Zou
        je later een event via een import of een API aanmaken, dan gelden dezelfde regels.</li>
    <li><strong>Makkelijker te testen:</strong> je kunt de regels los testen, zonder browser
        of database erbij.</li>
</ul>

<h2>Hoe werkt het samen met de rest?</h2>
<p>De <strong>Controller</strong> roept de Service aan en geeft de ruwe invoer mee. De Service
    controleert de regels en gebruikt daarna de <strong>Repository</strong> om echt iets op te
    slaan of op te halen. Fouten geeft hij terug als een lijst, zodat de Controller ze kan
    laten zien.</p>

<?php require __DIR__ . '/../partials/footer.php'; ?>
