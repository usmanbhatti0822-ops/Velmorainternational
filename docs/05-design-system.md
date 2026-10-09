# 05 — Design System (Provisional)

> Colours and fonts are **provisional until the logo is final**. All values are stored as design tokens (Tailwind theme / CSS variables), so changing the brand colour later is a one-file edit.

## 1. Brand Personality
Trusted · Rooted in farming · Premium export house · Clean and corporate, with warm natural tones. Avoid cluttered, "keyword-stuffed" or cheap-marketplace looks.

## 2. Colour Scheme

| Role | Name | Hex | Use |
|------|------|-----|-----|
| Primary | Velmora Forest | `#14503A` | Header, buttons, headings, footer |
| Primary dark | Deep Field | `#0C3526` | Hover, dark sections |
| Primary light | Sage | `#E3EEE7` | Soft backgrounds, tags |
| Accent | Harvest Gold | `#C8A04A` | CTA highlights, icons, underlines |
| Accent dark | Antique Gold | `#A9822F` | Gold hover, text on light |
| Background | Cream | `#FAF7F0` | Page background |
| Surface | White | `#FFFFFF` | Cards |
| Text | Charcoal | `#1F2A26` | Body text |
| Muted text | Stone | `#5E6B65` | Secondary text |
| Border | Mist | `#DDE3DF` | Dividers, inputs |
| Earth (optional) | Leather Brown | `#7A4B2A` | Leather division accent |
| Success | Green | `#1E8E5A` | Success states |
| Warning | Amber | `#D98E04` | Warnings |
| Danger | Red | `#C0392B` | Errors |
| WhatsApp | WhatsApp Green | `#25D366` | Floating button only |

**Division accents** (small tags, hover borders): Rice `#C8A04A`, Wheat `#D9B26B`, Grains `#8A9A5B`, Dry Fruits `#8B3A3A`, Leather `#7A4B2A`, Textile `#2F5D8A`.

**Contrast:** Charcoal on Cream ≈ 13:1; white on Forest ≈ 9:1; both exceed WCAG AA. Do not place Gold text on Cream for body copy (use Antique Gold or Forest).

**Dark mode:** not in Phase 1.

### Tailwind tokens (Tailwind CSS 4, already added in `resources/css/app.css`)
```css
@theme {
  --color-forest: #14503A;  --color-deep: #0C3526;  --color-sage: #E3EEE7;
  --color-gold: #C8A04A;    --color-antique: #A9822F; --color-cream: #FAF7F0;
  --color-charcoal: #1F2A26; --color-stone: #5E6B65;  --color-mist: #DDE3DF;
  --color-leather: #7A4B2A;
}
```
Use as `bg-forest`, `text-gold`, `border-mist`, etc.

## 3. Typography

| Use | Font | Fallback |
|-----|------|----------|
| Headings | **Playfair Display** (or Cormorant Garamond) | Georgia, serif |
| Body/UI | **Inter** | system-ui, sans-serif |
| Arabic | **Cairo** or Noto Naskh Arabic | Tahoma |
| Chinese (if added) | Noto Sans SC | system |

Self-host fonts (WOFF2, `font-display: swap`) for speed and privacy.

Scale: H1 48–64 px · H2 36–44 · H3 24–28 · Body 16–18 · Small 14. Line height 1.6 for body. Max line length ~70 characters.

## 4. Layout and Spacing
12-column grid, container max 1280 px, 8-px spacing system (8/16/24/32/48/64/96). Section vertical padding 80–120 px desktop, 56 px mobile. Radius: 12 px cards, 999 px pills. Shadows soft and low (`0 8px 30px rgba(20,80,58,.08)`). Mobile-first breakpoints: 640 / 768 / 1024 / 1280.

## 5. Components

- **Header:** logo left, mega-menu for Products (6 divisions with icons), language switcher, primary button "Request a Quote"; sticky with shrink on scroll.
- **Buttons:** Primary (Forest), Secondary (outline), Accent (Gold) for key CTAs.
- **Division card:** image, name, short line, arrow; hover = image zoom 1.05 and arrow slide.
- **Product card:** image, name, 2 key specs, "Quote" and "Chat".
- **Spec table:** zebra rows, sticky first column on mobile.
- **Package card:** icon, who it's for, bullet points, CTA.
- **Stats:** large numbers with label, count-up once.
- **Timeline:** 4 steps (Sourcing → Quality Check → Packing → Shipping) with progress line.
- **Marquee strips:** brand/partner logos (only real ones); certification strip stays hidden until enabled.
- **Forms:** floating labels, inline validation, clear error text, file drop zone.
- **Floating actions (bottom corner):** chat bubble (primary), WhatsApp button; do not overlap on mobile.
- **Footer:** dark Forest, 4 columns, contact, socials, legal.

## 6. Motion and Animation Guidelines

| Effect | Spec |
|--------|------|
| Page load | Hero text fade-up 600 ms, stagger 100 ms |
| Scroll reveal | Fade + 24 px translate, 500–700 ms, ease-out, trigger once at 15% visible |
| Counters | 1.5 s ease-out count-up once |
| Hover cards | Image scale 1.05, 400 ms; lift 4 px |
| Hero | Slow Ken Burns (scale 1→1.08 over 12 s) or muted looping video |
| Timeline | Line draws as user scrolls |
| Map pins | Soft pulse every 3 s |
| Chat widget | Slide-up 250 ms, typing indicator dots |
| Buttons | 200 ms colour/shadow transition |

Rules: respect `prefers-reduced-motion` (disable non-essential motion), animate only `transform` and `opacity`, no auto-playing audio, keep total JS small.

## 7. Imagery Direction
Real photos of farms, mills, warehouses, ports, containers, people at work. Consistent warm colour grade. Product shots on neutral cream/white background. Video: 10–20 s loops, compressed, with poster image. No stock images with watermarks.

## 8. Tone of Voice
Professional, confident, simple English. Short sentences. Facts over hype: "20+ years", "exports to X countries" only if verified.

## 9. Logo Direction (Phase 1 — Brand)
Wordmark "VELMORA" with a simple symbol (grain ear, leaf or compass/globe hint) that works in one colour, on dark and light, and as a small favicon. Deliverables: primary, horizontal, icon-only, one-colour, favicon, social avatar, letterhead, visiting card, envelope, catalog cover.

## 10. Catalog Direction
A4, cover with brand pattern, company intro, division spreads, product pages with spec tables and packaging, private-label page, contact page. One combined catalog + one per division. Print-ready (CMYK, 3 mm bleed) and web PDF (under 10 MB).
