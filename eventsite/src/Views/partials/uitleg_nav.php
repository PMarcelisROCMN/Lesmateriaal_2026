<?php

/**
 * Snelnavigatie tussen de uitleg-onderwerpen.
 * Zet $huidig op de slug van de pagina zelf, dan wordt die vet i.p.v. een link.
 */

/** @var string $huidig */
$huidig = $huidig ?? '';

$onderwerpen = [
    'router'     => 'Router',
    'controller' => 'Controller',
    'service'    => 'Service',
    'repository' => 'Repository',
    'domain'     => 'Domain',
    'view'       => 'View',
];
?>
<nav class="topic-nav">
    <span class="muted">Onderdelen:</span>
    <?php foreach ($onderwerpen as $slug => $label): ?>
        <?php if ($slug === $huidig): ?>
            <strong><?= $label ?></strong>
        <?php else: ?>
            <a href="<?= BASE_URL ?>uitleg/<?= $slug ?>"><?= $label ?></a>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>
