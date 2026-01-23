# Codebase Taxonomy

- Registration: The 'codebase' taxonomy is registered by the chubes-docs plugin in `inc/Core/Codebase.php` and ties it to the documentation post type.
- Expected top-level terms (used throughout the theme):
  - wordpress-plugins
  - wordpress-themes
  - discord-bots
  - php-libraries
  These slugs are returned by `ChubesDocs\Core\Codebase::get_top_level_slugs()` in the chubes-docs plugin.

Helper functions (user-facing behavior)

The chubes-docs plugin provides static helper methods in `ChubesDocs\Core\Codebase`:

- `get_primary_term($terms)`
  - Returns the deepest (most specific) assigned codebase term from an array of terms.
- `get_project_term($terms)`
  - Resolves the project-level term (a child of a top-level category) from a set of assigned terms.
- `get_top_level_term($terms)`
  - Resolves the top-level category term from a set of assigned terms.

- See the implementations and related helpers in the chubes-docs plugin `inc/Core/Codebase.php` for exact behavior and edge cases.

URL mapping

- Top-level codebase slugs map to public archives and site routes, for example:
  - /wordpress-plugins/
  - /wordpress-themes/
  - /discord-bots/
  - /php-libraries/

- The theme and chubes-docs plugin use those slugs to generate archives and to build documentation permalinks; see chubes-docs plugin `inc/Core/RewriteRules.php` for the routing rules that map slugs to archive templates.