#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Generates styled PDFs for PHP Level 4 teaching materials.
Run:  python _make_pdfs.py
"""

import os, re
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle
from reportlab.lib.units import mm
from reportlab.lib.colors import HexColor
from reportlab.lib.enums import TA_LEFT, TA_CENTER
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, KeepTogether
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont

# ─── Fonts ─────────────────────────────────────────────────────────────────────
WF = r'C:\Windows\Fonts'
for name, file in [
    ('Arial',            'arial.ttf'),
    ('Arial-Bold',       'arialbd.ttf'),
    ('Arial-Italic',     'ariali.ttf'),
    ('Arial-BoldItalic', 'arialbi.ttf'),
    ('CourierNew',       'cour.ttf'),
    ('CourierNew-Bold',  'courbd.ttf'),
]:
    pdfmetrics.registerFont(TTFont(name, os.path.join(WF, file)))
pdfmetrics.registerFontFamily('Arial', normal='Arial', bold='Arial-Bold',
    italic='Arial-Italic', boldItalic='Arial-BoldItalic')
pdfmetrics.registerFontFamily('CourierNew', normal='CourierNew', bold='CourierNew-Bold')

# ─── Colors ────────────────────────────────────────────────────────────────────
DARK      = HexColor('#1A2E35')
TEAL      = HexColor('#028090')
GRAY      = HexColor('#4B5563')
CODE_TEXT = HexColor('#A8D8EA')
INFO_BG1  = HexColor('#EBF8FA')
INFO_BG2  = HexColor('#F0FAFF')
CODE_BG   = HexColor('#1E2D40')
TIP_BG    = HexColor('#FEF3C7')
WHITE     = HexColor('#FFFFFF')
ROW_ALT   = HexColor('#F8FDFE')
GRID_LINE = HexColor('#E5E7EB')

# ─── Page geometry ─────────────────────────────────────────────────────────────
PW, PH   = A4
ML, MR   = 22*mm, 22*mm
MT, MB   = 20*mm, 20*mm
CW       = PW - ML - MR
IL       = 32*mm
IV       = CW - IL

# ─── Paragraph styles ──────────────────────────────────────────────────────────
def S(name, **kw):
    return ParagraphStyle(name, **kw)

sLabel  = S('lbl',    fontName='Arial-Bold', fontSize=10, textColor=TEAL,  spaceAfter=3,  leading=14)
sPartDl = S('pdl',    fontName='Arial-Bold', fontSize=10, textColor=TEAL,  leading=14)
sPartTt = S('ptt',    fontName='Arial-Bold', fontSize=20, textColor=WHITE, leading=26)
sTitle  = S('ttl',    fontName='Arial-Bold', fontSize=24, textColor=DARK,  spaceAfter=4,  leading=30)
sSubtit = S('sub',    fontName='Arial',      fontSize=13, textColor=GRAY,  spaceAfter=14, leading=18)
sH1     = S('h1',     fontName='Arial-Bold', fontSize=16, textColor=DARK,  spaceBefore=16, spaceAfter=5, leading=22)
sH2     = S('h2',     fontName='Arial-Bold', fontSize=13, textColor=TEAL,  spaceBefore=10, spaceAfter=4, leading=18)
sBody   = S('body',   fontName='Arial',      fontSize=11, textColor=GRAY,  spaceAfter=7,  leading=17)
sBullet = S('bul',    fontName='Arial',      fontSize=11, textColor=GRAY,  spaceAfter=3,  leading=16, leftIndent=16)
sNum    = S('num',    fontName='Arial',      fontSize=11, textColor=GRAY,  spaceAfter=3,  leading=16, leftIndent=22)
sCode   = S('code',   fontName='CourierNew', fontSize=9,  textColor=CODE_TEXT, leading=13)
sIL     = S('il',     fontName='Arial-Bold', fontSize=10, textColor=DARK,  leading=14)
sIV     = S('iv',     fontName='Arial',      fontSize=10, textColor=GRAY,  leading=14)
sTip    = S('tip',    fontName='Arial',      fontSize=10, textColor=DARK,  leading=15)
sTHdr   = S('thdr',   fontName='Arial-Bold', fontSize=10, textColor=WHITE, leading=13)
sTCode  = S('tcode',  fontName='CourierNew', fontSize=9,  textColor=DARK,  leading=13)
sTBody  = S('tbody',  fontName='Arial',      fontSize=10, textColor=GRAY,  leading=14)

# ─── Inline markup ─────────────────────────────────────────────────────────────
def md(text, cs=10):
    # Split on backtick spans first so code content and body text are escaped separately.
    # Escaping the full string first and then applying the backtick regex caused double-escaping
    # (e.g. $this->method() would land as $this-&amp;gt;method() in the XML).
    parts = re.split(r'`([^`]+)`', text)
    result = []
    for i, part in enumerate(parts):
        if i % 2 == 0:
            escaped = part.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;')
            escaped = re.sub(r'\*\*([^*]+)\*\*', r'<b>\1</b>', escaped)
            result.append(escaped)
        else:
            escaped = part.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;')
            result.append('<font name="CourierNew" size="{}" color="#1A2E35">{}</font>'.format(cs, escaped))
    return ''.join(result)

# ─── Element builders ──────────────────────────────────────────────────────────
def sp(h=8):       return Spacer(1, h)
def lbl(t):        return Paragraph(t, sLabel)
def title(t):      return Paragraph(t, sTitle)
def subtit(t):     return Paragraph(t, sSubtit)
def h1(t):         return Paragraph(t, sH1)
def h2(t):         return Paragraph(md(t), sH2)
def body(t):       return Paragraph(md(t), sBody)
def bul(t):        return Paragraph('&#8226;  ' + md(t), sBullet)
def num(n, t):     return Paragraph('{}&#160;&#160;{}'.format(n, md(t)), sNum)

def _cl(line):
    s = line.lstrip()
    indent = '&#160;' * (len(line) - len(s))
    content = s.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;').replace('\t', '&#160;' * 4)
    return indent + content

def code(src):
    lines = src.strip('\n').split('\n')
    html = '<br/>'.join(_cl(l) for l in lines)
    para = Paragraph('<font name="CourierNew" size="9">{}</font>'.format(html), sCode)
    t = Table([[para]], colWidths=[CW])
    t.setStyle(TableStyle([
        ('BACKGROUND',    (0,0), (-1,-1), CODE_BG),
        ('LEFTPADDING',   (0,0), (-1,-1), 14),
        ('RIGHTPADDING',  (0,0), (-1,-1), 12),
        ('TOPPADDING',    (0,0), (-1,-1), 10),
        ('BOTTOMPADDING', (0,0), (-1,-1), 10),
    ]))
    return t

def tip(t):
    para = Paragraph(md(t, cs=9), sTip)
    tbl = Table([[para]], colWidths=[CW])
    tbl.setStyle(TableStyle([
        ('BACKGROUND',    (0,0), (-1,-1), TIP_BG),
        ('LINEBEFORE',    (0,0), (0,-1),  3, TEAL),
        ('LEFTPADDING',   (0,0), (-1,-1), 14),
        ('RIGHTPADDING',  (0,0), (-1,-1), 12),
        ('TOPPADDING',    (0,0), (-1,-1), 8),
        ('BOTTOMPADDING', (0,0), (-1,-1), 8),
    ]))
    return tbl

def info_table(rows):
    data = [[Paragraph(a, sIL), Paragraph(b, sIV)] for a, b in rows]
    t = Table(data, colWidths=[IL, IV])
    style = [
        ('TOPPADDING',    (0,0), (-1,-1), 7),
        ('BOTTOMPADDING', (0,0), (-1,-1), 7),
        ('LEFTPADDING',   (0,0), (-1,-1), 10),
        ('RIGHTPADDING',  (0,0), (-1,-1), 8),
        ('VALIGN',        (0,0), (-1,-1), 'TOP'),
    ]
    for i in range(len(rows)):
        b1 = INFO_BG1 if i % 2 == 0 else INFO_BG2
        b2 = WHITE    if i % 2 == 0 else ROW_ALT
        style += [('BACKGROUND', (0,i), (0,i), b1), ('BACKGROUND', (1,i), (1,i), b2)]
    t.setStyle(TableStyle(style))
    return t

def two_col_table(headers, rows, col_ratios=(0.35, 0.65), hdr_bg=DARK):
    w0, w1 = CW * col_ratios[0], CW * col_ratios[1]
    data = [[Paragraph(h, sTHdr) for h in headers]]
    for a, b in rows:
        data.append([Paragraph(a, sTCode), Paragraph(b, sTBody)])
    t = Table(data, colWidths=[w0, w1])
    style = [
        ('BACKGROUND',    (0,0), (-1, 0), hdr_bg),
        ('TOPPADDING',    (0,0), (-1,-1), 6),
        ('BOTTOMPADDING', (0,0), (-1,-1), 6),
        ('LEFTPADDING',   (0,0), (-1,-1), 10),
        ('RIGHTPADDING',  (0,0), (-1,-1), 8),
        ('VALIGN',        (0,0), (-1,-1), 'TOP'),
        ('GRID',          (0,0), (-1,-1), 0.5, GRID_LINE),
    ]
    for i in range(1, len(data)):
        bg = WHITE if i % 2 == 1 else ROW_ALT
        style.append(('BACKGROUND', (0,i), (-1,i), bg))
    t.setStyle(TableStyle(style))
    return t

def part_header(deel, title):
    t = Table([
        [Paragraph('DEEL {}'.format(deel), sPartDl)],
        [Paragraph(title, sPartTt)],
    ], colWidths=[CW])
    t.setStyle(TableStyle([
        ('BACKGROUND',    (0,0), (-1,-1), DARK),
        ('LEFTPADDING',   (0,0), (-1,-1), 16),
        ('RIGHTPADDING',  (0,0), (-1,-1), 16),
        ('TOPPADDING',    (0,0), (0,0),   12),
        ('BOTTOMPADDING', (0,0), (0,0),   2),
        ('TOPPADDING',    (0,1), (0,1),   0),
        ('BOTTOMPADDING', (0,1), (0,1),   14),
    ]))
    return t

def flow_box(text):
    p = Paragraph(text, S('flow', fontName='Arial-Bold', fontSize=11,
        textColor=WHITE, alignment=TA_CENTER, leading=16))
    t = Table([[p]], colWidths=[CW])
    t.setStyle(TableStyle([
        ('BACKGROUND',    (0,0), (-1,-1), DARK),
        ('TOPPADDING',    (0,0), (-1,-1), 10),
        ('BOTTOMPADDING', (0,0), (-1,-1), 10),
        ('LEFTPADDING',   (0,0), (-1,-1), 12),
        ('RIGHTPADDING',  (0,0), (-1,-1), 12),
    ]))
    return t

# ═══════════════════════════════════════════ OPDRACHT: USERREPOSITORY ═════════

def opdracht_story():
    s = []
    s += [lbl('PHP · Level 4')]
    s += [title('UserRepository')]
    s += [subtit('Een klasse bouwen die praat met de database')]
    s += [info_table([
        ('Week',      'W13 – W14'),
        ('Leerjaar',  'Jaar 1 · Periode 2'),
        ('Doel',      'De Repository-laag begrijpen en zelf bouwen als onderdeel van het CSR-patroon'),
        ('Werkwijze', 'Individueel · gebruik je eigen IDE en XAMPP'),
        ('Inleveren', 'PHP-map gezipt via de gebruikelijke manier'),
    ])]

    s += [h1('Wat ga je bouwen?')]
    s += [body('Deze opdracht bestaat uit twee delen. In **Deel 1** bouw je een `UserRepository` die gebruikersdata beheert. In **Deel 2** pas je hetzelfde patroon toe voor een `ProductRepository` met volledige CRUD: aanmaken, opvragen, aanpassen en verwijderen.')]
    s += [body('Aan het einde kun je:')]
    s += [bul('een klasse instantiëren met een databaseverbinding via de constructor')]
    s += [bul('private properties gebruiken om implementatiedetails te verbergen')]
    s += [bul('prepared statements uitvoeren vanuit een klasse')]
    s += [bul('een ruwe database-rij omzetten naar een object (hydrate)')]
    s += [bul('een repository bouwen met alle CRUD-methodes')]
    s += [sp(8)]

    s += [part_header('1', 'UserRepository')]
    s += [sp(8)]

    s += [h1('Benodigdheden')]
    s += [body('Voer het volgende SQL-script uit in phpMyAdmin of je terminal:')]
    s += [code(r"""CREATE DATABASE IF NOT EXISTS user_repo_opdracht
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE user_repo_opdracht;

