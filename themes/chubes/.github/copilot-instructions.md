## Purpose
Short, actionable guidance for AI coding agents working on the Chubes WordPress theme.

## Quick context (big picture)
- This is a traditional WordPress theme (no build tools). Core PHP is organized in `functions.php` and `/inc/` feature folders.
- Major systems: contact form (AJAX + honeypot), portfolio (custom post type + AJAX load-more), and plugin install tracking (WP.org API + cron).

## Hotspots (files to read first)
- `functions.php` — theme setup, conditional asset enqueues (look for `filemtime()` usage and `wp_enqueue_*`).
- `inc/contact-ajax.php` and `page-contact.php` — contact form flow and server-side handling.
- `inc/portfolio/` (especially `portfolio-custom-fields.php` and `portfolio-image-overlay.php`) and `template-parts/portfolio-item.php` — portfolio CPT and presentation.
- `inc/plugins/track-plugin-installs.php` and `inc/plugins/plugin-custom-fields.php` — plugin tracking API and cron scheduling.
- `inc/breadcrumbs.php` — navigation/parent-page detection logic used across templates.

## Concrete conventions & patterns (do not invent alternatives)
- No package.json / no build step: edit files directly under `/assets/` and PHP files.
- Asset versioning uses filemtime(): when you change CSS/JS, the theme uses the file's mtime as the version. Example pattern is in `functions.php`.
- JS data is passed from PHP using `wp_localize_script()` and naming patterns like `*_params` or `*_vars` (e.g., `loadmore_params`).
- Security pattern: always verify nonces, use honeypot + timestamp checks in contact flow, sanitize inputs with `sanitize_text_field()` / `sanitize_email()` and escape outputs with `esc_html()`, `esc_url()`, `esc_attr()`.
- Procedural/module style: add feature code under `/inc/` and register hooks in `functions.php` rather than using classes or namespacing.

## Data flows to know
- Contact form: browser → AJAX (`admin-ajax.php`) → `inc/contact-ajax.php` → validation/sanitization → emails (admin + user). Check `assets/js/contact.js` for the client-side flow.
- Portfolio load-more: archive page → `assets/js/load-more.js` (localized `loadmore_params`) → `admin-ajax.php` handler (see `inc/utils/load-more.php`).
- Plugin installs: scheduled via `wp_schedule_event()` (daily) → `inc/plugins/track-plugin-installs.php` fetches WordPress.org API → stored/aggregated for admin UI.

## Common tasks and where to change things
- Update contact form behavior: edit `page-contact.php`, `assets/js/contact.js`, and `inc/contact-ajax.php` (follow existing nonce & honeypot checks).
- Add portfolio custom field: add to `inc/portfolio/portfolio-custom-fields.php` and update `template-parts/portfolio-item.php` to render it.
- Change asset loading rules: modify enqueue logic in `functions.php` (use `is_front_page()`, `is_post_type_archive('portfolio')`, etc.).

## Debugging and testing tips
- Local dev: run the site in Local (LocalWP) or any WordPress stack — there is no build step.
- Enable WP debug in `wp-config.php` (WP_DEBUG, WP_DEBUG_LOG) to surface PHP errors.
- For AJAX issues, inspect network → `admin-ajax.php` and check server response and PHP error log.
- After changing CSS/JS, the theme uses file mtime for cache-busting; touching the file or changing its contents updates the served version.

## Quick rules for automated edits
- Preserve existing nonce/honeypot/timestamp checks when touching contact code.
- When adding JS that needs server data, use `wp_localize_script()` and follow existing naming conventions (`*_params`/`*_vars`).
- Keep PHP edits modular under `/inc/` and register functionality via hooks in `functions.php`.

## Useful references in this repo
- `functions.php` — asset loading, theme setup
- `page-contact.php` + `inc/contact-ajax.php` — contact form
- `assets/js/load-more.js` + `inc/utils/load-more.php` — portfolio load-more
- `inc/plugins/track-plugin-installs.php` — plugin API integration & cron
- `inc/breadcrumbs.php` — navigation logic

If any section is unclear or you'd like the file to include additional example snippets, tell me which area to expand and I'll iterate.
