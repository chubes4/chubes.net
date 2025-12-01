## Purpose
Guidance for AI coding agents contributing to the Chubes WordPress theme—a custom WordPress theme showcasing documentation, portfolio, and content management systems.

## Architecture Overview
**Chubes** is a **traditional WordPress theme** (no Node.js build pipeline) with:
- **Custom post types**: Journal (blog), Game (interactive) - Documentation CPT is registered by the chubes-docs plugin
- **Unified codebase taxonomy**: Hierarchical organization for 4 project types (wordpress-plugins, wordpress-themes, discord-bots, php-libraries) - registered by the chubes-docs plugin
- **Three core systems**: Documentation + Install tracking (WordPress.org API - handled by chubes-docs plugin), Navigation (context-aware breadcrumbs), Homepage customization (Customizer + Gutenberg)
- **Template hierarchy system**: Organized subdirectories (`/templates/{archive,single,page}/`) via filters in `inc/core/filters.php`
- **Build pipeline**: `build.sh` creates `/build/` packages for production deployment
- **Extended search**: Includes all custom post types in site-wide search

## Critical Files
- **`functions.php`** — Theme setup, parent page detection, module includes
- **`inc/core/assets.php`** — Centralized asset enqueuing for all theme CSS/JS with conditional loading
- **`inc/core/filters.php`** — Template hierarchy filters for organized `/templates/` subdirs
- **`inc/core/breadcrumbs.php`** — Context-aware navigation for back links

## Project-Specific Conventions
1. **No build step**: Edit files directly. CSS/JS changes immediately reflected (see next point).
2. **Asset versioning via `filemtime()`**: Each CSS/JS file uses file modification time as version in `wp_enqueue_*()`. Changing file contents auto-busts cache.
3. **Template organization**: Use `/inc/core/filters.php` hierarchy filters to redirect lookups. Example: single posts → `/templates/single/`, archives → `/templates/archive/`.
4. **Clean URLs via rewrite rules**: `/wordpress-plugins/{project}/`, `/docs/{project}/{doc}/` — see chubes-docs plugin `inc/Core/RewriteRules.php`.
5. **Data passing pattern**: PHP → JS uses `wp_localize_script('script-handle', 'object_name', $array)`. Follow naming: `*_params` or `*_vars`.
6. **Procedural, modular PHP**: Add new features in `/inc/{category}/` files and hook registration in `functions.php`. No classes or namespacing.
7. **Security: Nonce + Honeypot + Timestamp**:
   - All REST endpoints verify nonces: `wp_verify_nonce($nonce, 'contact_nonce')`
   - Contact form includes hidden honeypot field (must be empty)
   - Timestamp check ensures submission wasn't too fast (5 second minimum)
   - Always sanitize input (`sanitize_text_field()`, `sanitize_email()`, `sanitize_textarea_field()`) and escape output (`esc_html()`, `esc_url()`, `esc_attr()`)

## Data Flows
1. **Documentation archives**: URL `/docs/{project}/{doc}/` → rewrite rule sets query var → chubes-docs plugin `inc/Core/RewriteRules.php` handler → template resolved via hierarchy filter → documentation posts rendered with codebase taxonomy filtering.
2. **Install tracking**: WordPress.org API integration handled by chubes-docs plugin → `term_meta` storage (unified key: `codebase_installs`) → rendered in admin UI.

## Development Workflow
- **Local setup**: Use Local (LocalWP) or any WordPress environment. No build step needed during development.
- **Enable debugging**: In `wp-config.php`, set `WP_DEBUG = true` and `WP_DEBUG_LOG = true` for error logging.
- **Cache-bust CSS/JS**: Modify file or touch it; `filemtime()` will detect change and update version hash.
- **Build for production**: Run `./build.sh` → creates `/build/chubes.zip` (for WordPress admin upload). Excludes `.git`, `build/`, `docs/`, dev docs.

## Quick Rules for AI Edits
- When adding PHP → JS data, use `wp_localize_script()` with `*_params` or `*_vars` naming.
- Keep feature code modular: one feature per `/inc/{category}/` file, hook registration in `functions.php`.
- For new rewrite rules, add to `inc/core/rewrite-rules.php` and flush rewrite rules via admin interface or `wp_cache_flush()`.
- Respect template hierarchy: never hardcode template paths; leverage filters in `inc/core/filters.php`.
- When working with codebase taxonomy, use unified term meta keys: `codebase_github_url`, `codebase_wp_url`, `codebase_installs`.
- Use vanilla JavaScript (Fetch API) for client-side logic; no jQuery dependencies.

## Key Files at a Glance
| File | Purpose |
|------|---------|
| `functions.php` | Asset loading, theme setup, navigation, module includes |
| `inc/core/custom-post-types.php` | Journal, Game CPT registration (Documentation CPT by chubes-docs plugin) |
| `inc/core/filters.php` | Template hierarchy filters for organized `/templates/` subdirs |
| `inc/core/assets.php` | Centralized CSS/JS enqueuing for all theme assets |
| `inc/core/breadcrumbs.php` | Context-aware navigation links |
| `templates/` | Organized by type: `/archive/`, `/single/`, `/page/` |
| `assets/css/`, `assets/js/` | Conditionally enqueued stylesheets and scripts |
