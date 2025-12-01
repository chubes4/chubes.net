Custom Post Types

The theme registers two custom post types in inc/journal/journal-post-type.php:

- journal
  - Purpose: blog-style posts separate from the default posts table (journal entries).
  - Public archive slug: /journal.
  - Templates: rendered via the theme's single and archive templates under templates/single/ and templates/archive/ (see templates/archive/archive.php and templates/single/single.php as generic fallbacks).

- game
  - Purpose: block-based games and interactive content.
  - Public archive slug: /games (rewrite => 'games').
  - Templates: uses the theme single and archive templates (templates/single/ and templates/archive/). Specific game template files can be added under templates/single/ and templates/archive/ as needed.

The documentation post type is registered by the chubes-docs plugin:
- documentation
  - Purpose: plugin and project documentation, guides, and hierarchical documentation pages.
  - Public archive slug: /docs (has_archive => 'docs').
  - Templates: templates/single/single-documentation.php and templates/archive/archive-documentation.php are used by the theme's template hierarchy.

- All CPTs declare 'show_in_rest' => true to enable Gutenberg and REST usage.