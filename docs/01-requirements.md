# 01 — Requirement Gathering

## 1. Business Context

| Item | Detail |
|------|--------|
| Company | Velmora International (Private) Limited |
| Registration | Registered in Pakistan |
| Experience | 20+ years in farming and agri/commodity business |
| Business | Export of rice, wheat, grains, dry fruits, leather and leather goods, textiles and garments |
| Domain | velmoraintl.com |
| Model | B2B export; supplies all customer types (importers, distributors, wholesalers, retailers, private-label brands, government/institutional buyers) in any quantity from sample lots to full container loads |
| Certifications | Held, but **not displayed on the website for now** |

## 2. Project Goals

1. Present Velmora as a trustworthy, established Pakistani exporter with two decades of experience.
2. Showcase all six product divisions with strong imagery and clear specifications.
3. Convert visitors into leads through **Request a Quote (RFQ)**, **live chat**, WhatsApp and email.
4. Let the company manage everything (products, packages, content, inquiries, chats) from an **admin panel** without a developer.
5. Rank on Google for export-related searches and support multiple languages.
6. Build a base that can grow (blog, catalogs, buyer portal) without rebuilding.

### Success Metrics (to set targets with client)
- Monthly RFQs and chat conversations started
- Chat first-response time (target: under 5 minutes in business hours)
- Catalog downloads
- Organic traffic and ranking for target keywords
- Page speed: LCP < 2.5 s on mobile

## 3. Stakeholders and Users

| Role | Description | Main needs |
|------|-------------|-----------|
| International buyer | Importer or distributor abroad | Find products, specs, packaging; ask price; talk to someone fast |
| Local/regional buyer | Wholesaler, retailer, brand | Same, plus small quantities and private label |
| Company owner/director | Decision maker | Dashboard of leads, brand credibility |
| Sales/export staff | Handles inquiries and chat | Inbox for RFQs and chats, notes, follow-up |
| Content manager | Updates products and pages | Easy editing, translations |
| Super admin | Full control | Users, roles, settings, backups |
| Developer/agency | Maintains system | Clean code, logs, deployment |

## 4. Product Scope

| Division | Examples (confirm exact list with client) |
|----------|--------------------------------------------|
| Rice | Basmati (1121, Super Kernel), IRRI-6, IRRI-9, parboiled, sella, broken |
| Wheat | Milling wheat, wheat flour, bran (if applicable) |
| Grains | Maize, pulses, millet, sesame and other grains the company supplies |
| Dry Fruits | Almonds, dates, walnuts, raisins, apricots, pistachios, etc. |
| Leather | Leather goods such as jackets, gloves, bags and wallets; raw/finished leather if applicable |
| Textile & Garments | Towels, bedding, T-shirts, hoodies, uniforms, private-label garments |

## 5. Supply Model: "Any Customer, Any Quantity" and Packages

The site must communicate that Velmora supplies **every type of buyer**, in **flexible volumes**. This is handled by **Supply Packages**, a manageable list in the admin panel:

| Package | Intended customer | Typical offer (placeholders — client to confirm) |
|---------|-------------------|--------------------------------------------------|
| Sample | New buyers testing quality | Small sample lots, courier shipping |
| Retail Pack | Supermarkets, small importers | Consumer packs (e.g. 1–10 kg) |
| Wholesale | Distributors, wholesalers | Bags/cartons in pallets |
| Bulk / Container | Importers, institutions | FCL/LCL container loads, bulk bags |
| Private Label / OEM | Brands | Customer-branded packaging, custom specs, garments/leather made to design |
| Long-term Contract | Regular buyers | Scheduled shipments, fixed terms |

Rules: no fixed prices shown (price on request); MOQ per product is optional and editable; packaging sizes per product are managed through packaging options (see ERD).

## 6. Functional Requirements

