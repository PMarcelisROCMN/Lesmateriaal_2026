# Les 4: Kleur en Contrast

**Vak:** Design voor het Web  
**Jaar:** 1, Periode 1  
**Vorige les:** Les 3 - Typografie  
**Volgende les:** Les 5 - Componenten en Design Systems  
**Projectcontext:** Studenten bouwen een goed doel website met hero, nav, CTA en testimonials.

---

## Theorie

### HSL vs RGB

**RGB** (Red, Green, Blue) is de kleurruimte die beeldschermen gebruiken. Elke kleur bestaat uit drie getallen van 0 tot 255. RGB is geschikt voor technische implementatie maar slecht intuItief voor designers: als je een kleur iets lichter wilt maken, moet je alle drie waarden aanpassen, zonder dat je goed kunt voorspellen wat het resultaat is.

**HSL** (Hue, Saturation, Lightness) is de kleurruimte die veel beter aansluit bij hoe mensen over kleur denken:

- **Hue (tint):** 0 tot 360 graden op het kleurwiel. 0 is rood, 120 is groen, 240 is blauw.
- **Saturation (verzadiging):** 0% is volledig grijs (geen kleur), 100% is zo levendig mogelijk.
- **Lightness (helderheid):** 0% is zwart, 50% is de "normale" kleur, 100% is wit.

Praktisch voordeel van HSL: om een lichtere variant van een kleur te maken, verhoog je alleen de L-waarde. Om een minder schreeuwerige kleur te maken, verlaag je de S-waarde. Dit maakt het bouwen van shade-systemen (50-900) veel eenvoudiger.

**In CSS gebruik je HSL als:** `color: hsl(214, 84%, 56%);` (dit is een blauw).  
**In Figma:** Klik op de kleurpicker en schakel over naar HSL-modus via het dropdown menu.

### WCAG Contrast Ratios

WCAG staat voor Web Content Accessibility Guidelines, gepubliceerd door het W3C. In Nederland is WCAG 2.1 AA-conformiteit verplicht voor overheidswebsites en aanbevolen voor alle websites.

**Hoe wordt contrast ratio berekend?**  
Het contrast ratio is de verhouding tussen de relatieve lichtheid (relative luminance) van de lichtste en donkerste kleur. Een ratio van 1:1 betekent geen contrast (twee identieke kleuren), 21:1 is het maximum (zwart op wit).

**WCAG AA (minimum):**
- Normale tekst (kleiner dan 18px of 14px bold): 4.5:1
- Grote tekst (18px+ normaal, of 14px+ bold): 3:1
- UI-componenten en iconen: 3:1

**WCAG AAA (optimaal):**
- Normale tekst: 7:1
- Grote tekst: 4.5:1

In de praktijk: streef naar AAA voor bodytekst en AA voor alles overige. Veel populaire kleurpaletten halen niet eens AA voor bodytekst op een witte achtergrond - dit is een veelvoorkomende fout.

**Handige vuistregel:** Donkerblauw of donkergrijs op wit haalt bijna altijd AA. Een middelgrijze kleur op wit vaak niet.

### Kleurpsychologie

Kleurassociaties zijn deels biologisch (rood = gevaar, groen = veilig) en deels cultureel bepaald. De onderstaande associaties gelden voor de westerse context:

| Kleur | Primaire associaties | Gebruik in webdesign |
|-------|---------------------|---------------------|
| Rood | Urgentie, gevaar, passie | Foutmeldingen, uitverkoop, CTA waar urgentie gewenst is |
| Oranje | Energie, vriendelijkheid, creativiteit | Zachte CTA, community-gerichte merken |
| Geel | Aandacht, optimisme, waarschuwing | Waarschuwingen, highlights, kinderproducten |
| Groen | Groei, natuur, succes, gezondheid | Successtates, duurzaamheid, gezondheid |
| Blauw | Vertrouwen, rust, professionaliteit | Financieel, overheid, tech, SaaS |
| Paars | Luxe, creativiteit, wijsheid | Premium producten, onderwijs, beauty |
| Zwart | Elegantie, kracht, luxe | High-end mode, premium tech |
| Wit | Zuiverheid, ruimte, minimalisme | Medisch, tech, minimalistische merken |

**Belangrijk:** kleurpsychologie is nooit de enige factor. De context, typografie en compositie versterken of verzwakken de kleurassociaties. Een rood gebruikt met veel witruimte en elegante typografie voelt heel anders dan rood in een drukke layout.

### Semantische Kleuren

Semantische kleuren zijn kleuren met een vaste, afgesproken betekenis binnen een designsysteem. Ze gaan los van de merkkleur en zijn bedoeld om informatie over te brengen:

- **Success (groen):** Actie geslaagd, data opgeslagen, formulier correct
- **Error (rood):** Actie mislukt, invoer ongeldig, iets geblokkeerd
- **Warning (amber/oranje):** Let op, tijdelijk probleem, niet-kritieke melding
- **Info (blauw):** Neutrale informatie, tips, updates

In een Figma token-systeem (of CSS custom properties) definieer je deze los van de brand-kleuren:
```
--color-success: #16a34a;
--color-error: #dc2626;
--color-warning: #d97706;
--color-info: #2563eb;
```

Het voordeel: als je besluit dat "success" een andere groentint moet worden, pas je het op een plek aan en het update overal.

### Het Shade-systeem (50-900)

Afkomstig uit Tailwind CSS, nu de industriestandaard. Elk kleur heeft 10 varianten:

- **50:** Zeer licht, bijna wit. Achtergronden, hover states op lichte elementen.
- **100-200:** Lichte tinten. Subtiele achtergronden, tags, badges.
- **300-400:** Medium-licht. Borders, disabled states.
- **500:** De basiskleur. Dit is de "echte" kleur.
- **600-700:** Donkerder. Hover states van knoppen, actieve states.
- **800-900:** Zeer donker. Zelden gebruikt als kleur, wel als tekst op lichte achtergronden.

### Neutrals

