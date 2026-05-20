# Les 7: Responsive Design en Mobile First

**Periode:** 2  
**Les:** 7 van 12  
**Vakgebied:** Interaction Design / Visual Design  
**Niveau:** Jaar 1  
**Duur:** 90 minuten (45 min theorie + 45 min Figma praktijk)

---

## Theorie

### Wat is responsive design?

Responsive design is een ontwerpaanpak waarbij een website of applicatie zich aanpast aan de schermgrootte en het apparaat van de gebruiker. Het concept is in 2010 geintroduceerd door Ethan Marcotte in zijn artikel "Responsive Web Design" op A List Apart.

De drie pijlers van responsive design zijn:

1. **Flexibele grids** - Lay-outs die werken met verhoudingen (percentages) in plaats van vaste pixelbreedtes.
2. **Flexibele afbeeldingen** - Afbeeldingen die schalen binnen hun container (`max-width: 100%`).
3. **Media queries** - CSS-regels die alleen van toepassing zijn boven of onder een bepaalde viewport-breedte.

Meer dan 60% van alle websitebezoeken komt inmiddels van mobiele apparaten (bron: StatCounter 2024). Responsive design is daarmee geen optionele toevoeging meer, maar een basisvereiste.

### Mobile First strategie

**Mobile First** betekent dat je het ontwerp begint bij het kleinste scherm (375px breedte voor een standaard iPhone) en van daaruit opschaalt naar grotere schermen. Dit staat tegenover de **Desktop First** aanpak waarbij je begint met de volledige desktop-layout en deze afstrips voor mobiel.

**Voordelen van Mobile First:**

- Dwingt je te prioriteren: wat is de kerninhoud en de kernactie op dit scherm?
- CSS-structuur met `min-width` media queries is eenvoudiger te onderhouden.
- Google indexeert websites primair via de mobiele versie (mobile-first indexing, sinds 2019).
- Betere performance op mobiel omdat je begint met minder, niet meer.

**Vuistregel voor de lessen:** Maak altijd eerst het mobile frame in Figma voordat je het desktop frame begint.

### Breakpoints

Breakpoints zijn de breedtedrempels waarop de lay-out verandert. Ze worden bepaald door de content, niet door specifieke apparaten. De volgende breakpoints zijn een goede standaard (gebaseerd op Tailwind CSS):

| Naam | Breedte | Gebruik |
|------|---------|---------|
| Mobile | 375px | Standaard iPhone breedte |
| Small (sm) | 640px | Kleine tablets en landscape mobiel |
| Medium (md) | 768px | Tablets |
| Large (lg) | 1024px | Kleine laptops |
| Extra Large (xl) | 1280px | Desktop |
| 2XL (2xl) | 1536px | Brede schermen |

In CSS gebruik je bij Mobile First `min-width` queries:

```css
/* Mobile stijlen (default, geen query nodig) */
.container { padding: 16px; }

/* Tablet en groter */
@media (min-width: 768px) {
  .container { padding: 32px; }
}

/* Desktop en groter */
@media (min-width: 1280px) {
  .container { padding: 80px; }
}
```

### Grid per breakpoint

Het kolomgrid past zich aan per schermgrootte:

- **Mobiel (375px):** 4 kolommen, 16px margin, 16px gutter
- **Tablet (768px):** 8 kolommen, 32px margin, 24px gutter
- **Desktop (1440px):** 12 kolommen, 80px margin, 24px gutter

In Figma stel je het grid in via Frame > Layout Grid. Gebruik "Columns" en stel het juiste aantal kolommen, margin en gutter in.

### Fluid typography en spacing

**Rem-eenheden** zijn de voorkeur boven pixels omdat ze meeschalen met de browserinstellingen van de gebruiker:

- `1rem` = 16px (browserstandaard)
- `1.125rem` = 18px
- `2rem` = 32px
- `3rem` = 48px

**CSS clamp()** maakt echte fluid typography mogelijk: de lettergrootte schaalt vloeiend tussen een minimum en maximum afhankelijk van de viewport:

```css
/* H1: minimaal 2rem, maximaal 3rem, schaalt met 2.5vw */
h1 { font-size: clamp(2rem, 2.5vw + 1rem, 3rem); }

/* Body: altijd minimaal 1rem (16px) */
body { font-size: clamp(1rem, 1.5vw, 1.125rem); }
```

**Richtlijnen voor typografie:**

- Body tekst: minimaal 16px (1rem) op mobiel
- H1 op mobiel: 28-32px
- H1 op desktop: 40-56px
- H2 op mobiel: 22-26px
- H2 op desktop: 32-40px

**Spacing:** Gebruik een 8pt grid (veelveelvoud van 8px) voor consistente whitespace: 8, 16, 24, 32, 48, 64, 96px.

---

## Bronnen

### 1. Responsive Web Design Basics
**URL:** https://web.dev/responsive-web-design-basics/  
**Aanbieder:** Google (web.dev)  
**Taal:** Engels  
**Niveau:** Beginner tot gevorderd

De officiele Google-gids voor responsive web design. Behandelt viewport meta-tag, CSS media queries, flexibele layouts en afbeeldingen. Altijd actueel en gratis.

