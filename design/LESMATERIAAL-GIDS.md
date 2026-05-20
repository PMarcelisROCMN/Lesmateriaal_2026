# Lesmateriaal Design — Generatiegids

Gebruik dit document als startpunt voor elke nieuwe sessie. Het beschrijft het curriculum, de designregels, de templatecode en de status per les.

---

## Curriculum overzicht

**Doelgroep:** Jaar 1 studenten, responsive websitedesign in Figma  
**Structuur:** 4 periodes × 6 lessen = 24 lessen totaal  
**Per les:** één Python script (`maak_presentatie.py`) + één PPTX + één `bronnen-en-documentatie.md`

### Projecten per periode
| Periode | Project | Kernthema's |
|---------|---------|-------------|
| 1 | Goed doel website (hero, nav, CTA, testimonials) | Figma basics, whitespace, typografie, kleur, componenten |
| 2 | Portfolio website (JS slider, about, projecten, responsive) | Responsive, navigatie, personal branding, design systems, animatie |
| 3 | PHP site met inlogsysteem (berichten, error handling, toasts) | Forms, feedback, error UX, toast/popup patterns |
| 4 | Thuisbezorgd-achtige bestelsite (winkelmandje, checkout, admin) | E-commerce UX, admin dashboard, complexe flows |

---

## Status

### Periode 1 — KLAAR ✓
| Les | Bestand | Status |
|-----|---------|--------|
| Les 1 | `periode-1/les-1/maak_presentatie_v2.py` → `01_intro-wat-is-design.pptx` | ✓ 18 slides |
| Les 2 | `periode-1/les-2/maak_presentatie.py` → `02_whitespace-alignment.pptx` | ✓ 16 slides |
| Les 3 | `periode-1/les-3/maak_presentatie.py` → `03_typografie-legibility.pptx` | ✓ 15 slides |
| Les 4 | `periode-1/les-4/maak_presentatie.py` → `04_kleur-contrast.pptx` | ✓ 15 slides |
| Les 5 | `periode-1/les-5/maak_presentatie.py` → `05_componenten-design-systems.pptx` | ✓ 15 slides |
| Les 6 | `periode-1/les-6/maak_presentatie.py` → `06_project-afronding-p1.pptx` | ✓ 14 slides |

### Periode 2 — NOG TE DOEN ✗
| Les | Bestand | Accent | Status |
|-----|---------|--------|--------|
| Les 7 | `periode-2/les-7/` → `07_responsive-mobile-first.pptx` | Indigo `(79,70,229)` → `(129,140,248)` | ✗ |
| Les 8 | `periode-2/les-8/` → `08_navigatie-sliders.pptx` | Sky `(14,165,233)` → `(56,189,248)` | ✗ |
| Les 9 | `periode-2/les-9/` → `09_portfolio-personal-branding.pptx` | Fuchsia `(192,38,211)` → `(232,121,249)` | ✗ |
| Les 10 | `periode-2/les-10/` → `10_design-systems-verdieping.pptx` | Amber `(217,119,6)` → `(251,191,36)` | ✗ |
| Les 11 | `periode-2/les-11/` → `11_animatie-micro-interacties.pptx` | Coral `(239,68,68)` → `(252,165,165)` | ✗ |
| Les 12 | `periode-2/les-12/` → `12_project-afronding-p2.pptx` | Emerald `(5,150,105)` → `(52,211,153)` | ✗ |

