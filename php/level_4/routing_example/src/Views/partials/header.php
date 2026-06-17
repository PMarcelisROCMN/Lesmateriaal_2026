<?php /** @var string $title */ ?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Evenementen') ?></title>
    <link rel="stylesheet" href="<?= e(url('/assets/css/style.css')) ?>">
</head>
<body>
<header class="topbar">
    <a class="brand" href="<?= e(url('/')) ?>">🎟️ Evenementen</a>
    <nav>
        <a href="<?= e(url('/')) ?>">Agenda</a>
        <?php if (!empty($_SESSION['admin'])): ?>
            <a href="<?= e(url('/admin/events')) ?>">Beheer</a>
            <form action="<?= e(url('/logout')) ?>" method="post" class="inline-form">
                <button type="submit" class="linklike">Uitloggen (<?= e($_SESSION['admin']) ?>)</button>
            </form>
        <?php else: ?>
            <a href="<?= e(url('/login')) ?>">Inloggen</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">