CREATE TABLE IF NOT EXISTS users (
    id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)  NOT NULL UNIQUE,
    email    VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);""")]
    s += [sp(4)]

    s += [h1('Composer installeren')]
    s += [body('Composer is een tool voor PHP die autoloading voor je regelt. Zonder autoloading moet je bovenaan elk PHP-bestand alle klassen handmatig inladen met `require_once`. Met Composer stel je dat één keer in, en daarna vinden PHP en jouw klassen elkaar automatisch — geen losse require-statements meer.')]
    s += [sp(4)]
    s += [h2('Downloaden')]
    s += [body('Download de installer via getcomposer.org en voer hem uit. Na de installatie is `composer` beschikbaar als commando in je terminal. Controleer dat met:')]
    s += [code('composer --version')]
    s += [sp(4)]
    s += [h2('PSR-4 autoloading instellen')]
    s += [body('Maak een `composer.json` aan in de hoofdmap van je project:')]
    s += [code(r"""{
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}""")]
    s += [sp(6)]
    s += [body('Dit vertelt Composer: alles in de `src/` map hoort bij de namespace `App\\`. Schrijf je `new App\\Domain\\User` in je code? Dan laadt PHP automatisch `src/Domain/User.php`.')]
    s += [sp(4)]
    s += [h2('Autoloader genereren')]
    s += [body('Voer dit commando uit in de map waar je `composer.json` staat:')]
    s += [code('composer dump-autoload')]
    s += [sp(6)]
    s += [body('Composer maakt een `vendor/` map aan met daarin `autoload.php`. Voeg dat bestand bovenaan je `index.php` toe:')]
    s += [code(r"require_once __DIR__ . '/../vendor/autoload.php';")]
    s += [sp(4)]
    s += [tip('Voer `composer dump-autoload` opnieuw uit als je een nieuwe klasse aanmaakt, anders kan PHP die niet vinden.')]
    s += [sp(4)]

    s += [h1('Projectstructuur')]
    s += [body('Maak de volgende mappenstructuur aan:')]
    s += [code("""user_repo_opdracht/
