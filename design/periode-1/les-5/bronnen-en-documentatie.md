# Les 5: Componenten en Design Systems

**Vak:** Design | **Jaar:** 1 | **Periode:** 1 | **Les:** 5 van 6
**Project:** Goed doel website
**Vorige les:** Les 4 - Kleur en kleurtheorie
**Volgende les:** Les 6 - Project afronding periode 1

---

## Theorie

### Wat is een design system?

Een design system is een verzameling van herbruikbare componenten, richtlijnen en ontwerpbeslissingen die samenwerken als een gedeelde taal voor een team. Het bevat:

- **Componenten:** knoppen, formuliervelden, kaarten, navigatie
- **Stijlen:** kleuren, typografie, spacing, schaduw
- **Richtlijnen:** wanneer gebruik je wat, en waarom
- **Documentatie:** beschrijvingen, do's en don'ts

Grote bedrijven bouwen uitgebreide design systems: Material Design (Google), Human Interface Guidelines (Apple), Carbon (IBM). Voor studenten is een mini-systeem al een enorme stap richting professioneel werken.

### Atomic Design (Brad Frost, 2013)

Atomic Design is een methode die ontwerpen opbouwt als chemische structuren, van klein naar groot:

**Atoms (atomen)**
De kleinste, ondeelbare bouwstenen van een interface:
- Knop
- Invoerveld
- Label / tekst
- Icoon
- Kleurvlak

**Molecules (moleculen)**
Combinaties van twee of meer atoms die samen een functie vervullen:
- Zoekveld (invoerveld + knop)
- Navigatie-item (icoon + label)
- Formulierveld (label + input + foutmelding)
- Kaartafbeelding (afbeelding + alt-tekst)

**Organisms (organismen)**
Complexe, zelfstandige secties van een interface, opgebouwd uit molecules en atoms:
- Navigatiebalk (logo + menu-items + CTA knop)
- Hero sectie (afbeelding + heading + subtitel + CTA)
- Testimonial-sectie (meerdere testimonial-kaarten)
- Footer (links + contactinfo + social icons)

**Templates en Pages**
Boven organisms staan templates (wireframes met placeholder content) en pages (templates gevuld met echte content). In Figma zijn dit meestal frames op paginaniveau.

### Figma Components

Een Figma component is een herbruikbaar ontwerpelement. Het bestaat uit:

- **Master component:** het origineel, herkenbaar aan het ruitvormige icoon
- **Instance:** een kopie die automatisch meebewegt met de master

Wanneer je de master aanpast (kleur, grootte, tekst), worden alle instances automatisch bijgewerkt. Dit is het kernprincipe van design systems.

**Component aanmaken:**
1. Maak een frame met de gewenste afmetingen
2. Stijl het element volledig
3. Selecteer het frame
4. Druk op Ctrl+Alt+K (Windows) of Cmd+Alt+K (Mac)
5. Het icoon wordt een ruitvorm: dit is de master

**Instance gebruiken:**
- Sleep het component vanuit het Assets panel (Shift+I)
- Of gebruik Alt+slepen op een bestaande instance om te dupliceren

**Naamgeving:** Gebruik slash-notatie voor hiearchische organisatie:
- `button/primary/large`
- `button/secondary/small`
- `nav/default`
- `nav/scrolled`

### Varianten en Properties

Varianten zijn meerdere versies van hetzelfde component, gegroepeerd in een component set. Ze worden gebruikt voor:

**States:**
- Default - de normale staat
- Hover - muiscursor eroverheen
- Active / Pressed - tijdens klikken
- Disabled - niet beschikbaar
- Focus - geselecteerd via toetsenbord

**Sizes:**
- Small
- Medium
- Large

**Types:**
- Primary
- Secondary
- Ghost / Outline
- Danger

**Varianten instellen in Figma:**
1. Selecteer een master component
2. Klik op "+ Add variant" in het rechter paneel
3. Figma maakt automatisch een tweede versie
4. Geef elke property een naam (bijv. "Type") en elke variant een waarde (bijv. "primary")
5. Herhaal voor alle benodigde varianten

### Auto Layout

