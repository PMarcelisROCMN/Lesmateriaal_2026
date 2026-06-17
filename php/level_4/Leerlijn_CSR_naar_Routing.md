# Leerlijn: Van Repository naar volledige CSR + Routing

> Vervolg op `Opdracht_UserRepository.md` en `Opdracht_Environment.md`.
> Doel: studenten bouwen stap voor stap een schone applicatie-architectuur op,
> van een losse repository naar een volwaardig CSR-patroon met één entry point en een router.

---

## 1. Waar staan we, waar gaan we heen?

**Wat studenten al kunnen (vertrekpunt):**

- Een `.env` + Composer-autoloader opzetten (`Opdracht_Environment.md`)
- Een `Database`-klasse die één PDO-verbinding levert
- Een `UserRepository` die alle SQL afhandelt en rijen omzet naar `User`-objecten (`hydrate`)
- Het CSR-*concept* herkennen vanuit de cheatsheet (maar nog niet zelf gebouwd)

**Waar we heen gaan (eindbeeld):**

```
public/
└── index.php            ← het ENIGE entry point (front controller)
.htaccess                ← stuurt alle verzoeken naar public/index.php
src/
├── Domain/              ← datastructuren (User, Product, ...)
├── Repositories/        ← SQL: data lezen/schrijven
├── Services/            ← businesslogica: "mag dit? klopt dit?"
├── Controllers/         ← HTTP: input lezen, response/redirect terugsturen
├── Config/
│   └── Database.php     ← levert de ene PDO-verbinding
├── Routes/
│   └── web.php          ← koppelt URL's aan controllers
└── bootstrap.php        ← bouwt de lagen op (dependency injection)
```

Dit is precies de structuur die ervaren studenten "vanzelf" gaan bouwen in hun
project (zie het *food-delivery*-project) — alleen leren we het hier **bewust en schoon**,
in plaats van met static methods overal.

**De rode draad:** elke les lost één concreet probleem op dat in de vorige les ontstaat.
Dezelfde probleem-gedreven didactiek als in `Opdracht_Environment.md`
("Het werkt, maar het is lelijk" → probleem → oplossing).

---

## 2. Belangrijke ontwerpkeuzes (vooraf vastleggen)

Maak deze twee keuzes bewust en houd ze de hele leerlijn consistent — anders gaan
studenten mengen wat ze in verschillende lessen zien.

### Keuze A — Dependency Injection i.p.v. static methods

In `login_example` krijgt elke laag zijn afhankelijkheid via de **constructor**:

```php
$pdo            = Database::getInstance();
$userRepository = new UserRepository($pdo);
$authService    = new AuthService($userRepository);
$authController = new AuthController($authService);
```

In het *food-delivery*-studentproject staat juist overal `UserRepository::create(...)`
en `Database::getPdo()` (static). Dat werkt, maar:

- je kunt klassen niet los testen of vervangen
- afhankelijkheden zijn onzichtbaar (verstopt in de methode)
- het breekt het CSR-verhaal dat we net hebben opgebouwd

> **Aanbeveling:** houd `Database` als singleton (één PDO-bron), maar gebruik voor
> **alle** repositories, services en controllers **constructor-injectie**. Dat sluit
> naadloos aan op `login_example` en de cheatsheet. De static-aanpak benoem je in
> les 5 expliciet als "wat er gebeurt als je géén bekabeling hebt".

### Keuze B — Router: kant-en-klaar vs. zelf bouwen

- **`bramus/router`** (zoals in het studentproject): snel, weinig afleiding, focus op
  controllers. `composer require bramus/router`. **Aanbevolen als hoofdroute.**
- **Zelf een mini-router schrijven** (~30 regels): leerzamer over hoe routing *werkt*,
  maar kost een hele les. Geschikt als verdieping/keuzeopdracht voor de snelle studenten.

> **Aanbeveling:** hoofdlijn met `bramus/router`; bied "bouw je eigen router" aan als
> verdiepingsbijlage (zie les 4, extra).

---

## 3. Lesoverzicht

| Les | Titel | Kernprobleem dat we oplossen | Eindresultaat |
|-----|-------|------------------------------|---------------|
| 1 | De Service-laag | Businesslogica zit in je pagina/repository | `AuthService` met validatie + hashing |
| 2 | De Controller-laag | HTTP-afhandeling zit door je logica heen | Volledige CSR-keten = `login_example` |
| 3 | Eén entry point | Tien losse `.php`-pagina's, overal dezelfde bootstrap | `.htaccess` + front controller |
| 4 | De Router | Handmatige `if`-en op URL's is onhoudbaar | `bramus/router` met GET/POST-routes |
| 5 | Bekabeling & guards | Controllers handmatig `new`-en in elke route | `bootstrap.php` als mini-container + auth-guards |
| 6 | Capstone: tweede resource | Kun je het patroon zelfstandig herhalen? | Volledige CSR-slice voor `Product` |

