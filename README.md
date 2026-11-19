# poss_php — Point of Sale System

A clean PHP MVC POS system built from scratch.

## Requirements
- PHP 8.1+
- MySQL 5.7+ / MariaDB 10.4+
- Apache with mod_rewrite (or Nginx equivalent)

## Setup

### 1. Clone / extract into your web root
```
/var/www/html/poss_php   (Apache)
```

### 2. Configure the database
Edit `.env` with your DB credentials, then run:
```bash
mysql -u root -p < database/schema.sql
```

### 3. Seed sample data (optional)
Generate the admin password hash first:
```bash
php -r "echo password_hash('admin123', PASSWORD_BCRYPT);"
```
Paste the hash into `database/seed.sql`, then:
```bash
mysql -u root -p poss_php < database/seed.sql
```

### 4. Apache virtual host
Point `DocumentRoot` to `/path/to/poss_php/public` and ensure `AllowOverride All` is set so `.htaccess` rewrites work.

### 5. Tailwind CSS
The layout uses Tailwind via CDN (no build step needed for development).
When moving to production, replace the CDN tag with a compiled CSS file using the Tailwind CLI:
```bash
npx tailwindcss -i ./public/assets/css/app.css -o ./public/assets/css/output.css --watch
```

## Structure
```
poss_php/
├── public/        ← web root (point Apache/Nginx here)
├── app/
│   ├── Controllers/
│   ├── Models/
│   └── Views/
├── core/          ← framework core (Router, DB, Auth …)
├── config/        ← app, database, routes
└── database/      ← schema.sql, seed.sql
```

## Build phases
- [x] Phase 1 — Core MVC foundation ← you are here
- [ ] Phase 2 — Auth & sessions
- [ ] Phase 3 — Products & inventory
- [ ] Phase 4 — POS cashier & cart
- [ ] Phase 5 — Payments & checkout
- [ ] Phase 6 — Reports & dashboard
- [ ] Phase 7 — UI polish & user management
