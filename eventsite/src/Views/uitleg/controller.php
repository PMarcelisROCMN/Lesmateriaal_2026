<?php

$title  = 'Uitleg: Controller';
$huidig = 'controller';

$registerCode = <<<'PHP'
// src/Controllers/LoginController.php
public function register(): void
{
    $username        = $_POST['username'] ?? '';
    $password        = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // input-validatie: klopt de vorm van het formulier?
    $errors = [];
    if ($username === '' || $password === '' || $confirmPassword === '') {
        $errors[] = 'Vul alle velden in';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'De wachtwoorden zijn niet gelijk';
    }

    // fout? toon het formulier opnieuw met de foutenlijst
    if (!empty($errors)) {
        $this->showRegister($errors);
        return;
    }

    // de business-regels checkt de Service
    $errors = $this->authService->register($username, $password);
    if (!empty($errors)) {
        $this->showRegister($errors);
        return;
    }

    $this->redirect('/events');
}
PHP;

require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/uitleg_nav.php';
?>

<a class="back-link" href="<?= BASE_URL ?>">&larr; Terug naar het overzicht</a>

<h1>Controller: de schakel tussen web en app</h1>
<p>De Controller is het eerste stukje van jouw code dat de Router aanroept. Hij neemt het
    verzoek aan, leest de invoer uit (<code>$_POST</code>, <code>$_GET</code>), doet de
    <strong>input-validatie</strong>, vraagt de Service om het echte werk en kiest tot slot
    welke view getoond wordt.</p>

<h2>Input-validatie vs business-regels</h2>
<p>De Controller controleert de <em>vorm</em> van wat binnenkomt: zijn de verplichte velden
    ingevuld, zijn de twee wachtwoorden gelijk? De <em>inhoudelijke</em> regels (bestaat de
    gebruikersnaam al, is het wachtwoord lang genoeg) laat hij aan de Service over. Zo blijft
    de Controller dun.</p>

<pre><code><?= htmlspecialchars($registerCode) ?></code></pre>

<h2>Waarom niet alles in één script?</h2>
<p>Je kunt het ophalen van invoer, de regels, de database en de HTML in één bestand zetten.
    Dat werkt bij een klein voorbeeld, maar wordt al snel een knoop waarin alles aan elkaar
    kleeft. De Controller houdt de boel juist uit elkaar:</p>
<ul>
    <li>Hij <strong>coördineert</strong> alleen: invoer aannemen, doorgeven, view kiezen.</li>
    <li>Hij bevat <strong>geen SQL</strong> (dat doet de Repository) en <strong>geen
        business-regels</strong> (dat doet de Service).</li>
    <li>Daardoor blijft elke methode kort en snap je in één blik wat er gebeurt.</li>
</ul>

<h2>Hoe werkt het samen met de rest?</h2>
<p>De <strong>Router</strong> roept de Controller aan. De Controller roept de
    <strong>Service</strong> aan voor de logica, en die gebruikt weer de
    <strong>Repository</strong> voor de database. Wat er terugkomt (bijvoorbeeld een lijst
    <code>Event</code>-objecten) geeft de Controller door aan de <strong>View</strong>.</p>

<?php require __DIR__ . '/../partials/footer.php'; ?>