### 2. CSS Media Queries (MDN)
**URL:** https://developer.mozilla.org/docs/Web/CSS/CSS_media_queries  
**Aanbieder:** Mozilla Developer Network  
**Taal:** Engels (ook gedeeltelijk Nederlands beschikbaar)  
**Niveau:** Beginner tot gevorderd

De volledige technische referentie voor CSS media queries. Inclusief alle mogelijke condities (breedte, hoogte, orientatie, kleurschema, etc.) met werkende codevoorbeelden.

### 3. Every Layout
**URL:** https://every-layout.dev  
**Aanbieder:** Heydon Pickering en Andy Bell  
**Taal:** Engels  
**Niveau:** Gevorderd

Fascinerende aanpak: responsieve lay-outs zonder media queries, puur met slimme CSS-logica (flexbox, grid, clamp). Verdieping voor studenten die verder willen. De vrije onderdelen zijn voldoende voor de cursus.

### 4. Figma: Responsive Design Resource Library
**URL:** https://figma.com/resource-library/responsive-design/  
**Aanbieder:** Figma  
**Taal:** Engels  
**Niveau:** Beginner

Officiele Figma-uitleg over responsive design in Figma: auto layout, constraints, min/max-breedte. Directe aansluiting bij de tools die studenten in de les gebruiken.

### 5. Mobile First (boek)
**URL:** https://abookapart.com/products/mobile-first  
**Aanbieder:** A Book Apart  
**Auteur:** Luke Wroblewski  
**Taal:** Engels  
**Prijs:** circa $18 digitaal

Het boek dat de mobile first beweging startte. Compact (120 pagina's), helder en nog altijd relevant. Aanrader voor studenten die de designfilosofie willen begrijpen, niet alleen de techniek.

---

## Opdracht

### Portfolio Website - Responsive Wireframe

**Inleveren:** Figma link via de ELO, voor aanvang van les 8.

**Opdrachtomschrijving:**

Maak twee Figma-frames voor de homepage van je portfolio website:

1. **home/mobile** - Formaat 375x812px (iPhone standaard)
2. **home/desktop** - Formaat 1440x900px (desktop standaard)

**Beide frames moeten bevatten:**

- Navigatiebalk (mobiel: hamburger + logo, desktop: volledige navigatie)
- Hero-sectie met een grote heading en een call-to-action knop
- Slider-placeholder als rechthoek (op mobiel: volle breedte, hoogte 240px)
- Intro-sectie met naam en korte bio
- Footer met social media iconen

**Technische eisen:**

- Werk uitsluitend in grijstinten (wireframe, geen kleur of branding)
- Gebruik het 4-koloms grid op mobiel (16px margin, 16px gutter)
- Gebruik het 12-koloms grid op desktop (80px margin, 24px gutter)
- Stel het grid in via Frame > Layout Grid in Figma

**Beoordelingscriteria:**

| Criterium | Punten |
|-----------|--------|
| Mobile wireframe compleet (alle 6 elementen aanwezig) | 30 |
| Desktop wireframe compleet (alle elementen aanwezig) | 30 |
| Grid correct ingesteld en gebruikt | 20 |
| Duidelijk verschil in layout tussen mobile en desktop | 20 |
| **Totaal** | **100** |

---

## Klassikale discussievragen

1. **Statistieken:** "Welke sites gebruik jij zelf het meest op je telefoon? Zijn die goed ontworpen voor mobiel?"

2. **Prioritering:** "Als je maar 3 dingen op de mobiele homepage mag tonen, wat zijn die 3 dingen voor een portfolio site?"

3. **Slechte ervaringen:** "Heeft iemand een voorbeeld van een website die er slecht uitziet op mobiel? Wat gaat er mis?"

4. **Strategie:** "Waarom zou Google de voorkeur geven aan sites die mobile-first zijn ontworpen?"

5. **Content vs. Layout:** "Is het slim om andere content te tonen op mobiel dan op desktop? Wanneer wel, wanneer niet?"

6. **Breakpoints:** "Hoe zou jij bepalen waar je breakpoints legt als je geen framework zoals Tailwind gebruikt?"

---

## Voorbereiding volgende les (Les 8: Navigatiepatronen en Sliders)

### Wat komt er in les 8?

**Hamburger menu en mobiele navigatiepatronen:**
- Wanneer gebruik je een hamburger menu versus een tab bar?
- Alternatieven voor het hamburger menu (tab navigatie, priority plus, etc.)
- Toegankelijkheidsvereisten voor mobiele navigatie

**Slider en carousel design:**
- Wanneer is een slider de juiste keuze?
- Navigatie-indicatoren: dots, arrows, paginering
- Autoplay: wel of niet en waarom
- Toegankelijkheid van sliders (toetsenbord, screenreader)

**Figma prototype:**
- Een klikbaar prototype van een slider in Figma
- Gebruik van interactive components en smart animate

### Voorbereiding voor de docent:
- Zet een Figma bestand klaar met een startpunt voor de slider prototype-oefening
- Bereid 2-3 voorbeeldsliders voor van Dribbble/Awwwards ter inspiratie
- Controleer of alle studenten hun wireframe-opdracht van les 7 hebben ingeleverd

### Bronnen voor les 8:
- Dribbble slider designs: https://dribbble.com/search/slider
- Awwwards navigatie: https://www.awwwards.com/websites/navigation/
- MDN: CSS Transitions voor animaties: https://developer.mozilla.org/docs/Web/CSS/CSS_Transitions