├── composer.json
├── public/
│   └── index.php
└── src/
    ├── Domain/
    │   └── User.php
    └── Repositories/
        └── UserRepository.php""")]
    s += [sp(6)]
    s += [body('De twee mappen hebben elk een eigen rol:')]
    s += [bul('`src/` bevat al je PHP-klassen. Deze map staat buiten de webroot en is niet rechtstreeks via de browser op te vragen.')]
    s += [bul('`public/` is de webroot. Alles wat via een URL bereikbaar moet zijn staat hier: je registratiepagina, loginpagina enzovoort.')]
    s += [sp(4)]
    s += [tip('Stel dat iemand `jouwsite.nl/src/Domain/User.php` intypt. Doordat `src/` buiten de webroot staat, krijgt die persoon niks terug. Je backend-code is zo beter afgeschermd.')]
    s += [sp(4)]

    s += [h1('Stap 1: De User-klasse')]
    s += [body('Maak `src/Domain/User.php` aan.')]
    s += [body('De `User`-klasse is een pure datastructuur: hij bevat alleen data, geen SQL en geen logica. Dit soort klassen noem je een **domeinklasse**.')]
    s += [body('Zet bovenaan het bestand:')]
    s += [code(r"""<?php
declare(strict_types=1);

namespace App\Domain;""")]
    s += [sp(6)]
    s += [body('De klasse heeft drie readonly properties: `$id` (int), `$username` (string) en `$email` (string).')]
    s += [body('Gebruik **constructor property promotion**: schrijf de properties direct als parameters van de constructor, met hun access modifier erbij. Je hoeft ze dan niet apart te declareren.')]
    s += [code(r"""class User
{
    public function __construct(
        public readonly int $id,
        public readonly string $username,
        public readonly string $email,
    ) {}
}""")]
    s += [sp(4)]
    s += [tip('`readonly` zorgt ervoor dat de waarde na aanmaken niet meer gewijzigd kan worden. Dat is precies wat je wilt: een gebruiker die uit de database komt verandert niet.')]
    s += [sp(4)]

    s += [h1('Stap 2: De UserRepository-klasse aanmaken')]
    s += [body('Maak `src/Repositories/UserRepository.php` aan.')]
    s += [body('Zet bovenaan:')]
    s += [code(r"""<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Domain\User;
