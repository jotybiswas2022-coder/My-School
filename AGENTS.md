# AGENTS.md

## Git
- Always commit and push to `origin` (branch `main`) after finishing a change, without waiting to be asked.
- Run `php artisan view:clear` and `php artisan test` before committing.
- Never commit secrets: `.env`, `storage/*.key`, `public/storage`, `vendor`, `node_modules` stay ignored.

## Project conventions
- Laravel 12, PHP, Blade only. No npm/Vite build step — all CSS lives inline in `resources/views/*/layouts/app.blade.php` and uses the CSS custom properties defined in `:root`.
- UI copy lives in `lang/en/ui.php` and `lang/bn/ui.php`; always add new strings to both files.
- Navbar/footer are shared partials: `resources/views/frontend/partials/`.
- Responsive breakpoints: navbar collapses to the drawer below 1400px, layout grids at 1024px and 720px.