# Environment & OOP Setup

## Vereisten

- PHP 8.1 of hoger
- Composer geïnstalleerd
- MySQL draaiend (bijv. via XAMPP of Docker)

---

## 1. Installatie

Clone of download het project. Voer daarna in de `Environment`-map het volgende uit:

```bash
composer install
```

Dit installeert alle packages die in `composer.json` staan, waaronder `vlucas/phpdotenv`.

---

## 2. .env aanmaken

Maak een `.env`-bestand aan in de root van het project (staat al in `.gitignore`, dus het komt nooit in git):

```env
DB_HOST=localhost
DB_USER=jouw_gebruikersnaam
DB_PASS=jouw_wachtwoord
DB_NAME=jouw_database
```

---

## 3. Database aanmaken

Maak in MySQL een database en tabel aan die overeenkomt met je `.env`:

```sql
CREATE DATABASE user_repo_opdracht;

USE user_repo_opdracht;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL
);

INSERT INTO users (username, email) VALUES ('peter', 'peter@example.com');
```

---

## 4. Uitvoeren

Open een terminal en navigeer naar de `Environment`-map:

```bash
cd pad/naar/Environment
```

Voer daarna het script uit:

```bash
php index.php
```

Als alles correct is ingesteld, zie je de `var_dump` van het `User`-object dat uit de database is opgehaald:

```
object(App\Domain\User)#4 (3) {
  ["id"]=>  int(1)
  ["username"]=>  string(5) "peter"
  ["email"]=>  string(19) "peter@example.com"
}
```

Als de gebruiker niet bestaat in de database, geeft `var_dump` `NULL` terug — dat is geen fout, maar betekent dat de query niets heeft gevonden.

Krijg je een foutmelding zoals `SQLSTATE[HY000] [1045] Access denied`? Controleer dan of de gegevens in `.env` overeenkomen met je MySQL-installatie.

---

## Concepten

### De Autoloader (Composer PSR-4)

Normaal moet je elk PHP-bestand handmatig inladen met `require_once`. Dat schaalt slecht zodra je veel klassen hebt.

Composer lost dit op via een **autoloader**. Je vertelt Composer waar jouw klassen staan door de `autoload`-sectie **zelf toe te voegen** aan `composer.json`:

```json
{
    "name": "mijn-project/naam",
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    },
    "require": {}
}
```

Dit zegt: *alles met namespace `App\...` is te vinden in de `src/`-map.*

Na het toevoegen van de `autoload`-sectie moet je eenmalig de autoloader genereren:

```bash
composer dump-autoload
```

Vanaf dat moment, door dit bovenaan `index.php` te zetten:

```php
require_once __DIR__ . '/vendor/autoload.php';
```

...laadt PHP automatisch de juiste bestanden zodra je een klasse gebruikt. Je hoeft nooit meer zelf `require_once` te schrijven voor je eigen klassen.

**Mappenstructuur volgt de namespace:**

| Namespace | Bestand |
|---|---|
| `App\Config\Database` | `src/Config/Database.php` |
| `App\Domain\User` | `src/Domain/User.php` |
| `App\Repositories\UserRepository` | `src/Repositories/UserRepository.php` |

---

### Namespaces

Een **namespace** is als een adres voor je klasse. Zonder namespaces zou je nooit twee klassen `User` kunnen hebben in hetzelfde project. Met namespaces zijn ze te onderscheiden: `App\Domain\User` vs `App\Admin\User`.

**Regels bij het aanmaken van een nieuwe klasse:**

1. De namespace moet overeenkomen met de mappenstructuur binnen `src/`
2. De bestandsnaam moet exact gelijk zijn aan de klassenaam (hoofdlettergevoelig)
3. Bovenaan elk bestand staat de namespace-declaratie

Voorbeeld: je maakt `src/Domain/Product.php` aan:

```php
<?php

namespace App\Domain;  // map: src/Domain/

class Product
{
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ){}
}
```

Wil je deze klasse ergens anders gebruiken, dan importeer je hem met `use`:

```php
<?php

use App\Domain\Product;  // importeer de klasse

$product = new Product(1, 'Laptop');
```

Zonder `use` zou je de volledige namespace elke keer moeten uitschrijven:

```php
$product = new \App\Domain\Product(1, 'Laptop');  // werkt, maar omslachtig
```