Tempo: ongeveer één les per onderdeel (les 4–5 mogen uitlopen). Les 6 is een
zelfstandige integratie-opdracht.

---

## 4. De lessen in detail

### Les 1 — De Service-laag

**Vertrekpunt:** de werkende `UserRepository` uit de vorige opdracht.

**Probleem dat we tonen:** waar zet je de regel "wachtwoord moet minstens 8 tekens zijn"?
En waar hash je het wachtwoord? Als dat in de repository komt, gaat de repository
ineens *beslissen* in plaats van alleen opslaan. Als het in de pagina komt, staat
dezelfde regel straks op vijf plekken.

**Concept:** de **service** is de laag voor businesslogica.
- "Mag dit?" (validatie van regels, niet van invoervelden)
- "Klopt dit?" (wachtwoord verifiëren)
- Bewerkingen die geen SQL en geen HTTP zijn (hashing, een token genereren)

**Praktijk** (volgt `login_example/src/Services/AuthService.php`):
- `AuthService` krijgt de `UserRepository` via de constructor
- `register()`: lengte checken → bestaat gebruiker al? → hashen → `repository->insert(...)`
- `login()`: gebruiker ophalen → `password_verify()` → bij fout een `RuntimeException` gooien
- Belangrijk principe: **nooit zeggen welk veld fout is** ("Gebruikersnaam óf wachtwoord onjuist")

**Test:** voorlopig nog vanuit een kaal `index.php` dat de service rechtstreeks aanroept.
We hebben nog geen controller — dat is precies de cliffhanger naar les 2.

**Te benoemen:** de service kent geen `$_POST` en geen `echo`. Geef je dat per ongeluk mee,
dan zit HTTP in je service. Dat is les 2.

---

### Les 2 — De Controller-laag

**Probleem dat we tonen:** je `index.php` doet nu te veel tegelijk: hij leest `$_POST`,
roept de service aan, vangt fouten op én toont HTML. Dat is geen "verkeer dirigeren" meer.

**Concept:** de **controller** is de enige laag die HTTP kent.
- Leest de invoer (`$_POST`, `$_GET`)
- Valideert *de invoer* (zijn de velden aanwezig?) — niet de businessregels
- Roept de service aan
- Stuurt een response terug (redirect, view, of foutmelding)

**Het verschil controller-validatie vs. service-validatie expliciet maken:**

| Vraag | Laag |
|-------|------|
| "Is het veld `username` ingevuld?" | Controller (invoer) |
| "Bestaat deze gebruikersnaam al?" | Service (businessregel) |
| "Hoe sla ik die gebruiker op?" | Repository (data) |

**Praktijk** (volgt `login_example/src/Controllers/AuthController.php`):
- `AuthController` krijgt de `AuthService` via de constructor
- `register()` / `login()`: invoer checken → service aanroepen → resultaat teruggeven
- `index.php` wordt nu kaal: **alleen bekabeling + dispatch**

**Eindresultaat:** dit is exact `login_example`. De volledige CSR-keten staat:

```php
$pdo            = Database::getInstance();
$userRepository = new UserRepository($pdo);
$authService    = new AuthService($userRepository);
$authController = new AuthController($authService);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $authController->login($_POST['username'], $_POST['password']);
    } catch (RuntimeException $e) {
        echo $e->getMessage();
    }
}
```

**Reflectie:** laat het lagendiagram uit de cheatsheet terugkomen en laat studenten
voor hun eigen code aanwijzen: welke regel hoort bij welke laag? Waar praat een laag
met een niet-buur? (Dat mag niet.)

> Op dit punt **beheersen studenten CSR voor één resource**. Les 3–5 gaan over
> *opschalen*: hoe houd je dit netjes als er tien pagina's en meerdere resources komen?

---

### Les 3 — Eén entry point (front controller)

**Probleem dat we tonen:** in een echt project heb je `login.php`, `register.php`,
`menu.php`, `cart.php`, ... Elke pagina herhaalt dezelfde opstart (`autoload`, `.env`,
sessie starten, PDO). En als je wilt afdwingen "alleen ingelogd", moet je dat op elke
pagina los regelen. Eén vergeten = lek.

**Concept:** de **front controller** — alle verzoeken gaan door één bestand,
`public/index.php`. Dat bestand start de applicatie één keer op en bepaalt daarna
wat er moet gebeuren.