Mappen voor les 7-12 zijn al aangemaakt onder `C:\Users\Peter Marcelis\Documents\Lesmateriaal\design\periode-2\`.

---

## Designregels (STRIKT — overtreding veroorzaakte bugs)

1. **Geen rounded containers** — gebruik `MSO_AUTO_SHAPE_TYPE.RECTANGLE` voor alle kaarten/panelen. Nooit `ROUNDED_RECTANGLE` op grote vlakken.
2. **Oval alleen voor dots** — kleine badges, nummercirkels in lijsten.
3. **Nooit een platte rechthoek bovenop een rounded container** — dit geeft lelijke hoekconflicten.
4. **`card()` helper** = platte rechthoek met een gradiëntstrook van 7px links. Geen andere card-variant.
5. **Content past altijd binnen de container** — controleer left + width < slide breedte (Inches(13.33)) en top + height < slide hoogte (Inches(7.5)).
6. **Geen em-dashes (—) in zichtbare tekst** — gebruik een dubbele punt, komma of herformuleer. Em-dashes zijn toegestaan in sprekersnotities.
7. **8pt grid** — alle spacing in veelvouden van Inches(0.11) ≈ 8px.
8. **Heatmap/balken overflow check** — bereken MAX_BAR altijd als kaartbreedte minus padding, niet als absoluut getal.

---

## Volledige templatecode (kopieer dit per les, pas ACCENT1/ACCENT2 aan)

```python
"""
Les N: [Titel]
"""
from pptx import Presentation
from pptx.util import Inches, Pt
from pptx.dml.color import RGBColor
from pptx.enum.text import PP_ALIGN
from pptx.enum.shapes import MSO_AUTO_SHAPE_TYPE
from PIL import Image, ImageDraw
import numpy as np, io

NAVY=(6,14,44); NAVY2=(30,58,138); BLUE=(37,99,235); BLUE2=(59,130,246)
CYAN=(6,182,212); TEAL=(13,148,136); WHITE=(255,255,255); OFFWH=(248,250,252)
DARK=(15,23,42); BODY=(51,65,85); MUTED=(100,116,135)
GREEN=(22,163,74); RED=(220,38,38)
ACCENT1=(XX,XX,XX)  # lesspecifiek — zie tabel hierboven
ACCENT2=(XX,XX,XX)  # lesspecifiek

def rgb(c): return RGBColor(c[0],c[1],c[2])
SW,SH=Inches(13.33),Inches(7.5); BW,BH=1920,1080

def garr(w,h,c1,c2,d='diag'):
    x,y=np.linspace(0,1,w,dtype=np.float32),np.linspace(0,1,h,dtype=np.float32)
    if d=='h': t=np.tile(x,(h,1))
    elif d=='v': t=np.tile(y.reshape(-1,1),(1,w))
    else: xx,yy=np.meshgrid(x,y); t=xx*.55+yy*.45
    a=np.zeros((h,w,3),dtype=np.float32)
    for i in range(3): a[:,:,i]=c1[i]+(c2[i]-c1[i])*t
    return np.clip(a,0,255).astype(np.uint8)

def dark_bg(circ=True):
    img=Image.fromarray(garr(BW,BH,NAVY,NAVY2)).convert('RGBA')
    if circ:
        ov=Image.new('RGBA',(BW,BH),(0,0,0,0)); d=ImageDraw.Draw(ov)
        d.ellipse([BW-500,-180,BW+200,520],fill=(ACCENT1[0],ACCENT1[1],ACCENT1[2],20))
        d.ellipse([-60,BH-360,380,BH+100],fill=(ACCENT2[0],ACCENT2[1],ACCENT2[2],22))
        img=Image.alpha_composite(img,ov)
    return img.convert('RGB')

def light_bg():
    img=Image.fromarray(garr(BW,BH,OFFWH,WHITE)).convert('RGBA')
    ov=Image.new('RGBA',(BW,BH),(0,0,0,0)); d=ImageDraw.Draw(ov)
    d.ellipse([BW-580,-220,BW+180,540],fill=(ACCENT1[0],ACCENT1[1],ACCENT1[2],8))
    img=Image.alpha_composite(img,ov); return img.convert('RGB')

def buf(img):
    b=io.BytesIO(); img.save(b,format='PNG'); b.seek(0); return b

prs=Presentation(); prs.slide_width=SW; prs.slide_height=SH
BLANK=prs.slide_layouts[6]

def ns(bg=None):
    s=prs.slides.add_slide(BLANK)
    if bg:
        p=s.shapes.add_picture(buf(bg),0,0,SW,SH)
        t=s.shapes._spTree; t.remove(p._element); t.insert(2,p._element)
    else:
        s.background.fill.solid(); s.background.fill.fore_color.rgb=rgb(WHITE)
    return s