---

### `optimize-autoloader` en `composer dump-autoload`

PSR-4 autoloading werkt **altijd**, zonder extra configuratie. Wanneer PHP `new App\Domain\User` tegenkomt, vertaalt hij de namespace direct naar het bestandspad `src/Domain/User.php` en laadt het. Dat is puur conventie — er is geen vooraf gebouwd overzicht nodig.

De instelling `optimize-autoloader` voegt daar een **prestatiecache** bovenop toe. Je voegt hem zelf toe aan `composer.json`; hij staat er niet standaard in:

```json
{
    "name": "environment/variables",
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    },
    "require": {
        "vlucas/phpdotenv": "^5.6"
    },
    "config": {
        "optimize-autoloader": true
    }
}
```

Met deze instelling genereert Composer bij `composer install` of `composer dump-autoload` een **classmap** in `vendor/composer/autoload_classmap.php`: een statisch overzicht van alle bekende klassen. PHP kijkt daar eerst in (sneller), maar valt terug op PSR-4 als een klasse er niet in staat.

Dat betekent: **nieuwe klassen worden ook zonder `dump-autoload` gevonden**, zolang de namespace en mappenstructuur kloppen. De classmap is een optimalisatie, geen vereiste.

```bash
composer dump-autoload
```

Wanneer heeft het zin om dit handmatig uit te voeren?

| Situatie | Waarom |
|---|---|
| Na `composer.json` aanpassen | Namespace-mapping bijwerken |
| Vóór een productie-deploy | Classmap volledig opbouwen voor maximale snelheid |
| Na `autoload_classmap.php` leeggooien | Cache herstellen (zoals je net deed) |

---

### Mappenstructuur: `src/` vs de root

De **`src/`-map** bevat alleen klassen — geen uitvoerbare scripts. Dit houdt de code georganiseerd en zorgt dat de autoloader weet waar klassen staan.

**Uitvoerbare scripts** (bestanden die je direct aanroept met `php bestand.php`) staan in de **root** van het project, of in een aparte `public/`-map als het een webapplicatie is:

```
Environment/
├── src/                  ← alleen klassen (nooit direct uitvoeren)
│   ├── Config/
│   │   └── Database.php
│   ├── Domain/
│   │   └── User.php
│   └── Repositories/
│       └── UserRepository.php
├── vendor/               ← Composer packages (nooit aanpassen)
├── .env                  ← geheime configuratie (nooit in git)
├── composer.json
└── index.php             ← het script dat je uitvoert
```

---

### `__DIR__`

`__DIR__` is een **magische constante** in PHP die altijd het absolute pad geeft van de map waarin het huidige bestand staat.

```php
// stel: dit bestand staat op C:/xampp/htdocs/Environment/index.php
echo __DIR__;
// geeft: C:/xampp/htdocs/Environment
```

**Waarom is dit nodig?** Zonder `__DIR__` zijn paden relatief aan de map *vanwaar* je PHP aanroept, niet vanwaar het bestand staat. Dat geeft problemen zodra je een script vanuit een andere map uitvoert.

```php
// FOUT: werkt alleen als je PHP uitvoert vanuit de Environment-map
require_once 'vendor/autoload.php';

// GOED: werkt altijd, ongeacht vanwaar je het script aanroept
require_once __DIR__ . '/vendor/autoload.php';
```

Hetzelfde geldt voor de dotenv-loader. `Dotenv::createImmutable(__DIR__)` zegt: *zoek het `.env`-bestand in dezelfde map als dit script*.

```php
// index.php staat in Environment/
// __DIR__ = het pad naar Environment/
// Dotenv zoekt dus naar Environment/.env
$dotenv = Dotenv::createImmutable(__DIR__);
```

Als `index.php` in een `public/`-map zou staan en `.env` in de root, gebruik je:

```php
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');  // één map omhoog
```

---

### De Env Loader (phpdotenv)

Gevoelige gegevens zoals wachtwoorden horen **niet** hardgecodeerd in je code te staan. Zeker niet als je code op GitHub zet.

De oplossing: sla ze op in een `.env`-bestand en lees ze in via de library `vlucas/phpdotenv`.

```php
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
```

Na deze twee regels zijn alle waarden uit `.env` beschikbaar via `$_ENV`:

