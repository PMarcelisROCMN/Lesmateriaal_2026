# Bouwlog — CSR + Routing leerlijn & referentieproject

> Werkdocument dat bijhoudt **wat** we bouwen, **waarom** we bepaalde keuzes maken
> en **wat de status** is. Bedoeld voor de docent (niet voor studenten).
> Laatst bijgewerkt: 2026-06-17.

---

## Doel

Studenten die het **repository-patroon** al beheersen verder brengen naar een
volledige, professionele opzet: **CSR** (Controller–Service–Repository) +
**routing**, met env-loader en autoloader. Eindbeeld is geïnspireerd op een echt
studentproject (`l4-pro-2-food-delivery-express-hampter`, gebruikt `bramus/router`
+ `phpdotenv`), maar dan **bewust en schoon** opgebouwd in plaats van met static
methods overal.

---

## Artefacten (in `php/level_4/`)

| Bestand / map | Wat | Status |
|---|---|---|
| `Leerlijn_CSR_naar_Routing.md` | Lesplan in 6 lessen (Service → Controller → front controller → router → bekabeling/guards → capstone) | ✅ |
| `routing_example/` | Werkend referentieproject: evenementensite met admin-CRUD op SQLite | ✅ getest |
| `login_example/` | Bestaand: CSR met AuthService/AuthController, nog zonder router | bestaand |
| `Opdracht_UserRepository.md` | Bestaand: repository-patroon | bestaand |
| `Opdracht_Environment.md` | Bestaand: `.env` + phpdotenv + autoloader + Database-singleton | bestaand |
| `Cheatsheet_CSR_Repository.md` | Bestaand naslagwerk CSR | bestaand |
| `Bouwlog_CSR_Routing.md` | Dit document | ✅ |

---

## Het referentieproject `routing_example/`

Een evenementensite: bezoekers zien een agenda, een ingelogde admin kan
evenementen aanmaken/wijzigen/verwijderen.

**Stack:** PHP 8.x · SQLite · `bramus/router` · `vlucas/phpdotenv` · Composer PSR-4.

**Lagen (CSR):**
```
public/index.php  →  Routes/web.php  →  Controller  →  Service  →  Repository  →  Domain\Event  →  View
                     (bramus/router)    (HTTP)         (logica)    (SQL)
```
Bekabeling op één plek: `src/bootstrap.php` (laadt ook `.env`).

**Getest (werkt):** agenda, detailpagina, login + sessie, admin-guard (redirect naar
`/login`), aanmaken, validatie, wijzigen, verwijderen, 404, en draaien in een submap.

---

## Beslissingenlog

Elke keuze met de reden erbij, zodat we later weten *waarom*.

1. **SQLite i.p.v. MySQL** — de database is één bestand, geen server/credentials nodig.
   Studenten kunnen het project meteen draaien. Overstap naar MySQL is later een
   configuratiewijziging (zie `.env`), geen herstructurering.

2. **Volledig CSR behouden, maar implementatie zo simpel mogelijk** — op verzoek
   (MBO niveau 4). Weggehaald: first-class callables, closures in views,
   handmatige base-path-logica. Gewone `foreach`, platte structuur, één plek voor
   bekabeling. De CSR-lagen zelf blijven — dát is de leerstof.

3. **Dependency injection i.p.v. static methods** — elke laag krijgt zijn
   afhankelijkheid via de constructor (zoals in `login_example`). Het studentproject
   gebruikt overal static (`UserRepository::create`), wat het CSR-verhaal sloopt en
   niet te testen/vervangen is.

4. **`bramus/router` (Composer-package) i.p.v. zelfbouw-router** — matcht de leerlijn
   (Les 4) én het studentproject. De zelfbouw-router leeft voort als verdiepings­
   materiaal in `extra/EigenRouter.php` ("zo werkt routing onder de motorkap").

5. **`.env` via `vlucas/phpdotenv`** — instellingen (nu het SQLite-pad) buiten de code.
   Sluit aan op `Opdracht_Environment.md` en maakt de stap naar MySQL-credentials
   straks logisch. `.env` staat in `.gitignore`; `.env.example` is wél in git.

6. **Inloggen simpel gehouden** — `AuthService::attempt()` geeft `true`/`false` terug
   (geen exceptions). De guard is één regel bovenin elke admin-actie:
   `$this->auth->requireLogin()`. Geen guard-closures in de routes.

7. **Draaien in een submap van `htdocs` (XAMPP, geen vhost)** — bramus detecteert het
   submap-pad zelf (`getBasePath()`); een kleine `url()`-helper zet datzelfde pad voor
   alle links en assets. Zo werkt het project zowel onder
   `http://localhost/routing_example/` als op de webroot (`php -S`), zonder iets aan
   te passen.

8. **Platte structuur i.p.v. een `public/`-map** (keuze docent, 2026-06-17). Reden:
   onze studenten draaien onder XAMPP in een submap; met een `public/`-map zou de URL
   `…/routing_example/public/` worden, wat verwart. Daarom staat de front controller in
   de projectroot, zodat de URL `…/routing_example/` is. Prijs: `src/`, `database/`,
   `vendor/` en `.env` staan onder de webroot — die worden afgeschermd met `[F]`-regels
   (403) in `.htaccess`. Getest: alle vier paden geven 403. De nette `public/`-variant
   blijft als concept in de leerlijn staan.

---

## Hoe draaien (huidige opzet)

Map in XAMPP `htdocs`, dan eenmalig in een terminal in die map:
```bash
composer install
copy .env.example .env
php database/setup.php
```
Apache starten en in de browser:
```
http://localhost/routing_example/
```
Admin-login: `admin` / `admin123`. (Zonder XAMPP: `php -S localhost:8000 router.php`.)

---

## Openstaande punten / TODO

- [x] **Keuze: URL met of zonder `/public/`.** → **Gekozen: plat** (beslissing 8).
      URL is `…/routing_example/`; broncode afgeschermd via `.htaccess`.
- [ ] Leerlijn per les koppelen aan concrete bestanden in `routing_example/`
      (welk bestand hoort bij welke les → leesvolgorde voor studenten).
- [ ] Per les een opdracht-`.md` schrijven (zie tabel in de leerlijn).
- [ ] Cheatsheet uitbreiden met front controller + router.