Auto Layout is Figma's implementatie van flexbox-achtig gedrag. Het zorgt dat een component zich aanpast aan de inhoud.

**Wanneer gebruik je Auto Layout?**
- Knoppen die meegroeien met tekst
- Lijsten die uitbreiden als items worden toegevoegd
- Navigatiebalken die items op afstand houden
- Kaarten met wisselende inhoud

**Auto Layout inschakelen:**
- Selecteer een frame of component
- Druk op Shift+A
- Of: rechtermuisklik > Add Auto Layout

**Instellingen:**
- **Direction:** Horizontal of Vertical
- **Gap between items:** ruimte tussen elementen (gebruik 8pt waarden: 8, 16, 24)
- **Padding:** binnenruimte rondom alle items (gebruik 8pt waarden)
- **Alignment:** hoe elementen zich uitlijnen

**Vergelijking met CSS:**
| Figma Auto Layout | CSS Flexbox |
|---|---|
| Direction: Horizontal | flex-direction: row |
| Direction: Vertical | flex-direction: column |
| Gap | gap |
| Padding | padding |
| Hug contents | width: fit-content |
| Fill container | flex: 1 |

### Component States en Interactie

In Figma kun je states definiëren als varianten en vervolgens interacties koppelen via Prototype mode. Dit geeft een realistischere preview van het ontwerp.

**De vier kernstates voor knoppen:**
1. **Default:** basisvorm, altijd zichtbaar
2. **Hover:** lichte kleurverandering (iets donkerder of lichter), cursor: pointer
3. **Active:** verder verduisterd of ingedrukt effect, duurt milliseconden
4. **Disabled:** verlaagd contrast (minimaal 3:1 ratio), cursor: not-allowed

---

## Bronnen

### 1. Atomic Design - Brad Frost
**URL:** atomicdesign.bradfrost.com
Het originele boek over Atomic Design, gratis online beschikbaar. Geschreven door Brad Frost die de methode heeft ontwikkeld. Bevat uitgebreide uitleg, voorbeelden en implementatietips.

### 2. Figma componenten documentatie
**URL:** figma.com/best-practices/components-styles-and-shared-libraries
Officiele Figma documentatie over best practices voor componenten, stijlen en gedeelde bibliotheken. Praktisch en up-to-date.

### 3. Storybook
**URL:** storybook.js.org
Een open source tool om UI componenten gesoleerd te bouwen en te documenteren. Veel gebruikt door development teams. Nuttig om te laten zien hoe developers design systems implementeren in code.

### 4. Material Design 3
**URL:** m3.material.io
Het design system van Google. Excellent als referentie: elke component is uitgebreid gedocumenteerd met gebruik, varianten, states en toegankelijkheidsrichtlijnen.

### 5. Supernova
**URL:** supernova.io
Een tool die Figma design tokens automatisch omzet naar code. Interessant voor gevorderde studenten die de brug naar development willen zien.

### Aanvullend materiaal
- **Figma Playground:** figma.com/community - zoek naar "design system" voor gratis templates
- **Refactoring UI:** refactoringui.com - praktisch boek over UI design principes
- **Laws of UX:** lawsofux.com - psychologische principes achter goed design

---

## Opdracht

### Titel
Componenten bouwen voor je goed doel website

### Omschrijving
In deze opdracht bouw je de basis van je design system door herbruikbare Figma components te maken voor je goed doel website. Je past atomic design toe en gebruikt Auto Layout voor flexibele componenten.

### Leerdoelen
Na het voltooien van deze opdracht kan de student:
- Uitleggen wat een design system is en waarom het nuttig is
- Atomic design toepassen (atoms, molecules, organisms)
- Figma master components aanmaken en instances gebruiken
- Varianten en properties instellen voor component states
- Auto Layout correct configureren voor flexibele componenten

### Taken

**Taak 1: Knopcomponent**
Maak een master component `button/primary/large` met:
- Auto Layout ingesteld: horizontaal, padding 16px top/bottom en 48px links/rechts (of: 16px/32px)
- Minimaal 3 varianten: primary, secondary, disabled
- Gebruik je kleurstijlen uit les 4
- Gebruik je tekststijlen uit les 3

