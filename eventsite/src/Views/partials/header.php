<?php

/**
 * Gedeelde bovenkant van elke pagina.
 * Zet voor de require een $title (en eventueel $currentUser voor de nav):
 *
 *   <?php $title = 'Alle events'; require __DIR__ . '/partials/header.php'; ?>
 */

/** @var string $title */
/** @var \App\Domain\User|null $currentUser */

// $currentUser is niet op elke pagina gezet. Standaard: niemand ingelogd.
$currentUser = $currentUser ?? null;
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Eventsite') ?></title>
    <link rel="stylesheet" href="<?= ASSET_BASE ?>style.css">
</head>

<body>
    <div class="container">

        <nav class="nav">
            <span class="nav-left">
                <a href="<?= BASE_URL ?>">Home</a>
                <a href="<?= BASE_URL ?>events">Alle events</a>
            </span>
            <span>
                <?php if ($currentUser): ?>
                    Ingelogd als <strong><?= htmlspecialchars($currentUser->username) ?></strong><?php if ($currentUser->isAdmin): ?> (beheerder)<?php endif; ?>
                    - <a href="<?= BASE_URL ?>logout">Uitloggen</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>login">Inloggen</a> of <a href="<?= BASE_URL ?>register">Registreren</a>
                <?php endif; ?>
            </span>
        </nav>