def box(s,l,t,w,h,c,border=None):
    sh=s.shapes.add_shape(MSO_AUTO_SHAPE_TYPE.RECTANGLE,l,t,w,h)
    sh.fill.solid(); sh.fill.fore_color.rgb=rgb(c)
    if border: sh.line.color.rgb=rgb(border); sh.line.width=Pt(0.75)
    else: sh.line.fill.background()
    return sh

def gbox(s,l,t,w,h,c1,c2,angle=90):
    sh=s.shapes.add_shape(MSO_AUTO_SHAPE_TYPE.RECTANGLE,l,t,w,h)
    sh.fill.gradient(); st=sh.fill.gradient_stops
    st[0].position=0.0; st[0].color.rgb=rgb(c1)
    st[1].position=1.0; st[1].color.rgb=rgb(c2)
    sh.fill.gradient_angle=angle; sh.line.fill.background(); return sh

def dot(s,cx,cy,r,c):
    sh=s.shapes.add_shape(MSO_AUTO_SHAPE_TYPE.OVAL,cx-r,cy-r,r*2,r*2)
    sh.fill.solid(); sh.fill.fore_color.rgb=rgb(c); sh.line.fill.background()

def lbl(s,text,l,t,w,h,size=18,c=BODY,bold=False,italic=False,align=PP_ALIGN.LEFT):
    bx=s.shapes.add_textbox(l,t,w,h); tf=bx.text_frame; tf.word_wrap=True
    p=tf.paragraphs[0]; p.alignment=align; r=p.add_run()
    r.text=text; r.font.name="Calibri"; r.font.size=Pt(size)
    r.font.bold=bold; r.font.italic=italic; r.font.color.rgb=rgb(c)

def blist(s,items,l,t,w,h,size=18,c=BODY,gap=7):
    bx=s.shapes.add_textbox(l,t,w,h); tf=bx.text_frame; tf.word_wrap=True
    for i,item in enumerate(items):
        p=tf.paragraphs[0] if i==0 else tf.add_paragraph()
        p.space_before=Pt(gap); r=p.add_run()
        r.text=f"  {chr(8226)}  {item}"; r.font.name="Calibri"
        r.font.size=Pt(size); r.font.color.rgb=rgb(c)

def notitie(s,t): s.notes_slide.notes_text_frame.text=t

def pnr(s,n,tot,light=False):
    lbl(s,f"{n} / {tot}",Inches(12.5),Inches(7.1),Inches(0.8),Inches(0.35),
        size=11,c=(180,180,200) if light else MUTED,align=PP_ALIGN.RIGHT)

def hero_hdr(s,title,sub=None):
    lbl(s,title,Inches(1.0),Inches(0.5),Inches(11.3),Inches(1.0),size=36,c=WHITE,bold=True)
    gbox(s,Inches(1.0),Inches(1.42),Inches(2.0),Inches(0.08),ACCENT1,ACCENT2,angle=0)
    if sub: lbl(s,sub,Inches(1.0),Inches(1.55),Inches(11.3),Inches(0.42),size=15,c=(200,200,240),italic=True)

def page_hdr(s,title,sub=None):
    lbl(s,title,Inches(1.0),Inches(0.5),Inches(11.3),Inches(0.95),size=34,c=DARK,bold=True)
    gbox(s,Inches(1.0),Inches(1.38),Inches(1.8),Inches(0.08),ACCENT1,ACCENT2,angle=0)
    if sub: lbl(s,sub,Inches(1.0),Inches(1.5),Inches(11.3),Inches(0.38),size=14,c=MUTED,italic=True)

def card(s,l,t,w,h,accent=None,bg=OFFWH):
    a=accent if accent else ACCENT1
    STRIP=Inches(0.07)
    box(s,l,t,w,h,bg,border=(215,225,240))
    gbox(s,l,t,STRIP,h,a,ACCENT2,angle=270)
    return l+STRIP+Inches(0.18), t+Inches(0.14), w-STRIP-Inches(0.35)

