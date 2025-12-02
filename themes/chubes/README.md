# Chubes Theme

Custom WordPress theme for https://chubes.net. This repository contains a modular, performance-focused WordPress theme with a documentation/codebase tracking system.

## Project overview

- Modular PHP organization under `/inc/`
- Templates organized under `/inc/core/templates/` with template-hierarchy filters
- Conditional asset loading with cache-busting via `filemtime()`
- Build script: `./build.sh` produces a ZIP for WordPress uploads in `/build/`
- NO contact functionality (moved to chubes-contact plugin)
- NO documentation CPT/taxonomy (handled by chubes-docs plugin)

## Quick setup (developer)

This theme runs in a standard WordPress environment (Local or server).

- Place the theme directory in `wp-content/themes/` and activate via the WordPress admin.
- Install and activate the chubes-contact plugin for contact form functionality.
- Install and activate the chubes-docs plugin for documentation features.

## Build

Create a production package with the included script:

./build.sh

This creates `/build/chubes.zip` for uploading to WordPress.

## Key files

- `/inc/core/assets.php` — centralized asset enqueuing and localization
- `/inc/journal/journal-post-type.php` — registers `journal` post type (game and documentation post types are registered by respective plugins)
- `/inc/core/filters.php` — template hierarchy routing to flattened directory
- `/inc/core/breadcrumbs.php` — breadcrumb generation for navigation
- `/inc/core/templates/` — flattened templates directory
- `build.sh` — production packaging script





## Asset loading

Assets are conditionally enqueued in `/inc/core/assets.php`. Examples:
- is_front_page() → assets/css/home.css
- is_singular('documentation') → chubes-docs/assets/css/documentation.css (plugin-managed)
- Archive/taxonomy pages → assets/css/archives.css
- Global navigation JS → assets/js/navigation.js

## Contributing

Open PRs against the repository. Keep changes minimal and focused. Verify behavior manually; there is no automated test suite.

---
Built by Chris Huber — https://chubes.net