use PDO;""")]
    s += [sp(6)]
    s += [body('Maak daarna een lege klasse aan:')]
    s += [code(r"""class UserRepository
{
}""")]
    s += [sp(6)]

    s += [h2('Constructor')]
    s += [body('De `UserRepository` heeft een databaseverbinding nodig. Voeg een constructor toe die een `PDO`-object accepteert en opslaat in een **private** property. Gebruik constructor property promotion:')]
    s += [code(r'public function __construct(private readonly PDO $pdo) {}')]
    s += [sp(4)]
    s += [tip('**Waarom `private`?** De PDO-verbinding is een intern detail van de repository. Andere klassen hoeven niet te weten hoe de verbinding eruitziet of hoe die tot stand is gekomen.')]
    s += [sp(4)]

    s += [h1('Stap 3: findByUsername')]
    s += [body('Voeg een methode `findByUsername` toe die zoekt op gebruikersnaam.')]
    s += [code(r'public function findByUsername(string $username): ?array')]
    s += [sp(6)]
    s += [body('De `?array` return type betekent: de methode geeft een `array` terug, of `null` als er niets gevonden is.')]
    s += [body('De methode doet het volgende:')]
    s += [num(1, 'Maak een prepared statement aan via `$this->pdo->prepare()`.')]
    s += [num(2, 'Voer het statement uit met `$username` als parameter.')]
    s += [num(3, 'Haal de rij op via `fetch()`.')]
    s += [num(4, "Als er een rij is: geef een array terug met `'user'` (het User-object) en `'password'` (de wachtwoordhash).")]
    s += [num(5, 'Als er geen rij is: geef `null` terug.')]
    s += [sp(4)]
    s += [tip('**Waarom geef je de wachtwoordhash apart mee?** Het `User`-object zelf bevat nooit een wachtwoord. Maar de code die inloggen afhandelt heeft de hash nodig om te vergelijken met wat de gebruiker intypt.')]
    s += [body('Maak nu eerst het `User`-object direct aan in deze methode. In stap 5 verplaats je dat naar een aparte methode.')]
    s += [sp(4)]

    s += [h1('Stap 4: register')]
    s += [body('Voeg een methode `register` toe die een nieuwe gebruiker opslaat.')]
    s += [code(r'public function register(string $username, string $email, string $passwordHash): User')]
    s += [sp(6)]
    s += [body('De methode doet het volgende:')]
    s += [num(1, 'Maak een prepared statement aan voor een INSERT.')]
    s += [num(2, 'Voer het statement uit met username, email en wachtwoordhash.')]
    s += [num(3, 'Haal het nieuwe ID op via `$this->pdo->lastInsertId()`. Cast dit naar `int`.')]
    s += [num(4, 'Geef een `new User(...)` terug met het nieuwe ID, de username en het email.')]
    s += [sp(4)]
    s += [tip('**Let op:** het hashen van het wachtwoord doe je **niet** in de repository. De repository slaat alleen data op. Hash het wachtwoord in `index.php` voordat je `register()` aanroept: `password_hash($password, PASSWORD_DEFAULT)`.')]
    s += [sp(4)]

    s += [h1('Stap 5: hydrate')]
    s += [body('Je maakt nu op meerdere plekken een `User`-object aan vanuit een database-rij. Als je later ook `findByEmail` of `findById` toevoegt, kopieer je steeds dezelfde code.')]
    s += [body('Maak een **private** methode `hydrate` die een database-rij omzet naar een `User`-object:')]
    s += [code(r'private function hydrate(array $row): User')]
    s += [sp(6)]
    s += [body('De methode maakt een `new User(...)` aan met de waardes uit `$row`. Gebruik **named arguments** voor de leesbaarheid:')]
    s += [code(r"""return new User(
    id: (int) $row['id'],
    username: $row['username'],
    email: $row['email'],
);""")]
    s += [sp(6)]
    s += [body('Vervang daarna in `findByUsername` het directe aanmaken van het `User`-object door `$this->hydrate($row)`.')]
    s += [sp(4)]
    s += [tip('`hydrate` betekent letterlijk "water toevoegen". In code: je vult een leeg object op met data uit de database. Je blaast het als het ware tot leven.')]
    s += [sp(4)]

    s += [h1('Stap 6: Testen in index.php')]
    s += [body('Maak `public/index.php` aan en test je repository.')]
    s += [code(r"""<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Repositories\UserRepository;