### Public website
| ID | Requirement | Priority |
|----|-------------|----------|
| FR-01 | Home page with hero, divisions, why-us, process, stats, packages, CTA | Must |
| FR-02 | About, Our Story (20+ years), Mission/Vision, Team (optional) | Must |
| FR-03 | Division and product listing with filters, search | Must |
| FR-04 | Product detail: images, description, specification table, packaging options, origin, MOQ, "Request Quote" | Must |
| FR-05 | Supply Packages page | Must |
| FR-06 | RFQ form (name, company, country, email, phone/WhatsApp, product(s), quantity, package, destination port, message, attachments) | Must |
| FR-07 | **Live chat** (see doc 06) | Must |
| FR-08 | Contact page with form, map, WhatsApp, email | Must |
| FR-09 | Catalog PDF download (per division and combined), optionally gated by email | Should |
| FR-10 | Multi-language (English first; Arabic with RTL; others later) | Should |
| FR-11 | Blog/News and Gallery | Could |
| FR-12 | Certifications page and logos, **built but hidden** until client enables | Should |
| FR-13 | Export markets world map with pins | Should |
| FR-14 | Sticky header, floating WhatsApp button, cookie notice | Must |
| FR-15 | SEO: meta, Open Graph, sitemap.xml, robots, schema.org, clean URLs | Must |
| FR-16 | Spam protection on all forms (honeypot, rate limit, reCAPTCHA/Turnstile) | Must |

### Admin panel
| ID | Requirement | Priority |
|----|-------------|----------|
| AD-01 | Secure login, roles and permissions (Super Admin, Sales, Content, Chat Agent) | Must |
| AD-02 | CRUD for divisions, products, specs, images, packaging, packages | Must |
| AD-03 | Inquiry (RFQ) inbox: status, assignment, notes, attachments, reply by email, export to CSV | Must |
| AD-04 | **Chat inbox**: live conversations, reply, assign, transfer, close, transcripts, canned replies | Must |
| AD-05 | Page/section content editing, banners, hero slides | Must |
| AD-06 | Translation management per language | Should |
| AD-07 | Media library, catalog PDF manager | Must |
| AD-08 | Settings: company info, social links, business hours, email/SMTP, WhatsApp, SEO defaults, certification visibility switch | Must |
| AD-09 | Dashboard: new RFQs, open chats, top products, leads by country | Should |
| AD-10 | Notifications (email, browser sound/badge, optional WhatsApp/Telegram alert) | Should |
| AD-11 | Activity log and backups | Should |

## 7. Non-Functional Requirements

| Area | Requirement |
|------|-------------|
| Performance | LCP < 2.5 s mobile; images in WebP/AVIF; lazy loading; caching; CDN |
| Security | HTTPS, CSRF, validation, hashed passwords, 2FA for admins, file-type checks on uploads, rate limiting, security headers |
| Availability | Target 99.5%+; automated daily DB backup and weekly file backup |
| Scalability | Queue workers for email; cache; DB indexes; can move to larger VPS |
| Accessibility | WCAG 2.1 AA basics: contrast, keyboard navigation, alt text |
| Compatibility | Latest 2 versions of Chrome, Edge, Safari, Firefox; iOS and Android |
| SEO | Server-rendered pages, hreflang for languages, structured data |
| Privacy | Cookie notice, privacy policy, chat data retention policy |
| Maintainability | Laravel conventions, Git, automated tests for critical flows, documentation |

## 8. Constraints and Assumptions

- Backend must be PHP (Laravel chosen).
- Certifications are not shown at launch; stats shown only if real and approved.
- Real-time chat needs a persistent process (Laravel Reverb), which works best on a VPS. A polling fallback is designed for shared hosting.
- Logo and brand colours are pending; the visual identity in doc 05 is provisional.
- Photos, product specs and company text come from the client; the agency can write and edit copy.

## 9. Risks

| Risk | Impact | Mitigation |
|------|--------|-----------|
| Six categories look unfocused | Weak brand message | Hero focus on one category, others as divisions |
| Content delays | Launch delay | Content checklist (doc 07), placeholder content |
| Logo changes colours late | UI rework | Finalise logo first or use design tokens (single file to update) |
| Chat unanswered at night | Lost leads | Business hours, offline form, email/WhatsApp alerts |
| Shared hosting limits | Chat/queues unreliable | Use VPS; fallback to polling |
| Spam | Inbox noise | Honeypot, rate limit, Turnstile |
