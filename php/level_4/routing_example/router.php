<?php

// dev-server router, alleen voor: php -S localhost:8000 router.php
// (onder XAMPP regelt .htaccess dit)

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// broncode en instellingen afschermen
if (preg_match('#^/(src|vendor|database)/#', $path) || $path === '/.env') {
    http_response_code(403);
    return true;
}

// bestaand bestand (css, afbeelding) direct serveren
if ($path !== '/' && file_exists(__DIR__ . $path) && !is_dir(__DIR__ . $path)) {
    return false;
}

require __DIR__ . '/index.php';
