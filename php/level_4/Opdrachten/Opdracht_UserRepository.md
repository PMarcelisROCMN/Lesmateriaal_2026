# Opdracht: UserRepository

## Wat ga je bouwen?

Je bouwt een `UserRepository`-klasse die alle databasecommunicatie rondom gebruikers afhandelt. De rest van je applicatie schrijft nooit zelf SQL: die vraagt aan de repository "geef me gebruiker X" en krijgt een netjes `User`-object terug.

Aan het einde kun je:

- een klasse instantiëren met een databaseverbinding via de constructor
- private properties gebruiken om implementatiedetails te verbergen
- prepared statements uitvoeren via een klasse
- een ruwe database-rij omzetten naar een object (hydrate)

---

## Benodigdheden

Voer het volgende SQL-script uit in phpMyAdmin of je terminal:

```sql
CREATE DATABASE IF NOT EXISTS user_repo_opdracht
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE user_repo_opdracht;

CREATE TABLE IF NOT EXISTS users (
    id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)  NOT NULL UNIQUE,
    email    VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
```

---

## Projectstructuur

Maak de volgende mappenstructuur aan:

```
user_repo_opdracht/
├── composer.json
├── public/
│   └── index.php
└── src/
    ├── Domain/
    │   └── User.php
    └── Repositories/
        └── UserRepository.php
```

Maak een `composer.json` aan voor autoloading:

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}
```

Voer daarna in de terminal uit:

```
composer dump-autoload
```

---

## Stap 1: De User-klasse

Maak `src/Domain/User.php` aan.

De `User`-klasse is een pure datastructuur: hij bevat alleen data, geen SQL en geen logica. Dit soort klassen noem je een **domeinklasse**.

Zet bovenaan het bestand:

```php
<?php
declare(strict_types=1);

namespace App\Domain;
```

De klasse heeft drie readonly properties: `$id` (int), `$username` (string) en `$email` (string).

Gebruik **constructor property promotion**: schrijf de properties direct als parameters van de constructor, met hun access modifier erbij. Je hoeft ze dan niet apart te declareren.

```php
class User
{
    public function __construct(
        public readonly int $id,
        public readonly string $username,
        public readonly string $email,
    ) {}
}
```

`readonly` zorgt ervoor dat de waarde na aanmaken niet meer gewijzigd kan worden. Dat is precies wat je wilt: een gebruiker die uit de database komt verandert niet.

---

## Stap 2: De UserRepository-klasse aanmaken

Maak `src/Repositories/UserRepository.php` aan.

Zet bovenaan:

```php
<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Domain\User;
use PDO;
```

Maak daarna een lege klasse aan:

```php
class UserRepository
{
}
```

### Constructor

De `UserRepository` heeft een databaseverbinding nodig. Voeg een constructor toe die een `PDO`-object accepteert en opslaat in een **private** property.

Gebruik constructor property promotion:

```php
public function __construct(private readonly PDO $pdo) {}
```

**Waarom `private`?** De PDO-verbinding is een intern detail van de repository. Andere klassen hoeven niet te weten hoe de verbinding eruitziet of hoe die tot stand is gekomen. Door hem `private` te maken, sluit je dat uit.

---

## Stap 3: findByUsername

Voeg een methode `findByUsername` toe die zoekt op gebruikersnaam.

```php
public function findByUsername(string $username): ?array
```

De `?array` return type betekent: de methode geeft een `array` terug, of `null` als er niets gevonden is.

De methode doet het volgende:
1. Maak een prepared statement aan via `$this->pdo->prepare()`.
2. Voer het statement uit met `$username` als parameter.
3. Haal de rij op via `fetch()`.
4. Als er een rij is: geef een array terug met twee sleutels:
   - `'user'`: een `User`-object
   - `'password'`: de wachtwoordhash uit de database
5. Als er geen rij is: geef `null` terug.

**Waarom geef je de wachtwoordhash apart mee?** Het `User`-object zelf bevat nooit een wachtwoord. Maar de code die het inloggen afhandelt heeft de hash nodig om te vergelijken met wat de gebruiker intypt. Door de hash als apart veld mee te geven, blijft het `User`-object schoon.

> Maak nu eerst het `User`-object direct aan in deze methode. In stap 5 verplaats je dat naar een aparte methode.

---

## Stap 4: register

Voeg een methode `register` toe die een nieuwe gebruiker opslaat.

```php
public function register(string $username, string $email, string $passwordHash): User
```

De methode doet het volgende:
1. Maak een prepared statement aan voor een INSERT.
2. Voer het statement uit met username, email en wachtwoordhash.
3. Haal het nieuwe ID op via `$this->pdo->lastInsertId()`. Cast dit naar `int`.
4. Geef een `new User(...)` terug met het nieuwe ID, de username en het email.

**Let op:** het hashen van het wachtwoord doe je **niet** hier. De repository slaat alleen op. Hashing is businesslogica en hoort thuis in een service.

---

## Stap 5: hydrate

Je maakt nu op meerdere plekken een `User`-object aan vanuit een database-rij. Als je straks ook `findByEmail` of `findById` toevoegt, kopieer je steeds dezelfde code.

Maak een **private** methode `hydrate` die een database-rij omzet naar een `User`-object:

```php
private function hydrate(array $row): User
```

De methode maakt een `new User(...)` aan met de waardes uit `$row`. Gebruik named arguments voor de leesbaarheid:

```php
return new User(
    id: (int) $row['id'],
    username: $row['username'],
    email: $row['email'],
);
```

Vervang daarna in `findByUsername` het directe aanmaken van het `User`-object door `$this->hydrate($row)`.

**Waarom `private`?** De `hydrate`-methode is een intern hulpmiddel. Andere klassen hoeven er niets mee te doen.

> `hydrate` betekent letterlijk "water toevoegen". In code: je vult een leeg object op met data. Je blaast het als het ware tot leven.

---

## Stap 6: Testen in index.php

Maak `public/index.php` aan en test je repository.

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Repositories\UserRepository;

$pdo = new PDO('mysql:host=localhost;dbname=user_repo_opdracht', 'root', '');
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$userRepository = new UserRepository($pdo);
```

Test de volgende dingen:
1. Roep `register()` aan met een username, email en een gehashed wachtwoord (`password_hash('test1234', PASSWORD_DEFAULT)`).
2. Dump het teruggegeven `User`-object met `var_dump()`.
3. Roep daarna `findByUsername()` aan met de username die je zojuist hebt opgeslagen.
4. Dump ook dit resultaat.

Controleer of het werkt door in de database te kijken of de rij er staat.

---

## Uitbreidingsopdrachten

1. Voeg een methode `findByEmail(string $email): ?array` toe. Gebruik daarin ook `hydrate()`.
2. Voeg een methode `findById(int $id): ?User` toe. Deze geeft direct een `?User` terug (geen array, want je hebt de wachtwoordhash hier niet nodig).
3. Gebruik `findById` in een klein scriptje dat de gebruiker ophaalt op basis van een ID dat je zelf invult.