**Praktijk:**
- Alles wat publiek bereikbaar is, gaat naar `public/`
- Eén `.htaccess` die elk verzoek naar `public/index.php` stuurt:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

- Uitleg: bestaat het bestand echt (een css/afbeelding)? Dan serveer dat. Anders →
  `index.php` laat beslissen.
- `public/index.php` laadt de autoloader, `.env` en start de sessie — **één keer, op één plek**.

**Te benoemen:** waarom staat `index.php` in `public/` en de `src/` daarbuiten?
Zodat de buitenwereld nooit rechtstreeks bij je broncode, `.env` of `vendor/` kan.

> **Draaien onder XAMPP (in een submap van `htdocs`)**
>
> Onze studenten zetten elk project in een eigen map onder `htdocs` en draaien
> dus op een **submap-URL**, bijvoorbeeld `http://localhost/mijnproject/`.
> Dat geeft één valkuil: links als `<a href="/login">` wijzen naar de **webroot**
> (`localhost/login`) in plaats van naar de submap. Op een submap kloppen ze niet.
>
> **Oplossing — alle links via een `url()`-helper** die het submap-pad ervoor plakt.
> `bramus/router` (les 4) detecteert dat pad zelf met `getBasePath()`; je vraagt het
> één keer op (`BASE_PATH`) en gebruikt het overal. Zo werkt hetzelfde project zowel
> op een submap als op de webroot, zonder iets aan te passen.
>
> **De `public/`-map: ideaal vs. praktijk.** Netjes is een aparte `public/`-map: dan
> staat alleen `index.php` + assets in de webroot en blijven `src/`, `.env` en
> `vendor/` daarbuiten. Nadeel in de XAMPP-workflow: je draait dan op
> `…/mijnproject/public/` — dat extra `/public/` verwart studenten. Daarom gebruikt het
> referentieproject `routing_example/` de **platte variant**: de front controller staat
> in de projectroot (korte URL `…/routing_example/`), en `src/`, `database/`, `vendor/`
> en `.env` worden afgeschermd met een paar regels in `.htaccess` (`[F]` = 403). Bespreek
> beide met studenten; de afweging staat in `Bouwlog_CSR_Routing.md`.

**Tussenstand:** we hebben één ingang, maar bepalen "welke pagina" nog met een lompe
`if ($_SERVER['REQUEST_URI'] === '/login')`. Dat is de opmaat naar les 4.

---

### Les 4 — De Router

**Probleem dat we tonen:** met een handvol `if`/`switch` op de URL wordt `index.php`
al snel een onleesbare brij. En je moet zelf GET van POST onderscheiden, parameters
uit de URL plukken, 404's afhandelen...

**Concept:** een **router** koppelt een URL + HTTP-methode aan een stukje code.

**Praktijk** (sluit aan op het studentproject):
- `composer require bramus/router` — herhaalt het patroon van `composer require` uit
  `Opdracht_Environment.md` (vertrouwd terrein, nieuw pakket)
- In `public/index.php`:

```php
$router = new \Bramus\Router\Router();
require __DIR__ . '/../src/Routes/web.php';
$router->run();
```

- In `src/Routes/web.php` de routes definiëren:

```php
$router->get('/login', function () {
    require __DIR__ . '/../Views/Pages/login.php';
});

$router->post('/login', function () {
    // hier komt straks de controller (les 5)
});

$router->get('/menu', function () {
    require __DIR__ . '/../Views/Pages/menu.php';
});
```

- GET vs POST: een formulier dat ophaalt (login-pagina tonen) is GET; het formulier
  versturen is POST. Zelfde URL, ander gedrag.

**Extra / verdieping (Keuze B):** "Bouw je eigen mini-router" — een klein klasje met
een array van routes en een `match()` op `$_SERVER['REQUEST_URI']` +
`$_SERVER['REQUEST_METHOD']`. Maakt de magie van `bramus/router` ontmythologiseerd.
Aanbieden aan studenten die snel klaar zijn.

---

### Les 5 — Bekabeling & guards (de kern)

**Probleem dat we tonen:** in elke route opnieuw dit schrijven is onwerkbaar:

```php
$router->post('/login', function () {
    $pdo  = Database::getInstance();
    $repo = new UserRepository($pdo);
    $svc  = new AuthService($repo);
    $ctrl = new AuthController($svc);
    $ctrl->login($_POST['username'], $_POST['password']);
});
```

Dit is ook precies waar studenten "for the lulz" naar static methods grijpen
(`UserRepository::create(...)`) om het korter te maken — en hun nette CSR daarmee slopen.

