# Referentieproject: Evenementensite (CSR + Routing, SQLite)

Een klein, compleet voorbeeldproject dat de architectuur uit
`../Leerlijn_CSR_naar_Routing.md` laat zien — bewust zo simpel mogelijk gehouden,
maar wél met de echte gereedschappen die je ook in een groot project gebruikt:

- **C**ontroller – **S**ervice – **R**epository
- één entry point (front controller) + **bramus/router**
- instellingen in een **`.env`** (via **phpdotenv**)
- inloggen als admin (sessie)
- **CRUD** op evenementen: aanmaken, wijzigen, verwijderen
- **SQLite**: de database is één bestand, geen server nodig

Bezoekers zien een agenda. Een ingelogde admin kan evenementen beheren.

## Gebruikte packages

| Package | Waarvoor |
|---------|----------|
| `bramus/router`     | koppelt URL's aan controllers |
| `vlucas/phpdotenv`  | leest instellingen uit `.env` |

## Starten

### Met XAMPP (zoals je gewend bent: een map onder `htdocs`)

1. Zet de map `routing_example` in je XAMPP `htdocs` (of geef 'm een eigen naam).
2. Open een terminal in die map en draai eenmalig:

   ```bash
   composer install            # haalt de packages binnen (vendor/)
   copy .env.example .env       # je eigen instellingen (mac/linux: cp)
   php database/setup.php       # maakt de database + voorbeelddata
   ```

3. Start Apache in XAMPP en ga in de browser naar:

   ```
   http://localhost/routing_example/
   ```

Dat het project in een **submap** draait is geen probleem: de router en alle links
rekenen automatisch met dat pad. Noem je de map anders, dan werkt het net zo goed —
je hoeft niets aan te passen.

> **Veiligheid:** omdat het project plat in de map staat, zijn `src/`, `vendor/`,
> `database/` en `.env` in principe via de browser bereikbaar. De `.htaccess`
> blokkeert dat (geeft een 403). Dat is wat een aparte `public/`-map normaal voor je
> doet — zie de leerlijn voor die nettere variant.

### Alternatief: de ingebouwde PHP-server

Zonder XAMPP, vanuit de projectmap:

```bash
composer install
copy .env.example .env
php database/setup.php
php -S localhost:8000 router.php
```

Open daarna **http://localhost:8000**.

## Inloggen als admin

| Gebruikersnaam | Wachtwoord |
|----------------|------------|
| `admin`        | `admin123` |

Klik rechtsboven op **Inloggen** en daarna op **Beheer**.

## Hoe een verzoek door het project loopt

```
Browser
  -> index.php            (front controller: alles begint hier)
  -> src/Routes/web.php    (bramus/router kiest de juiste actie)
  -> Controller            (leest input, kiest een view)
  -> Service               (businesslogica: klopt de invoer?)
  -> Repository            (SQL: lezen/schrijven in SQLite)
  -> Domain\Event          (de data als net object)
  -> View                  (HTML terug naar de browser)
```

Alle lagen worden op één plek aan elkaar geknoopt: `src/bootstrap.php`
(dat laadt ook de `.env`).

## Mappenstructuur

```
routing_example/
├── index.php                 ← front controller (enig startpunt)
├── router.php                ← alleen voor de PHP-dev-server
├── .htaccess                 ← routing + bescherming voor Apache/XAMPP
├── .env.example              ← voorbeeld-instellingen (kopieer naar .env)
├── composer.json
├── assets/css/style.css
├── database/
│   ├── setup.php              ← run je één keer: maakt tabellen + voorbeelddata
│   ├── schema.sql             ← de tabellen ter referentie
│   └── database.sqlite        ← wordt aangemaakt door setup.php (niet in git)
├── src/
│   ├── helpers.php            ← e(), url(), redirect(), view()
│   ├── bootstrap.php          ← laadt .env + bouwt alle lagen op
│   ├── Config/Database.php     ← levert de SQLite-verbinding (pad uit .env)
│   ├── Domain/Event.php        ← datastructuur
│   ├── Repositories/           ← SQL
│   ├── Services/               ← businesslogica
│   ├── Controllers/            ← verzoeken verwerken
│   ├── Routes/web.php          ← URL -> controller
│   └── Views/                  ← HTML-templates
└── extra/
    └── EigenRouter.php         ← verdieping: zo bouw je zelf een router
```

## Routes

| Methode | URL | Actie | Toegang |
|---------|-----|-------|---------|
| GET  | `/`                          | agenda met aankomende evenementen | iedereen |
| GET  | `/events/{id}`               | detailpagina                      | iedereen |
| GET  | `/login`                     | loginformulier                    | iedereen |
| POST | `/login`                     | inloggen                          | iedereen |
| POST | `/logout`                    | uitloggen                         | iedereen |
| GET  | `/admin/events`              | overzicht beheer                  | admin    |
| GET  | `/admin/events/create`       | formulier nieuw                   | admin    |
| POST | `/admin/events`              | opslaan nieuw                     | admin    |
| GET  | `/admin/events/{id}/edit`    | formulier wijzigen                | admin    |
| POST | `/admin/events/{id}`         | wijziging opslaan                 | admin    |
| POST | `/admin/events/{id}/delete`  | verwijderen                       | admin    |