$pdo = new PDO('mysql:host=localhost;dbname=user_repo_opdracht', 'root', '');
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$userRepository = new UserRepository($pdo);""")]
    s += [sp(6)]
    s += [body('Test de volgende dingen:')]
    s += [num(1, "Roep `register()` aan met een username, email en een gehashed wachtwoord: `password_hash('test1234', PASSWORD_DEFAULT)`.")]
    s += [num(2, 'Dump het teruggegeven `User`-object met `var_dump()`.')]
    s += [num(3, 'Roep daarna `findByUsername()` aan met de username die je zojuist hebt opgeslagen.')]
    s += [num(4, 'Dump ook dit resultaat en controleer of alle velden kloppen.')]
    s += [sp(4)]

    s += [h1('Uitbreidingsopdrachten')]
    s += [num(1, 'Voeg een methode `findByEmail(string $email): ?array` toe. Gebruik daarin ook `hydrate()`.')]
    s += [num(2, 'Voeg een methode `findById(int $id): ?User` toe. Deze geeft direct een `?User` terug, want je hebt de wachtwoordhash hier niet nodig.')]
    s += [num(3, 'Gebruik `findById` in een klein scriptje dat een gebruiker ophaalt op basis van een ID dat je zelf invult.')]

    # ── DEEL 2 ──────────────────────────────────────────────────────────────────
    s += [sp(12)]
    s += [part_header('2', 'ProductRepository')]
    s += [sp(8)]
    s += [body('De `UserRepository` staat. Nu ga je hetzelfde patroon toepassen voor een `ProductRepository` met volledige CRUD. De stappen zijn korter opgezet dan in Deel 1 — je kent de opbouw nu.')]
    s += [sp(4)]

    s += [h1('Database tabel aanmaken')]
    s += [body('Voeg de volgende tabel toe aan dezelfde database:')]
    s += [code(r"""CREATE TABLE IF NOT EXISTS products (
    id    INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100)  NOT NULL,
    price DECIMAL(8,2)  NOT NULL,
    stock INT UNSIGNED  NOT NULL DEFAULT 0
);""")]
    s += [sp(4)]

    s += [h1('Stap 1: De Product-klasse')]
    s += [body('Maak `src/Domain/Product.php` aan. De klasse heeft vier readonly properties:')]
    s += [bul('`$id` — int')]
    s += [bul('`$name` — string')]
    s += [bul('`$price` — float')]
    s += [bul('`$stock` — int')]
    s += [sp(4)]
    s += [body('Gebruik dezelfde opbouw als de `User`-klasse: strict types, de juiste namespace en constructor property promotion.')]
    s += [sp(4)]

    s += [h1('Stap 2: De ProductRepository aanmaken')]
    s += [body('Maak `src/Repositories/ProductRepository.php` aan. Zelfde namespace-structuur en constructor als `UserRepository`. Begin met een lege klasse en voeg de constructor met `private readonly PDO $pdo` toe.')]
    s += [sp(4)]

    s += [h1('Stap 3: findById')]
    s += [code(r'public function findById(int $id): ?Product')]
    s += [sp(6)]
    s += [body('SELECT op basis van ID. Gebruik `hydrate()` om de rij om te zetten. Geef `null` terug als er geen rij gevonden is.')]
    s += [sp(4)]

    s += [h1('Stap 4: findAll')]
    s += [code(r'public function findAll(): array')]
    s += [sp(6)]
    s += [body('SELECT alle rijen uit de tabel. Gebruik `fetchAll()` in plaats van `fetch()` om alle rijen tegelijk op te halen. Loop daarna over de rijen en roep op elke rij `hydrate()` aan.')]
    s += [code(r"""$rows = $stmt->fetchAll();
