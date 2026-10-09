# 02 — Product Requirements Document (PRD)

## 1. Product Overview
**Product:** Velmora International corporate export website with admin panel and live chat.
**Vision:** Be the first place an international buyer looks to understand Velmora's range, trust the company, and start a conversation within minutes.
**Positioning:** "Two decades of farming and export experience. From farm to port, in any quantity."  *(tagline draft — client to approve)*

## 2. Personas

1. **Ahmed, importer (UAE):** wants Basmati price for 1 container, needs specs, packaging, fast reply on WhatsApp/chat.
2. **Li, distributor (China):** wants sesame/dry fruits, needs Chinese or clear English, MOQ and samples.
3. **Sara, brand owner (EU):** wants private-label garments or leather goods, needs OEM details.
4. **Bilal, Velmora sales manager:** wants all leads and chats in one place, with follow-up status.

## 3. Sitemap

```
Home
├── About Us (Story, Mission & Vision, Why Velmora, Team*)
├── Products (Divisions)
│   ├── Rice            → product pages
│   ├── Wheat           → product pages
│   ├── Grains          → product pages
│   ├── Dry Fruits      → product pages
│   ├── Leather         → product pages
│   └── Textile & Garments → product pages
├── Supply & Packages (Sample, Retail, Wholesale, Bulk/Container, Private Label, Contract)
├── Private Label & OEM
├── Quality & Process (Sourcing → Quality Check → Packing → Shipping)
├── Export Markets (map)
├── Catalog (downloads)
├── Certifications*  (built, hidden at launch)
├── Gallery / Blog*  (optional)
├── Request a Quote
├── Contact
└── Legal (Privacy Policy, Terms, Cookie Policy)
```
`*` optional or hidden at launch.

## 4. Page Specifications

### 4.1 Home
1. Hero: full-width slider or slow video (farm, port, containers), headline, 2 CTAs: **Request a Quote** and **Download Catalog**.
2. Trust strip: "20+ Years" and other **approved** facts (counters animate once on view).
3. Divisions grid: 6 cards with hover zoom and arrow slide.
4. Featured products (admin selected).
5. Why Velmora: farming roots, quality control, flexible quantities, on-time shipping.
6. Supply Packages overview: 6 cards.
7. Process timeline.
8. Export markets map.
9. Private label banner.
10. Latest news/blog (optional).
11. Final CTA band: RFQ + chat + WhatsApp.
12. Footer: links, contact, socials, newsletter.

### 4.2 Division page
Banner, intro, filter and sort, product cards (image, name, short spec, "Quote" button), division FAQ, CTA.

### 4.3 Product page
Image gallery, name, short description, **specification table** (e.g. moisture, grain length, grade; for garments: GSM, sizes, fabric), **packaging options** (list from admin), origin, MOQ, lead time (optional), supply packages available, **Request Quote** (pre-fills product) and **Chat about this product** (sends product context to chat), related products, FAQ.

### 4.4 Supply & Packages
Explains "we serve every buyer": table of packages, who each suits, how ordering works (inquiry → quotation → sample → contract → shipment), shipping terms placeholders (FOB, CIF, etc. — client to confirm), CTA.

### 4.5 Request a Quote
Fields: full name, company, designation, email, phone/WhatsApp, country, product(s) (multi-line items with product, quantity + unit, package), destination port, delivery timeline, notes, attachment (PDF/image, max 10 MB). Success page with reference number and auto-reply email.

### 4.6 Contact
Form, address, phone, WhatsApp, email, map, business hours, time zone note.

## 5. Feature List with User Stories and Acceptance Criteria

| ID | User story | Acceptance criteria |
|----|-----------|---------------------|
| US-01 | As a buyer, I can browse products by division and filter | Filters work without page break; URL is shareable; works on mobile |
| US-02 | As a buyer, I can see a product's spec and packaging | Spec table and packaging list render from admin data |
| US-03 | As a buyer, I can submit an RFQ with multiple products | Validation errors shown; saved in DB; email to sales and auto-reply to buyer; reference number shown |
| US-04 | As a buyer, I can start a chat without registering | Widget asks name + email (phone optional); message delivered to admin in under 2 s; history persists on page reload for the same device |
| US-05 | As a buyer, if nobody is online, I can leave a message | Offline form creates a conversation marked "offline"; email alert sent to admin |
| US-06 | As sales, I see new chats and RFQs instantly | Sound + badge + browser notification; unread counter |
| US-07 | As sales, I reply from the admin panel and attach files | Buyer sees reply in real time; files scanned for type/size |
| US-08 | As admin, I add a product with specs, photos and packaging | Product live after publish; appears in search and sitemap |
| US-09 | As admin, I toggle certifications visibility | When off, no certification text, logos or page links appear anywhere |
| US-10 | As a buyer, I can switch language | Content, URLs and direction (RTL for Arabic) change; hreflang set |
| US-11 | As a buyer, I can download catalogs | File served; download logged; optional email gate |
| US-12 | As admin, I view lead reports | Dashboard shows RFQs and chats by date, country, product |

## 6. Out of Scope (Phase 1)
Online payments and shopping cart, buyer login/portal, ERP integration, mobile app. Architecture allows adding these later.

## 7. Analytics and Tracking
Google Analytics 4, Search Console, event tracking for: RFQ submit, chat start, catalog download, WhatsApp click.

## 8. Content Requirements
Company profile, product list and specs, high-quality photos/videos (own), team/farm/warehouse photos, logo, catalog content, address and contact details, legal pages. All images optimised and watermark-free; no copied content from other sites.

## 9. Roadmap and Milestones

| Milestone | Deliverables |
|-----------|--------------|
| M0 Documentation | This package approved |
| M1 Brand | Logo, colours, typography final |
| M2 Design | Wireframes + UI for Home, Division, Product, RFQ, Contact, Chat, Admin skin |
| M3 Core build | Laravel setup, DB, Filament admin, products, packages, pages |
| M4 Leads | RFQ, email, notifications, spam protection |
| M5 Chat | Widget, Reverb realtime, admin inbox, offline mode |
| M6 Content & Catalog | Data entry, catalog PDFs, translations |
| M7 QA & Launch | Testing, SEO, performance, security review, go-live |
| M8 Support | 30-day warranty, then maintenance plan |

## 10. Definition of Done
Feature coded, tested (feature + browser), responsive, accessible, reviewed, documented, deployed to staging, approved by client.