def col_kop(s,l,t,w,h,text,c):
    box(s,l,t,w,h,c)
    lbl(s,text,l,t+Inches(0.07),w,h-Inches(0.07),size=20,c=WHITE,bold=True,align=PP_ALIGN.CENTER)

# ── SLIDES HIER ──────────────────────────────────────────
# ...

OUT=r"C:\Users\Peter Marcelis\Documents\Lesmateriaal\design\periode-X\les-N\NN_bestandsnaam.pptx"
prs.save(OUT)
print(f"Klaar: {len(prs.slides)} slides")
```

---

## Slidestructuur per les (standaard patroon)

| Positie | Slidetype | Achtergrond |
|---------|-----------|-------------|
| 1 | Titelslide | `dark_bg(True)` + ACCENT-strip links |
| 2 | Agenda (6 items, gekleurde dots) | `light_bg()` |
| 3-4 | Theorie intro (split of bullets) | `ns()` of `dark_bg(False)` |
| 5-8 | Diepere theorie + visuele demo's | afwisselend `light_bg()` / `dark_bg(False)` |
| 9-10 | Figma hands-on (stap-voor-stap) | `dark_bg(False)` + `hero_hdr` |
| 11 | Project toepassing | `dark_bg(False)` |
| 12 | Live analyse / klassikale oefening | `light_bg()` |
| 13 | Bronnen (5 cards) | `light_bg()` |
| 14 | Opdracht | `dark_bg(False)` + accent-strip links |
| 15 | Volgende les | `dark_bg(True)` + accent-strip links |

Afsluitende lessen (les 6, les 12) hebben 14 slides en eindigen met een successlide.

---

## Inhoud per resterende les

### Les 7 — Responsive Design en Mobile First
**Accent:** Indigo `(79,70,229)` → `(129,140,248)`  
**PPTX:** `07_responsive-mobile-first.pptx` · 16 slides  
**Project:** Portfolio website (periode 2 kickoff)  
**Kernthema's:**
- Wat is responsive design, 60% mobiel verkeer
- Mobile first strategie vs desktop first
- Breakpoints: 375 / 640 / 768 / 1024 / 1280 / 1536px
- Fluid typography: rem, clamp(), vw
- Figma: drie frames naast elkaar (mobile/tablet/desktop)
- Grid per breakpoint: 4/8/12 kolommen

**Figma hands-on:** frame 375×812 mobile, 768×1024 tablet, 1440×900 desktop naast elkaar

**Opdracht:** wireframe home/mobile en home/desktop in grijstinten, met 4-koloms en 12-koloms grid

**Volgende les preview:** navigatiepatronen en sliders

**Bronnen:**
- web.dev/responsive-web-design-basics
- developer.mozilla.org/docs/Web/CSS/CSS_media_queries
- every-layout.dev
- figma.com/resource-library/responsive-design
- abookapart.com/products/mobile-first

---

### Les 8 — Navigatiepatronen en Sliders
**Accent:** Sky `(14,165,233)` → `(56,189,248)`  
**PPTX:** `08_navigatie-sliders.pptx` · 15 slides  
**Project:** Portfolio website — navigatie en slider  
**Kernthema's:**
- Desktop nav: horizontaal, mega menu, sidebar
- Mobiel nav: hamburger, bottom nav, tab bar
- Hamburger menu: ontwerpchecklist (44×44px taptarget, overlay, sluitknop)
- Slider anatomie: pijlen, dot-indicators, swipe gesture, max 5 slides
- Toegankelijkheid: ARIA labels, keyboard navigatie, prefers-reduced-motion
- Figma prototype: slider (3 frames, Smart Animate) en hamburger menu (overlay)

**Figma hands-on:**
1. Slider prototype: 3 slide-frames verbinden met On Click, Move In from Right, 300ms Ease Out
2. Hamburger prototype: nav/closed → nav/open als overlay, slide in from right

**Opdracht:** desktop nav + mobiele hamburger ontwerpen + slider prototype met 3 werkende slides

**Bronnen:**
- nngroup.com/articles/hamburger-menus
- smashingmagazine.com (zoek 'carousel design')
- w3.org/WAI/ARIA/apg/patterns
- figma.com/prototyping
- swiperjs.com

---

### Les 9 — Portfolio Design en Personal Branding
**Accent:** Fuchsia `(192,38,211)` → `(232,121,249)`  
**PPTX:** `09_portfolio-personal-branding.pptx` · 15 slides  
**Project:** Portfolio website — about pagina en projectenpagina  
**Kernthema's:**
- Wat maakt een sterk portfolio (max 5-8 projecten, kwaliteit boven kwantiteit)
- Personal branding: stijl, toon en focus
- About pagina structuur: naam, foto, bio (3-5 zinnen), skills, contact CTA
- Projectenpagina: thumbnail, naam, categorie badge, 2-zinnen beschrijving
- Projectpresentatie: probleem → process → resultaat → lessen
- Consistentie over paginas: kleur, typografie, componenten uniform
- Figma multi-pagina: Pages aanmaken (Home/About/Projecten/Sitemap)

**Figma hands-on:** about pagina wireframen in mobile en desktop; foto naast bio op desktop (2-koloms)

**Opdracht:** about pagina + projectenpagina wireframe + stijlgids-page in Figma

**Bronnen:**
- dribbble.com/tags/portfolio
- behance.net
- smashingmagazine.com (zoek 'designer portfolio')
- webflow.com/made-in-webflow
- lapa.ninja

---

### Les 10 — Design Systems Verdieping
**Accent:** Amber `(217,119,6)` → `(251,191,36)`  
**PPTX:** `10_design-systems-verdieping.pptx` · 15 slides  
**Project:** Portfolio website — design system voltooien  
**Kernthema's:**
- Design tokens: kleur, spacing, typografie als variabelen met naam
- Naamconventies: `color/brand/primary-500`, `spacing/component/padding-md`
- Figma Variables: Collections (Primitives + Semantic), modes (Light/Dark)
- Component library organiseren: Foundations / Components / Patterns
- Componenten documenteren: beschrijving, Do & Don't, annotaties
- Handoff: Inspect panel toont CSS properties; tokens zijn developer-vriendelijk
- Real-world voorbeelden: Material Design 3, IBM Carbon, Tailwind, Apple HIG

**Figma hands-on:** Variables collection aanmaken, kleurtoken toepassen op knop-component

**Opdracht:** Variables collection (6 kleurtokens), beschrijvingen bij componenten, 'System' page in Figma

**Bronnen:**
- design-tokens.github.io
- carbondesignsystem.com
- figma.com/best-practices/variables
- tokens.studio
- supernova.io

---

### Les 11 — Animatie en Micro-interacties
**Accent:** Coral `(239,68,68)` → `(252,165,165)`  
**PPTX:** `11_animatie-micro-interacties.pptx` · 15 slides  
**Project:** Portfolio website — animaties toevoegen  
**Kernthema's:**
- Vier doelen van animatie: feedback, oriëntatie, aandacht, persoonlijkheid
- Regels: 150-300ms micro, 400-600ms macro, subtiel, consistent, purposeful
- Micro-interacties: hover, click, toggle, loading (Dan Saffer definitie)
- Easing curves: lineair (vermijden), ease-out (objecten die binnenkomen), ease-in-out (page transitions)
- Spring animatie: stiffness, damping, mass — iOS/Linear/Material
- Figma Smart Animate: zelfde laagnamen in twee frames = soepele overgang
- Loading states: spinner vs skeleton screen vs progress bar
- Toegankelijkheid: prefers-reduced-motion, geen autoplay zonder pauzeknop, WCAG 2.3.3

**Figma hands-on:**
- Hover state op knop: variant 'default' → variant 'hovered', 150ms Ease Out
- Slide-in hamburger overlay animatie

**Opdracht:** hover state projectkaart + CTA knop, werkend slider prototype, hamburger animatie

**Bronnen:**
- material.io/design/motion
- microinteractions.com (Dan Saffer boek)
- figma.com/prototyping
- developer.mozilla.org/docs/Web/CSS/animation
- easings.net

---

### Les 12 — Project Afronding Periode 2
**Accent:** Emerald `(5,150,105)` → `(52,211,153)`  
**PPTX:** `12_project-afronding-p2.pptx` · 14 slides  
**Project:** Portfolio website — review en oplevering  
**Kernthema's:**
- Terugblik 6 lessen periode 2
- Portfolio checklist: paginas (Home/About/Projecten), design (tokens, typografie, WCAG AA), interactie (slider, hamburger, hover states)
- Responsive check per breakpoint: mobile 375px, tablet 768px, desktop 1440px
- Slider review: 3 slides, pijlen, dots, 300ms animatie, geen autoplay
- Navigatie review: desktop horizontaal + mobiel hamburger overlay
- Design system check: alle kleuren als tokens, componenten gedocumenteerd, handoff klaar
- FIST peer review framework (Feit / Impact / Suggestie / Toelichting)
- Figma presentatiemodus: aparte 'Presentatie' page, prototype flow, link delen
- Beoordelingscriteria: Design kwaliteit 40%, Technische uitvoering 35%, Presentatie 25%
- Volgende periode preview: PHP site met inlogsysteem en error handling

**Bronnen:**
- refactoringui.com
- lawsofux.com
- shiftnudge.com
- figma.com/community
- designcode.io

---

## Structuur bronnen-en-documentatie.md

Elk documentatiebestand volgt deze structuur (in het Nederlands):

```markdown
# Les N: [Titel]

