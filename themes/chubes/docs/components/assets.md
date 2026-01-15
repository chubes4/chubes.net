# Asset Loading Patterns

Centralized loader: `inc/core/assets.php` (function `chubes_enqueue_assets`) enqueues theme assets with conditional checks and uses `filemtime()` for cache-busting.

## Files and when they load

- Always enqueued
  - style.css (get_stylesheet_uri())
  - assets/css/root.css
  - assets/js/navigation.js (mobile navigation)

- Front page
  - assets/css/home.css (when is_front_page())

- Documentation single posts
  - `chubes-docs/assets/css/related-posts.css` (enqueued by the `chubes-docs` plugin on `is_singular('documentation')`)

- Archives and taxonomy pages
  - `assets/css/archives.css` (on archives/taxonomy/category/tag)
  - `chubes-docs/assets/css/archives.css` (enqueued by the `chubes-docs` plugin on documentation/codebase archive contexts)

- Contact page
  - Contact functionality and its frontend assets are handled by the `chubes-contact` plugin (not the theme asset loader).

- Single posts
  - assets/css/single.css (when is_singular() && !is_page())

## Cache-busting

- All enqueued files use filemtime( $theme_path . '/path/to/file' ) for the version argument so browsers refresh when files change.

## Asset Organization

- CSS files follow modular design with root.css as single source of truth for variables
- No inline CSS used anywhere in the theme
- JavaScript files use vanilla JavaScript rather than jQuery
- All assets load conditionally based on page context for performance optimization