$products = [];
foreach ($rows as $row) {
    $products[] = $this->hydrate($row);
}
return $products;""")]
    s += [sp(4)]

    s += [h1('Stap 5: insert')]
    s += [code(r'public function insert(Product $product): Product')]
    s += [sp(6)]
    s += [body('INSERT statement. Gebruik `$product->name`, `$product->price` en `$product->stock` als waarden. Haal het nieuwe ID op via `lastInsertId()` en geef een nieuw `Product`-object terug met dat ID.')]
    s += [sp(4)]

    s += [h1('Stap 6: update')]
    s += [code(r'public function update(Product $product): void')]
    s += [sp(6)]
    s += [body('UPDATE statement. Pas `name`, `price` en `stock` aan. Gebruik `$product->id` in de WHERE-clausule om het juiste product te vinden. Het return type is `void` — je hoeft niets terug te geven.')]
    s += [sp(4)]

    s += [h1('Stap 7: delete')]
    s += [code(r'public function delete(int $id): void')]
    s += [sp(6)]
    s += [body('DELETE statement. Gebruik het meegegeven `$id` in de WHERE-clausule. Return type is `void`.')]
    s += [sp(4)]

    s += [h1('Stap 8: hydrate (private)')]
    s += [body('Voeg een private `hydrate(array $row): Product` methode toe, net als in `UserRepository`. Let op de juiste casts voor de numerieke velden:')]
    s += [bul('`$row[\'id\']` en `$row[\'stock\']` casten naar `int`')]
    s += [bul('`$row[\'price\']` casten naar `float`')]
    s += [sp(4)]

    s += [h1('Stap 9: Alles testen')]
    s += [body('Test alle CRUD-methodes in `index.php`:')]
    s += [num(1, 'Voeg een paar producten toe via `insert()`.')]
    s += [num(2, 'Haal alle producten op met `findAll()` en dump de array.')]
    s += [num(3, 'Zoek één product op via `findById()`.')]
    s += [num(4, 'Pas een product aan via `update()` — verander de prijs of de voorraad.')]
    s += [num(5, 'Verwijder een product via `delete()`.')]
    s += [num(6, 'Roep `findAll()` nogmaals aan en controleer of de wijzigingen kloppen.')]

    return s

# ════════════════════════════════════════════ CHEATSHEET: CSR REPOSITORY ══════

def cheatsheet_story():
    s = []
    s += [lbl('PHP · Level 4')]
    s += [title('Cheatsheet')]
    s += [subtit('CSR-patroon, Repository en PHP-klassen')]

    # Strict types
    s += [h1('Strict types')]
    s += [body('Zet dit bovenaan elk PHP-bestand. PHP dwingt dan de opgegeven types af: je kunt geen `string` doorgeven waar een `int` verwacht wordt. Zonder strict types converteert PHP types stilletjes, wat tot verrassende fouten kan leiden.')]
    s += [code(r"""<?php