**Taak 2: Navigatiecomponent**
Maak een master component `nav/default` met:
- Frame van 1440x80px
- Elementen: logo-placeholder, 3 menu-links, CTA knop (instance van je knopcomponent)
- Auto Layout: horizontaal, space between
- Optioneel: variant `nav/scrolled` met lichtere achtergrond

**Taak 3: Testimonialkaart**
Maak een master component `card/testimonial` met:
- Afbeelding-placeholder (ronde avatar)
- Citaattekst
- Naam en functie van de persoon
- Gebruik je stijlen
- Plaats 3 instances naast elkaar op een nieuwe pagina 'Components'

**Taak 4: Integratie**
- Gebruik je knopcomponent als instance in je hero sectie
- Gebruik je navigatiecomponent als instance op alle paginas
- Controleer dat de stijlen consistent zijn

**Taak 5: Documentatie in Figma**
- Voeg een beschrijving toe aan elk component via het rechter paneel
- Organiseer je componenten op een aparte 'Design System' pagina in Figma

### Beoordelingscriteria
| Criterium | Beschrijving |
|---|---|
| Correcte components | Echte master components met ruitvorm icoon, geen gekopieerde shapes |
| Auto Layout | Correct ingesteld, componenten groeien mee met inhoud |
| Varianten | Minimaal 3 varianten per component waar gevraagd |
| Stijlgebruik | Kleur- en tekststijlen consequent toegepast |
| Instances | Components hergebruikt als instances, niet als losse kopieeen |
| Naamgeving | Slash-notatie gebruikt (button/primary/large) |

### Inleveren
- Figma-link naar je project (view-only)
- Zorg dat de pagina 'Design System' zichtbaar en georganiseerd is

---

## Klassikale discussievragen

1. **Waarom gebruiken grote bedrijven design systems?**
   Verwacht antwoord: consistentie, snelheid, samenwerking. Stuur het gesprek naar het feit dat bij grote teams zonder systeem elk team zijn eigen stijl maakt.

2. **Wat is het verschil tussen een master component en een instance?**
   Verwacht antwoord: master is het origineel, instance is een kopie die meebewegt. Als studenten het verschil kunnen uitleggen, begrijpen ze het concept.

3. **Wanneer maak je een nieuw component, en wanneer gebruik je een variant?**
   Verwacht antwoord: variant als het dezelfde functie heeft maar er anders uitziet (knop: primary vs secondary). Nieuw component als het een andere functie heeft (knop vs invoerveld).

4. **Hoe is Auto Layout vergelijkbaar met iets in het dagelijks leven?**
   Leuk antwoord: een elastische riem, een accordeon, een zin die afbreekt bij de kantlijn. Dit helpt studenten het concept te onthouden.

5. **Wat zou er misgaan als je geen design system gebruikt voor de goed doel website?**
   Verwacht antwoord: elke pagina ziet er anders uit, aanpassingen duren lang, samenwerking is lastig.

---

## Voorbereiding volgende les (Les 6: Project Afronding)

### Wat studenten moeten meenemen
- Figma project met alle componenten uit les 5
- Alle paginas van de goed doel website (minimaal: home, over ons, contact)
- Kleur- en tekststijlen toegepast

### Wat jij voorbereidt
- Review checklist voor de goed doel website
- Peer review format (wie geeft feedback aan wie)
- Beoordelingsrubric voor de eindoplevering

### Inhoud les 6
1. Korte terugblik op periode 1 (lessen 1-5)
2. Zelfcheck: studenten lopen de checklist door hun eigen ontwerp
3. Peer review sessie: gestructureerde feedback in tweetallen
4. Presentatiemoment: elk student presenteert 2-3 minuten
5. Oplevering: Figma-link inleveren

### Aandachtspunten bij nakijken
- Controleer of studenten echte Figma components gebruiken (ruitvorm icoon zichtbaar?)
- Zijn de components georganiseerd op een aparte pagina?
- Gebruiken ze instances of hebben ze shapes gekopieerd?
- Is Auto Layout zichtbaar in het rechter paneel als je een component selecteert?
- Zijn de kleur- en tekststijlen toegepast via Figma stijlen (niet handmatig ingevoerd)?
