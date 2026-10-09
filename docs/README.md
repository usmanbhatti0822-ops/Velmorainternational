# Velmora International (Private) Limited — Website Project Documentation

**Domain:** velmoraintl.com  **Type:** B2B export website with admin panel and live chat
**Stack:** PHP 8.3+ · Laravel 13.x · MySQL (8.4 LTS or 9.7 LTS) · Tailwind CSS · Alpine.js · Filament admin · Laravel Reverb (live chat)
**Document version:** 1.0 (draft for client review) · **Date:** October 2026

## Document Index

| # | File | Purpose |
|---|------|---------|
| 01 | `01-requirements.md` | Requirement gathering: business context, goals, stakeholders, functional and non-functional requirements |
| 02 | `02-prd.md` | Product Requirements Document: sitemap, pages, features, user stories, acceptance criteria, roadmap |
| 03 | `03-erd.md` | Database design: ERD (Mermaid), table dictionary, indexes |
| 04 | `04-architecture.md` | System architecture, tech stack, modules, security, hosting, deployment |
| 05 | `05-design-system.md` | Colour scheme, typography, UI components, animation and motion guidelines |
| 06 | `06-chat-system.md` | Live chat module: visitor widget, admin inbox, realtime flow, data and rules |
| 07 | `07-client-questionnaire.md` | Questions to confirm with client (Roman Urdu + English) |

## Project Phases

1. **Phase 0 – Documentation & sign-off** (this package)
2. **Phase 1 – Brand**: logo, brand colours, typography (colours in doc 05 are provisional until logo is final)
3. **Phase 2 – UI/UX design**: wireframes → high-fidelity pages (desktop + mobile)
4. **Phase 3 – Development**: Laravel core, admin panel, products, RFQ, chat
5. **Phase 4 – Catalog & content**: product catalog PDF (per category + combined), photos, copywriting, translations
6. **Phase 5 – QA, SEO, launch**: testing, performance, security, go-live
7. **Phase 6 – Support & growth**: maintenance, analytics, SEO iterations

## Implementation Milestones

| Milestone | Status | Delivered / next steps |
|-----------|--------|------------------------|
| M0 Documentation | Complete | Requirements, PRD, ERD, architecture, design, chat rules and client questionnaire reviewed. |
| M1 Brand | Blocked on client | Apply the approved logo, final brand tokens and supplied company facts when received. Current visual direction is provisional. |
| M2 UI/UX | Baseline implemented | Responsive English/Arabic public pages and staff interface are in place. Complete browser review against approved brand assets and client feedback. |
| M3 Core build | Baseline implemented | Laravel schema, bilingual divisions/packages, product catalog and role-gated staff tools are implemented. A custom Laravel admin replaces the planned Filament panel because the local PHP environment lacks `ext-intl`; install/enable it before reconsidering Filament. |
| M4 Leads | Core implemented; launch setup pending | RFQ/contact workflows, validation, private attachments and inquiry management are implemented. Configure production mail and add the client-approved spam protection before launch. |
| M5 Chat | Shared-host baseline implemented | Visitor chat, offline intake, staff inbox, private attachments and polling are implemented. Realtime Reverb, browser notifications and sounds remain deferred until hosting and operational needs are confirmed. |
| M6 Content & Catalog | Admin tools implemented; client content pending | Staff can manage product/division/package/catalog content and certification visibility. Load approved product data, images, translations, catalog PDFs, contact details and legal copy; do not publish unverified claims. |
| M7 QA & Launch | In progress | Automated tests and asset build pass locally. Finish browser/accessibility, performance and security checks, configure MySQL/SMTP/domain/hosting, then deploy to staging and obtain client approval. |
| M8 Support | Not started | Begin the agreed warranty and maintenance period after client-approved production launch. |

### Launch sequence

1. Collect questionnaire answers, logo/brand approval, real product specifications, catalog files, contact/legal details and certification approvals.
2. Configure production PHP extensions, MySQL, mail, file storage, domain and HTTPS; create the first staff account with `php artisan velmora:make-admin`.
3. Import only approved content, verify English and Arabic/RTL pages, test RFQ/chat/admin workflows, and complete browser, accessibility, security and performance checks.
4. Deploy to staging for client sign-off, then production; monitor inquiries, mail delivery, chat and backups during the warranty period.

## Guiding Principles

- **Truthful claims only.** Statistics, years and certifications appear only when real and approved. Certifications exist but are **hidden for now**; the system includes a visibility switch so they can be published later with one click.
- **Own content only.** Reference sites are studied for structure and ideas; no text or images are copied.
- **One brand, six divisions.** Rice is the suggested hero; the other five categories appear as divisions.
- **Version policy.** Use current supported releases (Laravel 13.x, MySQL LTS); pin versions in `composer.lock` / `package-lock.json`. Verify third-party package compatibility (Filament, Reverb) at project start.
