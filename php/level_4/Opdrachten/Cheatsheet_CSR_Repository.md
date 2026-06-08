# Cheatsheet: CSR-patroon, Repository en PHP-klassen

---

## Strict types

```php
<?php
declare(strict_types=1);
```

Zet dit bovenaan elk PHP-bestand. PHP dwingt dan de opgegeven types af: je kunt geen `string` doorgeven waar een `int` verwacht wordt. Zonder strict types probeert PHP types automatisch om te zetten, wat tot verrassende fouten kan leiden.

---

## Klassen en properties

```php
class User
{
    public int $id;
    public string $username;
    public string $email;
}
```

Een **property** is een variabele die bij een object hoort. Je declareert hem in de klasse-body met een access modifier en een type.

---

## Access modifiers

| Modifier    | Zichtbaar voor                        |
|-------------|---------------------------------------|
| `public`    | Iedereen                              |
| `protected` | De klasse zelf en subklassen          |
| `private`   | Alleen de klasse zelf                 |
| `readonly`  | Eenmalig schrijfbaar, daarna alleen lezen |

Gebruik `private` voor alles wat een intern implementatiedetail is. Maak properties alleen `public` als andere code er echt bij moet.

---

## Constructor

De constructor wordt automatisch aangeroepen bij `new KlasseNaam(...)`. Gebruik hem om het object in te stellen met de waardes die het nodig heeft.

```php
class User
{
    private int $id;
    private string $username;

    public function __construct(int $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }
}

$user = new User(1, 'jan');
```

### Constructor property promotion

Kortere schrijfwijze: declareer en wijs toe in één stap door de access modifier direct in de parameter te schrijven. PHP doet de rest.

```php
class User
{
    public function __construct(
        private readonly int $id,
        private readonly string $username,
    ) {}
}
```

Hetzelfde resultaat als hierboven, zonder de herhalingen.

---

## Return types

```php
public function getUsername(): string { ... }  // altijd een string

public function findUser(): ?User { ... }       // User of null

public function save(): void { ... }            // geeft niets terug
```

`?Type` (nullable type) betekent: de methode geeft dit type terug, of `null`. Gebruik dit als het normaal is dat iets niet gevonden wordt.

---

## Het CSR-patroon

```
Controller  <-->  Service  <-->  Repository  <-->  Database
```

| Laag         | Verantwoordelijkheid                              |
|--------------|---------------------------------------------------|
| Controller   | HTTP-input ontvangen, response terugsturen        |
| Service      | Businesslogica: "mag dit?", "klopt dit?"          |
| Repository   | Data lezen en schrijven naar de database          |

**Gouden regel:** elke laag praat alleen met zijn directe buur. De controller roept de service aan, de service roept de repository aan. De controller schrijft nooit SQL en de repository weet niets van HTTP.

---

## Repository

Een repository is de enige klasse die SQL schrijft voor een bepaald model. De rest van de applicatie vraagt gewoon om objecten en weet niet hoe die uit de database komen.

```php
class UserRepository
{
    public function __construct(private readonly PDO $pdo) {}

    public function findByUsername(string $username): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    private function hydrate(array $row): User
    {
        return new User(
            id: (int) $row['id'],
            username: $row['username'],
            email: $row['email'],
        );
    }
}
```

De PDO-verbinding is `private`: andere klassen hoeven niet te weten hoe de repository intern werkt.

---

## Hydrate

`hydrate()` zet een ruwe database-rij om naar een object.

**Zonder hydrate:**
```php
$row = $stmt->fetch();
// $row is een array: ['id' => 1, 'username' => 'jan', 'email' => 'jan@example.com']
echo $row['username'];
```

**Met hydrate:**
```php
$user = $this->hydrate($row);
// $user is een User-object
echo $user->username;
```

Voordelen:
- Je IDE weet wat het object is en geeft autocompletion
- Je weet zeker welke data beschikbaar is (geen onverwachte array-keys)
- Als een kolomnaam verandert in de database, pas je het alleen in `hydrate()` aan

---

## Dependency injection via de constructor

Elke laag ontvangt zijn afhankelijkheden via de constructor. In `index.php` bouw je de lagen op van binnen naar buiten:

```php
$pdo            = new PDO('mysql:host=localhost;dbname=mijn_db', 'root', '');
$userRepository = new UserRepository($pdo);
$authService    = new AuthService($userRepository);
$authController = new AuthController($authService);
```

Zo is elke klasse op zichzelf te begrijpen en te vervangen. De `UserRepository` weet niet waar zijn PDO vandaan komt. De `AuthService` weet niet welke concrete repository hij gebruikt. Dat is de kracht van dit patroon.

---

## Named arguments

Bij het aanmaken van een object met veel parameters kun je de parameternamen erbij schrijven. Dat maakt de code leesbaarder:

```php
return new User(
    id: (int) $row['id'],
    username: $row['username'],
    email: $row['email'],
);
```

In plaats van:

```php
return new User((int) $row['id'], $row['username'], $row['email']);
```
