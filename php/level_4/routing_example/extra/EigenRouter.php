<?php
declare(strict_types=1);

/*
 * ───────────────────────────────────────────────────────────────────────────
 *  VERDIEPING — niet gebruikt door de applicatie.
 * ───────────────────────────────────────────────────────────────────────────
 *  Dit project gebruikt bramus/router (een Composer-package). Maar zo'n router
 *  is geen magie. Dit bestand laat zien hoe je er zelf een kunt bouwen in ~30
 *  regels: een lijst routes, en bij elk verzoek kijken welke past.
 *
 *  Hoort bij de verdiepingsopdracht "bouw je eigen router" uit Les 4 van
 *  Leerlijn_CSR_naar_Routing.md. Wil je 'm echt gebruiken? Vervang dan in
 *  index.php de bramus-router door deze klasse.
 * ───────────────────────────────────────────────────────────────────────────
 */

namespace Extra;

class EigenRouter
{
    /** @var array<int, array{method: string, pattern: string, handler: callable}> */
    private array $routes = [];

    public function get(string $pattern, callable $handler): void
    {
        $this->routes[] = ['method' => 'GET', 'pattern' => $pattern, 'handler' => $handler];
    }

    public function post(string $pattern, callable $handler): void
    {
        $this->routes[] = ['method' => 'POST', 'pattern' => $pattern, 'handler' => $handler];
    }

    public function run(string $method, string $uri): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            // Zet '/events/{id}' om naar een regex en kijk of de URL erop past.
            $regex = '#^' . preg_replace('#\{[a-z]+\}#', '([^/]+)', $route['pattern']) . '$#';

            if (preg_match($regex, $uri, $matches)) {
                $params = array_slice($matches, 1); // de gevangen stukjes, bv. de id
                ($route['handler'])(...$params);
                return;
            }
        }

        http_response_code(404);
        echo '404 — Pagina niet gevonden';
    }
}
