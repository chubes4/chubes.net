# Custom Post Types

## Theme: `chubes`

The theme registers one custom post type in `inc/journal/journal-post-type.php`:

- `journal`
  - Purpose: blog-style posts separate from default posts (journal entries)
  - Public archive slug: `/journal`

## Plugin: `chubes-games`

The `game` post type is registered by the `chubes-games` plugin (not the theme):

- `game`
  - Purpose: block-based games and interactive content
  - Public archive slug: `/games`

## Plugin: `chubes-docs`

The `documentation` post type is registered by the `chubes-docs` plugin:

- `documentation`
  - Purpose: project documentation, guides, and hierarchical documentation pages
  - Public archive slug: `/docs` (`has_archive` => `docs`)

## Template Notes

All of these post types are rendered using the theme’s normal template hierarchy. The `chubes-docs` plugin also injects documentation-specific archive behavior via theme hooks (see `chubes-docs/inc/Templates/Archive.php`).
