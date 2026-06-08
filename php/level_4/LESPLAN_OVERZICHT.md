# Periode 2 — OOP Denken & Bestelsysteem
## Leerlijn: Jaar 1 Periode 2 (6 weken, 12 lessen)

### Doel
Studenten kennen na periode 1 procedureel PHP. In deze periode leren ze OOP stap voor stap
toepassen door een bestelsysteem te bouwen: producten beheren als admin, een winkelwagen
bijhouden in een sessie, en nep-afrekenen als klant. Ze leren nadenken over *architectuur*:
welke klasse doet wat? Zo leggen ze de basis voor CSR in jaar 2.

### Eindproduct: Bestelsysteem
- **Admin**: producten toevoegen / bewerken / verwijderen (CRUD)
- **Admin**: overzicht van alle bestellingen
- **Klant**: productoverzicht bekijken
- **Klant**: producten in winkelwagen stoppen (opgeslagen in sessie)
- **Klant**: bestelling afrekenen (nep — gewoon opslaan in DB)

### Projectstructuur (doel einde periode)
```
bestelsysteem/
├── composer.json
├── vendor/
│   └── autoload.php
├── src/
│   ├── Domain/
│   │   ├── Product.php
│   │   ├── OrderItem.php
│   │   └── Order.php
│   ├── Cart.php              ← beheert de winkelwagen in de sessie
│   ├── ProductRepository.php ← alle DB-logica voor producten
│   └── OrderRepository.php   ← alle DB-logica voor bestellingen
└── public/
    ├── index.php             ← productoverzicht klant
    ├── cart.php              ← winkelwagen
    ├── checkout.php          ← afrekenen
    └── admin/
        ├── products.php      ← CRUD producten
        └── orders.php        ← bestellingoverzicht
```

---

## Weekoverzicht

| Week | Les 1 | Les 2 |
|------|-------|-------|
| W9   | OOP Basis — Klassen & Objecten | Constructor & Access Modifiers |
| W10  | Domeinklassen voor het Bestelsysteem | Meerdere Klassen Samenwerken |
| W11  | Modern PHP — Type Hints & Strict Types | Constructor Promotion, Readonly & Exceptions |
| W12  | Namespaces & Mappenstructuur | Composer & Autoloading |
| W13  | Database in een Klasse — Repository | Architectuur: Wie Doet Wat? |
| W14  | Cart-klasse & Sessies in OOP | Checkout & Admin — Eindopdracht |

---

## Gedetailleerde lesinhoud

### Week 9 — OOP Basis

**W9_L1 — Klassen & Objecten**
- Wat is OOP en waarom? (vergelijk: procedureel vs. OOP)
- Een klasse aanmaken: `class Product {}`
- Properties declareren, methoden schrijven
- Object aanmaken met `new Product()`
- `$this` gebruiken binnen een klasse
- Praktijk: klasse `Product` met `naam`, `prijs`, `voorraad` + methode `getPrijsMetBTW()`

**W9_L2 — Constructor & Access Modifiers**
- `__construct()` — waarom en hoe
- `public`, `private`, `protected` — wie mag wat zien?
- Getters schrijven voor private properties
- Praktijk: `Product`-klasse uitbreiden met constructor en access modifiers

---

### Week 10 — OOP in de Praktijk

**W10_L1 — Domeinklassen voor het Bestelsysteem**
- Het project introduceren (bestelsysteem overzicht)
- Wat zijn "domain classes"? (simpele klassen die data voorstellen)
- `Product`-klasse definitief bouwen
- `OrderItem`-klasse: welk product, hoeveel, voor welke prijs?
- `Order`-klasse: klant, datum, lijst van OrderItems
- Praktijk: alle drie klassen bouwen en objecten aanmaken

**W10_L2 — Meerdere Klassen Samenwerken**
- Een `Order` bevat een array van `OrderItem`-objecten
- Methode `berekenTotaal()` op `Order`
- Hoe geef je objecten mee aan andere klassen?
- Praktijk: een nep-bestelling opbouwen puur met objecten (nog geen database)

---

### Week 11 — Modern PHP (PHP 8.x)

