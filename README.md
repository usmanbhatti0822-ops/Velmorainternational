# Velmora International — Website (Laravel 13)

B2B export website for **Velmora International (Private) Limited** (velmoraintl.com): rice, wheat, grains, dry fruits, leather, textile & garments. Includes admin panel and live chat (planned modules).

Full documentation is in [`docs/`](docs/README.md): requirements, PRD, ERD, architecture, design system, chat system, client questionnaire.

## Requirements
- PHP 8.3+ (with extensions: mbstring, xml, curl, mysql, zip, intl, gd or imagick, fileinfo)
- Composer 2
- Node.js 20+ and npm
- MySQL 8.4 LTS (or 9.7 LTS)

## Setup (VS Code terminal)
```bash
composer install
cp .env.example .env
php artisan key:generate

# create database "velmora" in MySQL, then set DB_USERNAME / DB_PASSWORD in .env
php artisan migrate

npm install
npm run dev          # terminal 1
php artisan serve    # terminal 2  → http://localhost:8000
```
Open the folder in VS Code and accept the recommended extensions (`.vscode/extensions.json`).

## Next build steps (see docs/02-prd.md roadmap)
1. Install Filament admin: `composer require filament/filament` (check Laravel 13 compatibility first), then `php artisan filament:install --panels`
2. Roles/permissions: `composer require spatie/laravel-permission`; translations: `spatie/laravel-translatable`
3. Create migrations from `docs/03-erd.md` (divisions, products, inquiries, chat tables)
4. Realtime chat: `php artisan install:broadcasting` (Reverb) — see `docs/06-chat-system.md`
5. Build pages following `docs/02-prd.md` and the design tokens in `resources/css/app.css`

## Versions
Laravel 13.x · Tailwind CSS 4 · Alpine.js 3.x (+ intersect plugin) · Vite. `composer.lock` / `package-lock.json` are created on first install — commit them to pin versions.

## Notes
- Certifications are intentionally hidden at launch (settings switch planned).
- Brand colours are provisional until the logo is final.