**Concept:** bouw de lagen **één keer** op in `bootstrap.php` (een simpele,
handgemaakte container) en gebruik de kant-en-klare controllers in de routes.

**Praktijk:**
- `src/bootstrap.php` start de sessie, haalt de PDO op en bouwt de objectgraaf:

```php
$pdo = Database::getInstance();

$userRepository = new UserRepository($pdo);
$authService    = new AuthService($userRepository);
$authController = new AuthController($authService);
// later: $productController = ...
```

- Routes worden kaal en leesbaar:

```php
$router->post('/login', fn() => $authController->login(
    $_POST['username'] ?? '',
    $_POST['password'] ?? ''
));
```

**Guards (auth-checks) netjes regelen** — zoals in het studentproject, maar uit één bron:

```php
$router->get('/account', function () use ($authController) {
    $authController->requireLogin();   // gooit/redirect bij geen sessie
    // toon account
});
```

- Bespreek `AuthOnly` / `GuestOnly` / `isAdmin` als herbruikbare guards
- Benoem: de guard hoort logisch bij de auth-laag, niet los rondslingerend in `web.php`

**Reflectie:** vergelijk de route van les 4 (alles handmatig `new`) met nu. Wie is
waar verantwoordelijk voor? Waarom is static "korter maar slechter"?

---

### Les 6 — Capstone: een tweede resource zelfstandig

**Doel:** kan de student het patroon **herhalen zonder voorbeeld**? Herhaling is waar
het patroon beklijft.

**Opdracht:** bouw een complete CSR-slice voor een tweede entiteit, bijvoorbeeld `Product`:

1. `Domain/Product.php` — datastructuur (promotion + readonly)
2. `Repositories/ProductRepository.php` — `findAll()`, `findById()`, `insert()`, `delete()` + `hydrate()`
3. `Services/ProductService.php` — businessregels (bijv. prijs > 0, naam niet leeg)
4. `Controllers/ProductController.php` — invoer lezen, service aanroepen, view tonen
5. Bekabeling toevoegen in `bootstrap.php`
6. Routes toevoegen in `web.php` (overzicht = GET, toevoegen = POST, verwijderen = POST)

**Beoordelingscriteria (rubric-startpunt):**

| Criterium | Onvoldoende | Voldoende | Goed |
|-----------|-------------|-----------|------|
| Laagscheiding | SQL/HTTP door elkaar | elke laag eigen taak | laag praat alleen met buur |
| Dependency injection | static methods | constructor-injectie | bekabeld in bootstrap |
| Routing | losse pagina's | routes via router | GET/POST correct gescheiden |
| Foutafhandeling | fouten genegeerd | `RuntimeException` gebruikt | nette nette melding naar gebruiker |

**Afsluitende reflectie:** laat de student `public/index.php` van les 1 naast die van
nu leggen. Wat weet `index.php` nu nóg van? (Niets — alleen opstarten en de router laten lopen.)

---

## 5. Aansluiting op bestaand materiaal

- **Cheatsheet uitbreiden:** `Cheatsheet_CSR_Repository.md` dekt al CSR, repository,
  hydrate en DI. Voeg twee secties toe: **(a) Front controller + .htaccess**, **(b) Router
  (route → controllermethode)**. Dan blijft de cheatsheet het naslagwerk voor de hele leerlijn.
- **Per les een opdracht-`.md`** in dezelfde stijl als `Opdracht_Environment.md`
  (probleem → oplossing → code → "ververs de browser"). Die kun je met
  `Opdrachten/_make_pdfs.py` naar PDF omzetten, net als de bestaande opdrachten.
- **`login_example/` blijft het referentieproject** voor les 1–2 (CSR zonder router).
  Overweeg een tweede referentieproject `routing_example/` dat les 3–5 afdekt
  (front controller + bramus/router + bootstrap-bekabeling), zodat studenten een schoon
  ijkpunt hebben naast hun eigen werk.

## 6. Suggestie voor benodigde nieuwe bestanden

| Bestand | Voor les | Status |
|---------|----------|--------|
| `Opdracht_Service.md` | 1 | nieuw |
| `Opdracht_Controller.md` | 2 | nieuw |
| `Opdracht_FrontController.md` | 3 | nieuw |
| `Opdracht_Router.md` | 4 | nieuw |
| `Opdracht_Bekabeling_en_Guards.md` | 5 | nieuw |
| `Opdracht_Capstone_Product.md` | 6 | nieuw |
| `Cheatsheet_CSR_Repository.md` (uitbreiden) | 3–4 | bestaand |
| `routing_example/` (referentieproject) | 3–5 | **gebouwd** — evenementensite met admin-CRUD op SQLite |