**W11_L1 — Type Hints & Strict Types**
- `declare(strict_types=1)` — wat doet dit en waarom zet je het bovenaan?
- Type hints op parameters: `string $naam`, `int $aantal`, `float $prijs`
- Return types: `: string`, `: int`, `: float`, `: void`, `: array`
- Nullable types: `?string` — mag null zijn óf een string
- Praktijk: alle klassen voorzien van type hints

**W11_L2 — Constructor Property Promotion, Readonly & Exceptions**
- Constructor property promotion (PHP 8.0): properties direct in de constructor declareren
- `readonly` (PHP 8.1): waarde kan na aanmaken niet meer veranderen
- Exceptions: `throw new InvalidArgumentException('...')`
- `try { ... } catch (InvalidArgumentException $e) { ... }`
- Praktijk: domeinklassen herschrijven met promotion + readonly + validatie via exceptions

---

### Week 12 — Namespaces & Composer

**W12_L1 — Namespaces**
- Waarom namespaces? (naambotsingen voorkomen)
- `namespace Bestelsysteem\Domain;` bovenaan een bestand
- `use Bestelsysteem\Domain\Product;` om een klasse te importeren
- PSR-4 mappenstructuur koppelen aan namespace
- Praktijk: alle domeinklassen voorzien van namespaces

**W12_L2 — Composer & Autoloading**
- Wat is Composer? (pakketbeheerder voor PHP)
- `composer.json` aanmaken met PSR-4 autoloading
- `"Bestelsysteem\\" : "src/"` instellen
- `composer dump-autoload` uitvoeren
- `require __DIR__ . '/vendor/autoload.php';` — één regel en klaar
- Praktijk: handmatige `require`-regels weghalen

---

### Week 13 — Database & Architectuur

**W13_L1 — Database in een Klasse (Repository)**
- Probleem: SQL door je hele project verspreid
- `ProductRepository` ontvangt een `PDO`-object via de constructor
- Methoden: `findAll()`, `findById(int $id)`, `save(Product $product)`, `delete(int $id)`
- `hydrate()`-methode: een rij uit de DB omzetten naar een `Product`-object
- Praktijk: `ProductRepository` bouwen en koppelen aan de productenpagina

**W13_L2 — Architectuur: Wie Doet Wat?**
- `OrderRepository` bouwen
- Separation of Concerns — elke klasse heeft één taak
- Lagendiagram: welke klasse praat met welke?
- Vergelijking: procedureel (W2) vs. OOP aanpak van nu
- Vooruitblik jaar 2: "Dit gaan we omzetten naar een echte API"

---

### Week 14 — Winkelwagen, Checkout & Eindopdracht

**W14_L1 — Cart-klasse & Sessies in OOP**
- Probleem: sessie-winkelwagen procedureel is rommelig
- `Cart`-klasse die de sessie intern beheert
- Methoden: `addItem()`, `removeItem()`, `getItems()`, `getTotaal()`, `clear()`
- Praktijk: `Cart`-klasse bouwen en koppelen aan `cart.php`

**W14_L2 — Checkout & Admin — Eindopdracht**
- Winkelwagen omzetten naar een `Order` + opslaan via `OrderRepository`
- Sessie leegmaken na succesvolle bestelling
- Admin CRUD voor producten (toevoegen/bewerken/verwijderen)
- Admin: overzicht van alle bestellingen
- Inleveren eindopdracht + vooruitblik jaar 2 (CSR, API, JSON)

---

## Wat studenten aan het einde kunnen
- Een klasse ontwerpen met de juiste properties en methoden
- Constructor property promotion en `readonly` toepassen
- Type hints en strict types gebruiken
- Exceptions gooien en opvangen
- Namespaces en Composer instellen
- Database-code bundelen in een repository-klasse
- Sessies beheren via een klasse
- Nadenken over: *welke klasse is verantwoordelijk voor wat?*

## Aansluiting op Jaar 2
In jaar 2 bouwen studenten voort op deze kennis:
- Het bestelsysteem wordt omgebouwd naar een JSON API
- Controllers en services worden geïntroduceerd als extra lagen
- `.htaccess` en een single entry point vervangen de losse PHP-pagina's
- Het CSR-patroon is dan herkenbaar en logisch