Neutrals zijn grijstinten die de ruggengraat van elk kleurpalet vormen. Een goede neutral is nooit puur grijs (#808080) maar heeft altijd een subtiele kleurkant:

- **Warme neutral:** Licht bruinig/beige tinten. Past bij oranje, rood, geel merkkleuren.
- **Koele neutral:** Licht blauwachtig. Past bij blauw, groen, paarse merkkleuren.

Gebruik nooit `#000000` als tekstkleur op een webpagina - het is te hard. `#0F172A` (bijna-zwart met blauw) of `#1C1C1E` (bijna-zwart neutraal) zijn betere keuzes.

---

## Bronnen

### 1. Coolors - coolors.co
**Waarom nuttig:** De meest gebruikte palette-generator ter wereld. Studenten kunnen een startkleur invoeren en Coolors genereert harmonische combinaties. De "lock"-functie laat je een kleur vastzetten en de rest laten varieren. Exporteert naar CSS, SVG en meer. Ideaal als beginpunt voor studenten die moeite hebben met kleurkeuze.

### 2. WebAIM Contrast Checker - webaim.org/resources/contrastchecker
**Waarom nuttig:** De meest gebruikte WCAG contrast checker. Voer twee hex-kleuren in en je ziet direct het contrast ratio en of het AA/AAA haalt. Bevat ook een "link contrast" checker voor interactieve elementen. Gratis, geen account nodig. Dit is het tool dat studenten verplicht moeten gebruiken bij de opdracht.

### 3. Tailwind CSS kleuren - tailwindcss.com/docs/customizing-colors
**Waarom nuttig:** Het meest gebruikte shade-systeem in de industrie. Alle kleuren zijn zorgvuldig kalibreerd zodat ze onderling consistente contrastverhoudingen hebben. Studenten kunnen dit als referentie gebruiken en de hex-waarden kopiEren als startpunt voor hun eigen palet. Bevat ook grijstinten (Slate, Gray, Zinc, Neutral, Stone) die direct bruikbaar zijn als neutrals.

### 4. Radix Colors - radix-ui.com/colors
**Waarom nuttig:** Een geavanceerder kleurensysteem dat specifiek gebouwd is voor toegankelijkheid en semantiek. Elk kleur heeft 12 stappen met duidelijke gebruiksdoelen (achtergrond, border, tekst etc.). Uitstekend voor studenten die verder willen gaan dan de basis. Bevat ook automatisch donkere modus varianten.

### 5. Color Hunt - colorhunt.co
**Waarom nuttig:** Een gecureerde verzameling van duizenden vier-kleurenpaletten, gefilterd op populariteit, trends of seizoen. Goed als inspiratiebron wanneer studenten vastlopen. Elk palet is direct kopieerbaar als hex codes. Minder geschikt voor technische implementatie (geen shade-systeem), maar uitstekend voor de initiEle keuze.

---

## Opdracht

### Les 4 Opdracht: Kleurpalet voor het Goed Doel Project

**Doel:** Studenten bouwen een volledig kleurpalet voor hun goed doel website en slaan dit op als Figma kleurstijlen.

**Deliverable:** Figma file met kleurstijlen klaar, gedeeld via Brightspace.

**Stappen:**

1. **Kies een primaire kleur** die past bij het thema en de emotie van jouw goed doel. Denk na: wat wil je uitstralen? Warmte, urgentie, hoop, betrouwbaarheid?

2. **Bouw een shade-palet** voor de primaire kleur met minimaal 5 stappen. Gebruik de 50-900 naamgeving. Je mag Tailwind CSS als referentie gebruiken en aanpassen, of zelf een palet opbouwen via Figma's HSL-sliders.

3. **Kies neutrals:** minimaal 5 grijstinten voor tekst en achtergronden. Tip: kies warme of koele grijzen die passen bij je primaire kleur.

4. **Kies een accentkleur** voor CTA-knoppen en highlights. Dit is een tweede, contrasterende kleur die spaarzaam wordt gebruikt.

5. **Check WCAG AA contrast** voor minimaal de volgende combinaties:
   - Bodytekst (neutral/700 of donkerder) op de achtergrond (neutral/50 of wit): minimaal 4.5:1
   - CTA-knoptekst op knopkleur: minimaal 4.5:1
   - Headings op achtergrond: minimaal 3:1 (groot formaat)

6. **Sla op als Figma kleurstijlen** met de volgende naamstructuur:
   - `brand/primary-50` t/m `brand/primary-900`
   - `neutral/50` t/m `neutral/900`
   - `semantic/success`, `semantic/error`, `semantic/warning`, `semantic/info`
   - `accent/500` (en eventuele shades)

**Beoordelingscriteria:**
- Kleurpalet is aanwezig met minimaal 5 brand-shades en 5 neutral-shades
- Alle kleurstijlen zijn correct benoemd in Figma
- Minimaal 3 contrast combinaties zijn gecheckt en halen WCAG AA
- De kleurkeuze is onderbouwd (kort in een notitie of comment in Figma)

---

## Klassikale discussievragen

1. **Kleurpsychologie:** "Stel je maakt een website voor een voedselbank, een kinderziekenhuis en een milieuorganisatie. Welke primaire kleur kies je voor elk, en waarom? Zijn er kleuren die voor alle drie zouden werken?"

2. **Contrast in de praktijk:** "Welke grote Nederlandse websites of apps kennen jullie die waarschijnlijk niet WCAG AA halen? Hoe zou je dat controleren?" (Vervolg: laat studenten live checken met de WebAIM tool)

3. **Semantiek vs. merk:** "Stel een merk heeft rood als merkkleur. Hoe gebruik je dan semantische rood voor foutmeldingen zonder verwarring te scheppen? Wat zijn mogelijke oplossingen?"

4. **Donkere modus:** "Steeds meer apps hebben een donkere modus. Waarom werkt het niet om gewoon alle lichte en donkere kleuren om te wisselen? Wat gaat er mis?" (Verwacht antwoord: contrast ratios kloppen dan niet meer, sommige kleuren die op licht werken zijn te levendig op donker etc.)

---

## Voorbereiding volgende les

**Les 5: Componenten en Design Systems**

Zorg dat het volgende klaarstaat voor les 5:

**Materiaal:**
- Presentatie les 5 (componenten) beschikbaar in Brightspace
- Figma template met basis-frame klaar (of verwijzing naar community file voor Atomic Design)
- Voorbeeldfile met eenvoudige component (knop met varianten) als demo-startpunt

**Figma voorbereiding:**
- Maak zelf alvast een Figma file met een knop-component met 3 varianten (default, hover, disabled) als demo
- Test of Auto Layout werkt zoals verwacht voor de hero-sectie

**Technische kennis opfrissen:**
- Atomic design: atoms (knop, input, icoon), molecules (formulierveld = label + input + foutmelding), organisms (nav = logo + links + CTA-knop)
- Figma components: hoe maak je een component (Ctrl+Alt+K), hoe maak je varianten (rechtermuisknop > Add variant)
- Auto Layout: padding, spacing, fill container vs. hug contents

**Controleer bij studenten:**
- Hebben alle studenten hun kleurpalet (les 4 opdracht) ingeleverd?
- Zijn Figma kleurstijlen aangemaakt? Dit is een vereiste voor les 5 - je hebt de kleuren nodig bij het bouwen van componenten.
- Studenten die dit nog niet hebben: zorg dat ze dit voor aanvang van les 5 afronden.

**Leerdoelen les 5:**
1. Studenten begrijpen het principe van atomic design
2. Studenten kunnen een herbruikbaar component aanmaken in Figma
3. Studenten passen Auto Layout toe in een nav of hero
4. Studenten bouwen de nav-component van hun goed doel website als Figma component
