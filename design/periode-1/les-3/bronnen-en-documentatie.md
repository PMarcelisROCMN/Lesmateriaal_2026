# Les 3: Typografie en Legibility

**Vak:** Design  
**Jaar:** 1  
**Periode:** 1  
**Doelgroep:** Eerstejaars studenten die leren ontwerpen in Figma voor een charity website

---

## Theorie

### Serif versus sans-serif

De meest fundamentele opdeling in typografie is die tussen serif- en sans-serif-lettertypen. Seriffonts hebben kleine decoratieve dwarsstreepjes (schreven) aan het einde van de letterstreken. Historisch gezien waren dit hulplijntjes voor stenografen en zijn ze meegenomen in drukletters. Sans-serif fonts ontbreken deze uitlopers, vandaar de naam: "zonder schreven" in het Frans.

In gedrukte media, zoals kranten en boeken, domineerden seriflettertypen lange tijd omdat de schreven de letters optisch aan elkaar koppelen en het oog begeleiden langs de regels. Bij kleine lettergroottes op papier zorgen de schreven voor extra herkenbaarheid. Bekende seriffonts zijn Times New Roman, Georgia, Garamond en Playfair Display.

Voor schermen gelden andere regels. Vroeger waren schermen te laag opgelos om de fijne schreven goed te renderen, waardoor sans-serif de standaard werd voor digitale interfaces. Hoewel moderne retina-schermen ook seriffonts goed weergeven, is de conventie gebleven: sans-serif fonts als Inter, Roboto en Outfit zijn de werkpaarden van het webdesign. Ze zijn functioneel, neutraal en lezen comfortabel op elk schermformaat.

Een veelgebruikte en effectieve aanpak is om serif te combineren met sans-serif: een expressief seriflettertype als display heading voor karakter en persoonlijkheid, gecombineerd met een heldere sans-serif voor de body. Dit contrast versterkt de visuele hiërarchie terwijl de leesbaarheid maximaal blijft.

### Type scale en visuele hierarchie

Een type scale is een geordende reeks lettergroottes die samen een coherent systeem vormen. Net zoals een muzikale toonladder bestaat uit noten die in harmonische verhouding staan, staat elke stap in een type scale in een vaste verhouding tot de volgende. De meest gebruikte verhouding is de "Major Third" (1.25) of de "Perfect Fourth" (1.333), maar voor webdesign werken ook handmatig gekozen schalen goed.

Het nut van een vaste type scale is dat designers nooit meer hoeven te raden welke maat gepast is. Elk element in de interface heeft een duidelijke plek in de hiërarchie: een H1 is de grootste en belangrijkste tekst op de pagina, een H2 markeert secties, een H3 componentonderdelen, enzovoort. Body tekst heeft altijd dezelfde grootte, en captions zijn consequent kleiner. Dit zorgt voor een voorspelbaar en professioneel ogende layout.

Voor eerstejaars studenten is het nuttig om de schaal concreet te maken: 48px voor H1, 36px voor H2, 24px voor H3, 18px voor H4, 16px voor body en 12px voor captions is een bewezen startpunt voor webdesign. Studenten die leren ontwerpen in Figma kunnen deze schaal direct als tekststijlen aanmaken, zodat ze bij elk nieuw ontwerp dezelfde consistente schaal hergebruiken.

Een veelgemaakte beginnersfout is te veel verschillende groottes gebruiken zonder systeem. Een pagina met tekst in 13, 14, 15, 17, 19 en 21px heeft geen duidelijke hiërarchie, ook al voelen al die maten min of meer hetzelfde. Een type scale dwingt tot discipline: je kiest uit vaste opties, niet uit een eindeloos spectrum.

### Line-height, letter-spacing en leesbaarheid

De ruimte rondom tekst is net zo belangrijk als de tekst zelf. Twee fundamentele eigenschappen bepalen het leescomfort: line-height (de afstand tussen regels) en letter-spacing (de ruimte tussen letters).

Line-height wordt in CSS uitgedrukt als een getal zonder eenheid, als vermenigvuldiger van de fontgrootte. Een line-height van 1.6 betekent dat de totale regelhoogte 1.6 maal de fontgrootte is. Voor body tekst van 16px levert dit een regelafstand van 25.6px op. Dit klinkt technisch, maar het effect is direct voelbaar: tekst met een line-height van 1.1 voelt samengeperst en vermoeiend, tekst met 1.6 heeft lucht en nodigt uit tot lezen. De vuistregel is: 1.5 tot 1.6 voor body, 1.1 tot 1.3 voor headings (die zijn groter en hoeven minder tussenruimte).