declare(strict_types=1);""")]
    s += [sp(4)]

    # Klassen en properties
    s += [h1('Klassen en properties')]
    s += [body('Een **property** is een variabele die bij een object hoort. Je declareert hem in de klasse-body met een access modifier en een type.')]
    s += [code(r"""class User
{
    public int $id;
    public string $username;
    public string $email;
}""")]
    s += [sp(4)]

    # Access modifiers
    s += [h1('Access modifiers')]
    s += [two_col_table(
        ['Modifier', 'Zichtbaar voor'],
        [
            ('public',    'Iedereen'),
            ('protected', 'De klasse zelf en subklassen'),
            ('private',   'Alleen de klasse zelf'),
            ('readonly',  'Eenmalig schrijfbaar, daarna alleen lezen'),
        ],
        col_ratios=(0.3, 0.7),
        hdr_bg=DARK,
    )]
    s += [sp(6)]
    s += [body('Gebruik `private` voor alles wat een intern implementatiedetail is. Maak properties alleen `public` als andere code er echt bij moet.')]
    s += [sp(4)]

    # Constructor
    s += [h1('Constructor')]
    s += [body('De constructor wordt automatisch aangeroepen bij `new KlasseNaam(...)`. Gebruik hem om het object in te stellen met de waardes die het nodig heeft.')]
    s += [code(r"""class User
{
    private int $id;
    private string $username;

    public function __construct(int $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }
}

$user = new User(1, 'jan');""")]
    s += [sp(6)]

    s += [h2('Constructor property promotion')]
    s += [body('Kortere schrijfwijze: declareer en wijs toe in één stap door de access modifier direct in de constructor-parameter te schrijven. PHP doet de rest.')]
    s += [code(r"""class User
{
    public function __construct(
        private readonly int $id,
        private readonly string $username,
    ) {}
}""")]
    s += [sp(4)]
    s += [tip('Hetzelfde resultaat als hierboven, maar zonder de herhalingen. Dit is de stijl die we in dit vak gebruiken.')]
    s += [sp(4)]

    # Return types
    s += [h1('Return types')]
    s += [code(r"""public function getUsername(): string  { ... }  // altijd een string