```php
$_ENV['DB_HOST']  // "localhost"
$_ENV['DB_USER']  // "peter"
```

`createImmutable` betekent dat de waarden na het inladen niet meer overschreven kunnen worden, wat voorkomt dat andere code per ongeluk je configuratie aanpast.

---

### Static methods en variabelen

Een gewone methode of variabele **hoort bij een object** — je moet eerst `new Database()` aanroepen.

Een `static` methode of variabele **hoort bij de klasse zelf**, niet bij een specifiek object. Je roept het aan met `ClassName::methodName()`.

```php
class Database
{
    public static PDO $pdo;           // static variabele: hoort bij de klasse

    public static function getInstance(): PDO  // static methode
    {
        if (!isset(self::$pdo)) {
            self::$pdo = new PDO(...);
        }
        return self::$pdo;
    }
}
```

Aanroepen doe je zo:

```php
$pdo = Database::getInstance();  // geen 'new' nodig
```

**`self::` vs `$this->`**

| | `self::` | `$this->` |
|---|---|---|
| Gebruik bij | `static` properties/methods | gewone (instantie) properties/methods |
| Wanneer | altijd beschikbaar | alleen binnen een object-instantie |

`self::$pdo` verwijst naar de statische variabele van de klasse zelf.  
`$this->pdo` zou verwijzen naar een variabele van *dit specifieke object* — maar dat bestaat hier niet, want de klasse wordt nooit met `new` aangemaakt.

**Waarom static hier?** Dit is het **Singleton-patroon**: je wil maar één database-verbinding in je hele applicatie. Elke keer dat je `Database::getInstance()` aanroept, krijg je dezelfde verbinding terug.

---

### Constructor en `$this`

De **constructor** (`__construct`) is een speciale methode die automatisch wordt aangeroepen zodra je een object aanmaakt met `new`.

```php
class UserRepository
{
    public function __construct(
        private readonly PDO $pdo
    ){}
}
```

```php
$repository = new UserRepository($pdo);  // $pdo wordt doorgegeven aan de constructor
```

---

#### Het verschil tussen een property en een lokale variabele

Dit is een veelgemaakte fout. Bekijk het verschil:

```php
class UserRepository
{
    public function __construct(
        private readonly PDO $pdo   // dit is een PROPERTY van de klasse
    ){}

    public function findByUsername(string $username): ?User
    {
        $stmt = $this->pdo->prepare("SELECT ...");  // gebruik de property via $this

        $result = $stmt->fetch();  // dit is een LOKALE variabele, alleen hier beschikbaar

        return $this->hydrate($result);
    }
}
```

**Een property** (`private readonly PDO $pdo`) is van het object. Hij wordt bewaard zolang het object bestaat en is beschikbaar in **alle methoden** van de klasse via `$this->pdo`.

**Een lokale variabele** (`$result`) bestaat alleen binnen de methode waar hij gedeclareerd is. Zodra de methode klaar is, verdwijnt hij.

---

#### Wat is `$this`?

`$this` is een verwijzing naar **het huidige object**. Stel je voor: je maakt twee repositories aan:

```php
$repo1 = new UserRepository($pdo1);
$repo2 = new UserRepository($pdo2);
```

Wanneer je `$repo1->findByUsername(...)` aanroept, wijst `$this` naar `$repo1` en zijn properties.  
Wanneer je `$repo2->findByUsername(...)` aanroept, wijst `$this` naar `$repo2`.

Elk object heeft zijn **eigen versie van de properties**. `$this` zegt altijd: *gebruik de properties van dit specifieke object*.

**Samengevat:**

```php
class Voorbeeld
{
    private string $naam;  // property: van het object, overal beschikbaar via $this

    public function __construct(string $naam)
    {
        $this->naam = $naam;  // $this->naam = property  |  $naam = lokale variabele (parameter)
    }

    public function begroet(): string
    {
        $begroeting = "Hallo, ";           // lokale variabele: alleen hier
        return $begroeting . $this->naam;  // property via $this
    }
}
```

| | Declaratie | Toegang | Levensduur |
|---|---|---|---|
| **Property** | `private string $naam` bovenin de klasse (of via constructor promotie) | `$this->naam` | Zolang het object bestaat |
| **Lokale variabele** | `$begroeting = "..."` in een methode | gewoon `$begroeting` | Alleen binnen die methode |
