Asset Loading Patterns

Centralized loader: inc/core/assets.php (function chubes_enqueue_assets) enqueues theme assets with conditional checks and uses filemtime() for cache-busting.

Files and when they load

- Always enqueued
  - style.css (get_stylesheet_uri())
  - assets/css/root.css
  - assets/js/navigation.js (mobile navigation)

- Front page
  - assets/css/home.css (when is_front_page())

- Documentation single posts
  - chubes-docs/assets/css/documentation.css (when is_singular('documentation')) - plugin-managed

- Archives and taxonomy pages
  - assets/css/archives.css (on post type archives, is_tax('codebase'), and related custom archive query vars)

- Contact page
  - assets/css/contact.css and assets/js/contact.js (when is_page('contact') or page template page-contact.php)
  - contact.js is localized with chubes_contact_params containing rest_url('chubes/v1/contact') and a nonce via wp_localize_script in inc/core/assets.php

- Single posts
  - assets/css/single.css (when is_single())

Cache-busting

- All enqueued files use filemtime( $theme_path . '/path/to/file' ) for the version argument so browsers refresh when files change.

Asset Organization

- CSS files follow modular design with root.css as single source of truth for variables
- No inline CSS used anywhere in the theme
- JavaScript files use vanilla JavaScript rather than jQuery
- All assets load conditionally based on page context for performance optimization