public function findUser():    ?User   { ... }  // User of null
public function save():        void    { ... }  // geeft niets terug""")]
    s += [sp(6)]
    s += [body('`?Type` (nullable type) betekent: de methode geeft dit type terug, of `null`. Gebruik dit als het normaal is dat iets niet gevonden wordt.')]
    s += [sp(4)]

    # CSR-patroon
    s += [h1('Het CSR-patroon')]
    s += [flow_box('Controller  →  Service  →  Repository  →  Database')]
    s += [sp(6)]
    s += [two_col_table(
        ['Laag', 'Verantwoordelijkheid'],
        [
            ('Controller',  'HTTP-input ontvangen, response terugsturen'),
            ('Service',     'Businesslogica: mag dit? Klopt dit?'),
            ('Repository',  'Data lezen en schrijven naar de database'),
        ],
        col_ratios=(0.28, 0.72),
        hdr_bg=TEAL,
    )]
    s += [sp(6)]
    s += [body('**Gouden regel:** elke laag praat alleen met zijn directe buur. De controller roept de service aan, de service roept de repository aan. De controller schrijft nooit SQL.')]
    s += [sp(4)]

    # Repository
    s += [h1('Repository')]
    s += [body('Een repository is de enige klasse die SQL schrijft voor een bepaald model. De rest van de applicatie vraagt gewoon om objecten en weet niet hoe die uit de database komen.')]
    s += [code(r"""class UserRepository
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
}""")]
    s += [sp(4)]
    s += [body('De PDO-verbinding is `private`: andere klassen hoeven niet te weten hoe de repository intern werkt.')]
    s += [sp(4)]

    # Hydrate
    s += [h1('Hydrate')]
    s += [body('`hydrate()` zet een ruwe database-rij om naar een object van de bijbehorende klasse.')]
    s += [sp(4)]
    s += [body('**Zonder hydrate** werk je overal in de applicatie met een associatieve array:')]
    s += [code(r"""$row = $stmt->fetch();
echo $row['username'];  // je moet de kolomnaam kennen""")]
    s += [sp(6)]
    s += [body('**Met hydrate** werk je met een object. Properties lees je met de **pijl-operator** (`->`):')]
    s += [code(r"""$user = $this->hydrate($row);
echo $user->username;   // pijl-operator, IDE helpt je""")]
    s += [sp(6)]
    s += [body("Stel dat je op je registratiepagina, profielpagina en adminpagina allemaal met gebruikersdata werkt. Zonder hydrate moet elke pagina de kolomnamen uit de database kennen: `$row['username']`, `$row['email']` enzovoort. Verandert een kolomnaam in de database? Dan moet je dat op elke losse pagina opzoeken en aanpassen.")]
    s += [sp(6)]
    s += [body("Met hydrate stel je de kolomnamen eenmalig in, in de `hydrate()`-methode zelf. Daarna werken alle pagina's met een `User`-object en hoeven ze niets van de databasetabel te weten. Alleen `hydrate()` kent de tabel.")]
    s += [sp(4)]
    s += [bul('Properties lees je met de pijl-operator: `$user->username`')]
    s += [bul('Je IDE geeft autocompletion op properties, niet op losse array-keys')]
    s += [bul('Kolomnaam verandert? Pas het alleen aan in `hydrate()`, nergens anders')]
    s += [sp(4)]

    # Dependency injection
    s += [h1('Dependency injection via de constructor')]
    s += [body('Elke laag ontvangt zijn afhankelijkheden via de constructor. In `index.php` bouw je de lagen op van binnen naar buiten:')]
    s += [code(r"""$pdo            = new PDO('mysql:host=localhost;dbname=mijn_db', 'root', '');
$userRepository = new UserRepository($pdo);
$authService    = new AuthService($userRepository);
$authController = new AuthController($authService);""")]
    s += [sp(6)]
    s += [body('Zo is elke klasse op zichzelf te begrijpen en te vervangen. De `UserRepository` weet niet waar zijn PDO vandaan komt. Dat is de kracht van dit patroon.')]
    s += [sp(4)]

    return s

# ─── Render ────────────────────────────────────────────────────────────────────
OUT_DIR = os.path.dirname(os.path.abspath(__file__))

def render(story, filename):
    path = os.path.join(OUT_DIR, filename)
    doc = SimpleDocTemplate(path, pagesize=A4,
        leftMargin=ML, rightMargin=MR, topMargin=MT, bottomMargin=MB)
    doc.build(story)
    print('Generated:', path)

if __name__ == '__main__':
    render(opdracht_story(), 'Opdracht_UserRepository.pdf')
    render(cheatsheet_story(), 'Cheatsheet_CSR_Repository.pdf')