Letter-spacing (ook tracking genoemd) bepaalt de ruimte tussen alle letters in een woord. In CSS is de standaard 0. Voor headings werkt een licht negatieve waarde (-0.02em tot -0.04em) goed: letters komen iets dichter bij elkaar en de heading voelt compacter en gedurfder. Voor body tekst is 0 altijd de juiste keuze. Te ruime letter-spacing in body tekst maakt woorden moeilijker herkenbaar omdat het visuele woordpatroon vervaagt.

Een praktisch experiment: toon studenten een tekstblok en verander alleen de line-height van 1.0 naar 1.6. De tekst leest zich letterlijk anders, zonder ook maar één letter te veranderen. Dit illustreert dat typografie niet alleen gaat over welke letters je kiest, maar over hoe je ze in de ruimte plaatst.

### Maximale regellengte

De optimale regellengte voor body tekst ligt tussen de 45 en 75 karakters per regel. Dit getal gaat over karakters (letters, spaties en leestekens), niet over woorden. Een vuistregel in woorden: 8 tot 12 woorden per regel is comfortabel.

De reden voor deze grens is fysiologisch: het menselijk oog heeft een beperkt span waarbinnen het tekst snel kan scannen zonder de kop te bewegen. Te korte regels zijn vermoeiend omdat de ogen te vaak naar links moeten springen voor de volgende regel. Het leesritme wordt verstoord en de tekst voelt gefragmenteerd. Te lange regels zijn het ergste: na het lezen van een lange regel moet het oog de nieuwe regel vinden, en bij lange regels is het gemakkelijk om per ongeluk een regel over te slaan.

In CSS is de eigenschap `max-width: 65ch` een betrouwbaar startpunt. De eenheid `ch` staat gelijk aan de breedte van het teken "0" in het huidige font. Voor de meeste fonts levert `65ch` een regellengte op die dicht bij het optimum ligt.

Voor het goed doel project is dit bijzonder relevant: studenten ontwerpen een hero sectie, navigatie, CTA en testimonials. Testimonials en beschrijvingsblokken hebben smalle kolommen nodig. Een kolom met testimonials die de volledige paginabreedte beslaat leest slecht. Zijbalken en kaartjes werken beter met kortere regels.

### Legibility versus Readability

Hoewel de termen "legibility" en "readability" in het dagelijks taalgebruik door elkaar worden gebruikt, verwijzen ze in typografie naar twee verschillende kwaliteiten.

Legibility (herkenbaarheid) gaat over hoe goed individuele letters of tekens herkenbaar zijn. Een font heeft goede legibility als de letters duidelijk van elkaar te onderscheiden zijn. Problematische combinaties zijn de hoofdletter I (i), de kleine letter l (el) en het cijfer 1 in veel fonts die lijken op Arial: ze zijn visueel identiek. Een font met slechte legibility maakt het lezen van specifieke woorden of cijfers foutgevoelig. Dit is kritisch voor wachtwoorden, codes, productnames en kleine teksten. Fonts die specifiek zijn ontworpen voor schermen, zoals Inter en Roboto, besteden veel aandacht aan legibility.

Readability (leesbaarheid) is een kwaliteit van de typografische compositie als geheel, niet van het font zelf. Een tekst heeft goede readability als langere stukken tekst gemakkelijk en comfortabel gelezen kunnen worden. Dit wordt bepaald door de combinatie van font, grootte, line-height, regellengte, contrast met de achtergrond en de kolombreedte. Een font met uitstekende legibility kan toch slechte readability hebben als de regellengte te groot is of de line-height te krap.

Walter Tracy, typograaf en auteur van Letters of Credit, formuleerde het treffend: "Legibility is a quality of the type. Readability is a quality of the typographic arrangement." Dit onderscheid is nuttig voor studenten omdat het hen leert dat typografie twee niveaus heeft: de keuze van het font en de manier waarop je het inzet.

---

## Bronnen

### 1. Google Fonts Knowledge - fonts.google.com/knowledge