## Theorie
[Per concept 3-4 alinea's diepere uitleg dan de slides: de 'waarom' achter elk principe,
verwijzingen naar onderzoek of bekende bronnen, praktische context voor de docent.]

## Bronnen
[Alle 5 links uit de presentatie, met per bron:]
- **Naam** — [URL]  
  Wat je er vindt en waarom het nuttig is voor studenten of docenten.

## Opdracht
[Volledige opdrachtomschrijving: wat moeten studenten maken, wat zijn de eisen,
hoe wordt het ingeleverd, wat is de deadline.]

## Klassikale discussievragen
1. ...
2. ...
3. ...
4. ...

## Voorbereiding volgende les
[Wat de docent klaar moet hebben of moet lezen voor de volgende les.]
```

---

## Leskleur per les (snel overzicht)

| Les | Kleur | ACCENT1 | ACCENT2 |
|-----|-------|---------|---------|
| 1 | Blauw | `(37,99,235)` | `(59,130,246)` |
| 2 | Teal | `(13,148,136)` | `(6,182,212)` |
| 3 | Paars | `(124,58,237)` | `(167,139,250)` |
| 4 | Oranje | `(234,88,12)` | `(251,191,36)` |
| 5 | Groen | `(22,163,74)` | `(74,222,128)` |
| 6 | Roze | `(225,29,72)` | `(251,113,133)` |
| 7 | Indigo | `(79,70,229)` | `(129,140,248)` |
| 8 | Sky | `(14,165,233)` | `(56,189,248)` |
| 9 | Fuchsia | `(192,38,211)` | `(232,121,249)` |
| 10 | Amber | `(217,119,6)` | `(251,191,36)` |
| 11 | Coral | `(239,68,68)` | `(252,165,165)` |
| 12 | Emerald | `(5,150,105)` | `(52,211,153)` |

---

## Werkwijze voor een nieuwe sessie

1. Lees dit bestand (`LESMATERIAAL-GIDS.md`)
2. Controleer de statustabel bovenaan — welke lessen zijn al klaar?
3. Genereer de eerstvolgende ontbrekende les:
   - Kopieer de templatecode
   - Pas `ACCENT1`/`ACCENT2` aan per de kleurentabel
   - Schrijf slides op basis van de lesinhoud in dit document
   - Voeg `prs.save(...)` toe met het juiste pad
   - Voer het script uit: `python maak_presentatie.py`
4. Schrijf daarna `bronnen-en-documentatie.md` in dezelfde map
5. Werk de statustabel in dit bestand bij (✗ → ✓)

**Let op:** voer altijd het script uit en bevestig "Klaar: N slides" voordat je doorgaat naar de volgende les.
