## Purpose
Guidance for AI coding agents contributing to the Chubes WordPress theme—a custom WordPress theme showcasing documentation, portfolio, and content management systems.

## Architecture Overview (Big Picture)
**Chubes** is a **traditional WordPress theme** (no Node.js build pipeline) with:
- **Custom post types**: Journal (blog), Game (interactive), Documentation (guides)
- **Unified codebase taxonomy**: Hierarchical organization for 4 project types (wordpress-plugins, wordpress-themes, discord-bots, php-libraries)
- **Three core systems**: Contact form (AJAX + security), Documentation + Install tracking (WordPress.org API), Navigation (context-aware breadcrumbs)
- **Template hierarchy system**: Organized subdirectories (`/templates/{archive,single,page,taxonomy}/`) via filters in `inc/core/filters.php`
- **Build pipeline**: `build.sh` creates `/dist/` packages for production deployment

## Critical Files (Read First)
- **`functions.php`** — Theme setup, asset enqueuing with `filemtime()` versioning, parent page detection
- **`inc/core/`** — Post types, taxonomies, template hierarchy filters, rewrite rules for clean URLs
- **`inc/core/assets.php`** — Centralized asset enqueuing for all theme CSS/JS
- **`inc/contact-rest-api.php`** + **`templates/page/page-contact.php`** — Contact form: REST API endpoint at `/wp-json/chubes/v1/contact` with nonce verification, honeypot, and timestamp checks
- **`inc/plugins/track-codebase-installs.php`** — WordPress.org API integration for install count tracking
- **`inc/breadcrumbs.php`** — Context-aware navigation for back links

## Project-Specific Conventions
1. **No build step**: Edit files directly. CSS/JS changes immediately reflected (see next point).
2. **Asset versioning via `filemtime()`**: Each CSS/JS file uses file modification time as version in `wp_enqueue_*()`. Changing file contents auto-busts cache.
3. **Template organization**: Use `/inc/core/filters.php` hierarchy filters to redirect lookups. Example: single posts → `/templates/single/`, archives → `/templates/archive/`.
4. **Clean URLs via rewrite rules**: `/wordpress-plugins/{project}/`, `/docs/{category}/{project}/{doc}/` — see `inc/core/rewrite-rules.php`.
5. **Data passing pattern**: PHP → JS uses `wp_localize_script('script-handle', 'object_name', $array)`. Follow naming: `*_params` or `*_vars`.
6. **Procedural, modular PHP**: Add new features in `/inc/{category}/` files and hook registration in `functions.php`. No classes or namespacing.
7. **Security: Nonce + Honeypot + Timestamp**:
   - All AJAX handlers verify nonces: `check_ajax_referer('action_nonce', 'nonce_field')`
   - Contact form includes hidden honeypot field (must be empty)
   - Timestamp check ensures submission wasn't too fast (5 second minimum)
   - Always sanitize input (`sanitize_text_field()`, `sanitize_email()`) and escape output (`esc_html()`, `esc_url()`, `esc_attr()`)

## Data Flows
1. **Contact form**: `page-contact.php` (form HTML) → `assets/js/contact.js` (REST POST) → `/wp-json/chubes/v1/contact` → `inc/contact-rest-api.php` (validate/sanitize) → emails sent (admin + user). Requires nonce, passes honeypot & timestamp checks.
2. **Documentation archives**: URL `/docs/{category}/{project}/` → rewrite rule sets query var → `inc/core/rewrite-rules.php` handler → template resolved via hierarchy filter → `templates/archive/archive-documentation.php` renders posts with codebase taxonomy filtering.
3. **Install tracking**: WordPress.org API call (`chubes_fetch_codebase_data()`) → `term_meta` storage (unified key: `codebase_install_count`) → rendered in admin UI via custom columns in `inc/core/custom-taxonomies.php`.

## Common Tasks & File Locations
- **Update contact form UI/behavior**: Edit `templates/page/page-contact.php` (HTML/form), `assets/js/contact.js` (client logic), `inc/contact-rest-api.php` (REST endpoint). Preserve nonce/honeypot/timestamp checks. Assets managed by `inc/core/assets.php`.
- **Add/modify custom post type**: Edit `inc/core/custom-post-types.php` (registration), then update templates in `/templates/{single,archive}/`.
- **Modify codebase taxonomy display**: Edit `inc/core/custom-taxonomies.php` (field registration, admin columns), then update `templates/taxonomy/taxonomy-codebase.php` (frontend).
- **Change asset loading rules**: Edit `functions.php` enqueue section. Use conditionals like `is_front_page()`, `is_singular('documentation')`, `is_post_type_archive()`.
- **Add new template type**: Create file in `/templates/{type}/`, then add corresponding hierarchy filter to `inc/core/filters.php`.
- **Add install tracking to new project type**: Use existing `chubes_fetch_codebase_data($slug, $project_type)` and `chubes_update_codebase_installs($term_id, $wp_url)` functions.

## Development Workflow
- **Local setup**: Use Local (LocalWP) or any WordPress environment. No build step needed during development.
- **Enable debugging**: In `wp-config.php`, set `WP_DEBUG = true` and `WP_DEBUG_LOG = true` for error logging.
- **Test AJAX**: Inspect Network tab in browser DevTools. Check request to `admin-ajax.php` and PHP error log.
- **Cache-bust CSS/JS**: Modify file or touch it; `filemtime()` will detect change and update version hash.
- **Build for production**: Run `./build.sh` → creates `/dist/chubes.zip` (for WordPress admin) and `/dist/chubes/` (for FTP). Excludes `.git`, `build/`, `dist/`, dev docs.

## Quick Rules for AI Edits
- Always preserve security checks (nonce, honeypot, timestamp, sanitization) when modifying contact or AJAX code.
- When adding PHP → JS data, use `wp_localize_script()` with `*_params` or `*_vars` naming.
- Keep feature code modular: one feature per `/inc/{category}/` file, hook registration in `functions.php`.
- For new rewrite rules, add to `inc/core/rewrite-rules.php` and flush rewrite rules via admin interface or `wp_cache_flush()`.
- Respect template hierarchy: never hardcode template paths; leverage filters in `inc/core/filters.php`.
- When working with codebase taxonomy, use unified term meta keys: `codebase_github_url`, `codebase_wp_url`, `codebase_install_count`.

## Key Files at a Glance
| File | Purpose |
|------|---------|
| `functions.php` | Asset loading, theme setup, parent page detection |
| `inc/core/custom-post-types.php` | Journal, Game, Documentation CPT registration |
| `inc/core/custom-taxonomies.php` | Codebase taxonomy, term meta fields, admin columns |
| `inc/core/filters.php` | Template hierarchy filters for organized `/templates/` subdirs |
| `inc/core/rewrite-rules.php` | Clean URLs for projects and documentation |
| `inc/core/assets.php` | Centralized CSS/JS enqueuing for all theme assets |
| `inc/contact-rest-api.php` | REST API endpoint for contact form submissions |
| `inc/plugins/track-codebase-installs.php` | WordPress.org API integration for install counts |
| `inc/breadcrumbs.php` | Context-aware navigation links |
| `templates/` | Organized by type: `/archive/`, `/single/`, `/page/`, `/taxonomy/` |
| `assets/css/`, `assets/js/` | Conditionally enqueued stylesheets and scripts |