Google Fonts Knowledge is een gratis online bibliotheek van artikelen over typografie, geschreven door typografische experts en designers. De content is uitgebreid maar toegankelijk geschreven, met veel visuele voorbeelden. Voor studenten is het bijzonder nuttig vanwege de artikelen over font combinaties, het kiezen van het juiste font voor een doel en de technische achtergrond van fontkenmerken. De Knowledge-sectie is los van de fontgalerij te gebruiken. Aanbevolen startpunt: het artikel "Choosing type" en "Pairing typefaces".

### 2. Typewolf - typewolf.com

Typewolf is een curatiedienst die dagelijks websites laat zien die opvallende typografie gebruiken. Voor elke site is uitgelegd welke fonts gebruikt worden, inclusief alternatieven en gerelateerde fonts. De site werkt als inspiratiebron en als referentie: studenten kunnen zien hoe professionele designers fonts inzetten in echte projecten. Typewolf publiceert ook maandelijkse lijsten met trending fonts, wat handig is om up-to-date te blijven. Iets geavanceerder dan de andere bronnen, maar visueel sterk en inspirerend.

### 3. The Elements of Typographic Style (online versie) - webtypography.net

Robert Bringhurst's "The Elements of Typographic Style" wordt beschouwd als het standaardwerk van de typografie. De online versie op webtypography.net presenteert de kernprincipes van het boek, vertaald naar webcontext met CSS-voorbeelden. Voor docenten is dit een uitstekende referentie om theoretische onderbouwing te geven bij de regels die in de les worden behandeld. Voor studenten is het een verdieping die aangeeft dat de regels die ze leren niet willekeurig zijn, maar een rijke historische achtergrond hebben.

### 4. Practical Typography - practicaltypography.com

Matthew Butterick's Practical Typography is een online boek dat typografieregels op een directe en humoristische manier presenteert. Het richt zich op praktische toepassingen en bevat duidelijke do's en don'ts. Bijzonder nuttig zijn de secties over font keuze, regellengte en het gebruik van koppen. De schrijfstijl is toegankelijk voor beginners en de voorbeelden zijn helder. Het boek kost geld om volledig te lezen, maar de gratis secties zijn al zeer waardevol.

### 5. Type Scale generator - typescale.com

Typescale.com is een interactieve tool waarmee je een type scale kunt genereren op basis van een basisgrootte en een verhouding. De tool toont direct een preview van hoe de schaal eruitziet en geeft de CSS-waarden die je direct kunt gebruiken. Voor studenten is dit een uitstekend hulpmiddel om te begrijpen hoe een type scale werkt voordat ze hun eigen schaal handmatig instellen in Figma. De tool ondersteunt meerdere standaard schaalverhoudingen zoals Minor Third, Major Third en Perfect Fourth.

---

## Opdracht

### Goed Doel Website: Typografie (Les 3)

**Doel:** Studenten maken een coherent typografisch systeem voor hun charity website en verwerken dit in hun Figma wireframe.

**Deliverable:** Figma bestand met tekststijlen en bijgewerkte wireframe, gedeeld als link via de leeromgeving.

**Deadline:** Voor aanvang les 4.

**Stap 1: Font keuze**  
Kies twee Google Fonts die passen bij het thema van je charity website. Denk na over de sfeer die je wil uitstralen: een dierenasiel vraagt om warme, vriendelijke fonts; een mensenrechtenorganisatie vraagt om gezaghebbende, betrouwbare fonts. Gebruik fonts.google.com/knowledge voor inspiratie en onderbouwing. Noteer waarom je deze fonts hebt gekozen.

**Stap 2: Type scale in Figma**  
Maak minimaal zes tekststijlen aan in Figma met de volgende namen en instellingen:
- heading/h1: 48px Bold, line-height 1.2
- heading/h2: 36px Bold, line-height 1.25
- heading/h3: 24px SemiBold, line-height 1.3
- body/regular: 16px Regular, line-height 1.6
- body/small: 14px Regular, line-height 1.5
- label: 12px Medium, line-height 1.4

Gebruik de schuine streep in de naam om een maphierarchie aan te maken in het Figma stijlenpanel.

**Stap 3: Typografie in wireframe verwerken**  
Pas de typografie toe op de wireframe die in les 2 is gemaakt. Vervang generieke tekst door de nieuwe tekststijlen. Let op: de hero heeft een H1, sectietitels zijn H2, kaartkoppen zijn H3, lopende tekst is body/regular.

