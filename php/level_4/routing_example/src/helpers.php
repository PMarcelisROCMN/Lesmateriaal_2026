<?php
declare(strict_types=1);

// maakt tekst veilig voor in html (voorkomt xss)
function e(?string $text): string
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

// bouwt een link op die ook in een submap klopt (zie BASE_PATH in index.php)
function url(string $path = '/'): string
{
    $base = defined('BASE_PATH') ? BASE_PATH : '';

    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

// stuurt de browser door en stopt het script
function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

/** @param array<string, mixed> $data */
function view(string $name, array $data = []): void
{
    extract($data);
    require __DIR__ . '/Views/' . $name . '.php';
}
