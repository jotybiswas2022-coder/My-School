# My School — School Website & Management System

A complete, production-style **school website** plus **school management system** built with **PHP, Laravel, Blade and vanilla JavaScript**.

The public website and the administration panel share one coherent, modern design language, while remaining completely separate in structure and access control.

---

## Technology

| Layer | Choice |
| --- | --- |
| Backend | PHP 8.2+ / Laravel 12 |
| Templating | Blade (HTML + **inline CSS only**) |
| Styling | Inline `<style>` blocks & style attributes — **no CSS framework** |
| Frontend JS | Vanilla JavaScript only — **no jQuery / React / Vue / Alpine / Livewire / Inertia** |
| Database | MySQL (or SQLite — see below) |

> There are **no external CSS files** and **no external JavaScript libraries**. Every view carries its own inline styles.

---

## 1. Installation

```bash
composer install
```

## 2. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Then edit `.env` with your details (at minimum the database block below).

## 3. Database configuration

MySQL (default):

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_school
DB_USERNAME=root
DB_PASSWORD=
```

Prefer SQLite for a quick local run? Create the file and switch the driver:

```bash
touch database/database.sqlite
```

```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

## 4. Migration

```bash
php artisan migrate
```

## 5. Seeding

Creates the admin user, staff, classes, subjects, students, exams, results, notices, events, news, gallery albums, admissions and contact messages — plus generated sample images.

```bash
php artisan db:seed
# or reset everything and reseed
php artisan migrate:fresh --seed
```

## 6. Running the application

```bash
# create the storage symlink so uploaded/generated images are web-accessible
php artisan storage:link

php artisan serve
```

Open <http://127.0.0.1:8000>.

### XAMPP with the document root above `public/`

If the document root is `htdocs` and the project lives in `htdocs/core`, two extra files are
needed next to `core/` so that PHP requests and static files both resolve:

```php
// htdocs/index.php
require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$app->handleRequest(Request::capture());
```

```apache
# htdocs/.htaccess — inside the existing RewriteEngine block, before the front-controller rule
RewriteCond %{DOCUMENT_ROOT}/core/public/$1 -f
RewriteRule ^(.+)$ core/public/$1 [L]
```

The second rule maps `/storage/...` and `/images/...` to `core/public/...`, which is what every
`asset('storage/...')` call in the views expects.

> **Do not set `ASSET_URL`** in this setup. With `ASSET_URL` present, `asset()` prefixes
> *all* URLs with it and every image/link under `storage/` returns 404.

## 7. Admin login

| Field | Value |
| --- | --- |
| URL | `/admin/login` |
| Email | `admin@myschool.edu` |
| Password | `password` |

Change this password immediately in any real deployment.

## 8. Student login

| Field | Value |
| --- | --- |
| URL | `/student/login` |
| Student ID | `STU-2026-001` (…through `STU-2026-060`) |
| Password | `password` |

---

## 9. Main features

### Public website
- Home page with hero, animated statistics, programs, principal's message, featured teachers, notices, events, news, masonry gallery, facilities, admission CTA and contact preview
- About, Principal's Message, Academics, Classes, Subjects, Facilities
- Teachers & Staff directory with search + department filter, and individual profiles
- Student directory (non-sensitive information only)
- Notice Board (search, category filter, pagination, attachments, detail page)
- Events (upcoming / past tabs, detail page)
- News (featured article, search, category filter, detail page)
- Gallery albums with a vanilla-JS lightbox and category filter
- Public **result search** producing a printable marksheet (grade + GPA)
- Online **admission application** with generated Application ID and public status tracking
- Contact form saving messages to the database
- Mobile navigation, scroll reveal, animated counters, confirmation modals, toast-style alerts

### Student portal
- Login / logout using a dedicated `student` auth guard
- Dashboard (attendance summary, latest results, recent notices, upcoming events, profile card)
- Profile, Attendance history, Results grouped by exam, Notices, Academics

### Admin panel (`/admin`)
- Separate layout with responsive sidebar, topbar, breadcrumbs and dashboard cards
- Dashboard statistics (students, teachers, classes, subjects, notices, events, news, pending admissions, unread messages) plus recent activity tables
- **Students** CRUD + search + class/section filters + profile with attendance & results
- **Teachers** CRUD + search + department filter + profile with assigned subjects
- **Classes & Sections** CRUD with subject allocation
- **Subjects** CRUD with teacher/class assignment
- **Academic Sessions** CRUD with "current session" switching
- **Exams** CRUD, exam subject scheduling, publish/unpublish results
- **Results** bulk marks entry, single-result editing, publishing
- **Attendance** daily marking (present / absent / late) with bulk actions, plus daily & per-student reports
- **Notices / Events / News** CRUD with publish toggles, image/attachment uploads
- **Gallery** album CRUD with multi-image upload
- **Admissions** listing, search, status filter, detail view, approve / reject
- **Contact messages** inbox with read/unread states and reply-by-email
- **Website settings** (identity, contact details, principal, social links, logos, admission status)

### Cross-cutting
- Full server-side validation on every form
- CSRF protection, authentication, authorization middleware
- Secure password hashing, unique filenames for uploads, file type/size validation
- Custom, on-brand **404 / 403 / 419 / 500 / 503** pages
- Fully responsive layouts (desktop / laptop / tablet / mobile) with no separate mobile pages

---

## 10. Folder structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          Admin panel controllers (CRUD for every module)
│   │   ├── Frontend/       Public website controllers
│   │   ├── Student/        Student auth + dashboard
│   │   └── Concerns/       UploadsFiles trait (secure image uploads)
│   └── Middleware/         AdminMiddleware, RedirectIfAuthenticated
├── Models/                 Eloquent models with full relationships
└── Providers/              Shares settings with all views, pagination view

database/
├── factories/              User factory
├── migrations/             Complete schema
└── seeders/                DatabaseSeeder with realistic sample data

routes/
├── web.php                 Public + student routes
└── admin.php               Admin routes

resources/views/
├── errors/                 404, 403, 419, 500, 503
├── partials/               Shared pagination partial
├── frontend/
│   ├── layouts/app.blade.php
│   ├── partials/           Navbar, footer, flash, page-head
│   ├── auth/               Student login
│   ├── student/            Student portal (layout, sidebar, pages)
│   └── *.blade.php         All public pages
└── backend/
    ├── layouts/app.blade.php
    ├── partials/           Sidebar, topbar, flash
    ├── auth/login.blade.php
    └── <module>/           Index / form / show views for every module
```

---

## Database schema

`users`, `students`, `teachers`, `classes`, `sections`, `subjects`, `academic_sessions`,
`class_subjects`, `attendances`, `exams`, `exam_subjects`, `results`, `notices`, `events`,
`news`, `gallery_albums`, `gallery_images`, `admissions`, `contacts`, `settings`
— all with proper foreign keys, unique constraints and indexes.

Key relationships: Student → Class / Section / Attendance / Results, Teacher → Subjects,
Class → Subjects / Students / Sections, Exam → Results / Subjects,
GalleryAlbum → GalleryImages.

---

## Testing

```bash
php artisan test
```

The feature suite covers every public page, admin authentication and protection, the admin
CRUD screens, student authentication, admission submission & validation, contact messages
and result search.

---

## Notes

- Uploads and seeded sample images live on the `public` disk; run `php artisan storage:link` once.
- Settings are cached; saving them through the admin panel clears the cache automatically.
- Only published notices, events, news, albums and results are visible publicly.