**Stap 4: Regellengte controleren**  
Controleer de regellengte van je langste tekstblok (waarschijnlijk de beschrijving in de hero of een tekstblok in de body). Telt meer dan 75 karakters per regel? Verklein de kolombreedte totdat de regellengte optimaal is.

**Stap 5: Contrast controleren**  
Gebruik een contrast checker (bijv. WebAIM Contrast Checker) om te bevestigen dat de tekst op de achtergrond voldoet aan WCAG AA. Body tekst vereist minimaal 4.5:1 contrast. Grote tekst (18px+ bold of 24px+ regular) vereist minimaal 3:1.

---

## Klassikale discussievragen

1. **Vertrouwen en typografie:** Welke fonts stralen meer vertrouwen uit voor een charity website, serif of sans-serif? Waarom denk je dat? Kun je een font bedenken dat totaal niet zou werken voor een serieuze goede doelen organisatie?

2. **Regellengte in de praktijk:** Open Wikipedia op je laptop. Is de tekstkolom breed of smal? Voelt het prettig om te lezen? Vergelijk dit met Medium.com. Wat is het verschil, en welke site leest beter? Hoe zou jij dit verklaren?

3. **Font als merkidentiteit:** Denk aan bekende merken zoals Apple, Google of IKEA. Welke fontkenmerken passen bij die merken? Als IKEA morgen een serif font zou gebruiken, wat zou dat communiceren over het merk?

4. **Accessibiliteit en design:** We hebben gezien dat WCAG een minimumcontrastratio van 4.5:1 eist. Is dit een beperking voor designers, of juist een hulpmiddel? Kun je creatief blijven binnen deze richtlijnen?

---

## Voorbereiding volgende les

### Les 4: Kleur en Contrast

**Onderwerpen:**
- WCAG contrast ratio's en toegankelijkheid
- Kleurharmonie: aanvullend, triadisch en analoog
- Semantische kleuren voor interface states (success, error, warning, info)
- Kleurpaletten opzetten in Figma met kleurstijlen

**Wat de docent moet voorbereiden:**

1. **Figma kleurpaletten demo:** Bereid een Figma bestand voor met een leeg kleurpalet op basis van de variabele-structuur: primary, primary-light, primary-dark, neutral-100 t/m neutral-900, success, warning, error. Studenten kunnen dit als template gebruiken.

2. **WCAG uitleg materiaal:** Bekijk de WCAG 2.1 richtlijnen op webaim.org. Bereid twee of drie voorbeelden voor van contrast dat net niet en net wel voldoet aan AA, zodat studenten het verschil visueel kunnen zien.

3. **Kleurtheorie opfrissen:** Bekijk de HSL kleurruimte (Hue, Saturation, Lightness). Figma werkt primair met HSL en studenten die dit begrijpen, werken veel sneller met kleurpaletten. Bereid een uitleg voor van hoe je met dezelfde hue maar verschillende lightness waarden een coherent palet bouwt.

4. **Coolors.co verkennen:** Ga naar coolors.co en genereer een paar paletten. Kijk hoe je paletten kunt exporteren naar verschillende formaten en hoe de lock-functie werkt. Studenten krijgen als huiswerk mee om een palet te kiezen; ze moeten dit kunnen importeren in de volgende les.

5. **Contrast checker tool:** Test de WebAIM Contrast Checker (webaim.org/resources/contrastchecker) en de Figma plugin "Contrast" (of "A11y - Color Contrast Checker"). Bereid een demo voor van hoe je contrast controleert in Figma.

6. **Voorbeelden van kleurgebruik in goede doelen websites:** Zoek drie voorbeelden van charity websites met opvallend en goed doordacht kleurgebruik. Denk aan Amnesty International (geel/zwart), WWF (groen/zwart) en Artsen zonder Grenzen (rood/wit). Bespreek hoe kleur de merkidentiteit versterkt.

**Tijdsindeling les 4 (indicatief):**
- 0:00 tot 0:15: Review van typografie opdrachten in kleine groepen
- 0:15 tot 0:45: Kleurtheorie en WCAG uitleg (slides)
- 0:45 tot 1:15: Live demo Figma kleurpaletten aanmaken
- 1:15 tot 1:45: Werktijd: studenten maken kleurpalet voor hun goed doel
- 1:45 tot 2:00: Afsluiting en toelichting volgende opdracht
