# Homepage System

The theme provides a dynamic homepage with hero section, content columns, and optional custom content blocks.

## Front Page Template

- Location: `templates/front-page.php`
- Displays hero section with site description
- Renders flexible column grid via `chubes_homepage_columns` action hook
- Supports optional custom content section via theme customizer

## Homepage Columns

The homepage displays content in a responsive grid using the `chubes_homepage_columns` action hook.

### Blog Column

- Displays latest 3 blog posts (`post` post type)
- Shows post title, date, and link to full post
- Includes "View all Posts" link to blog archive page
- Hook priority: 10

### Journal Column

- Displays latest 3 journal entries (`journal` post type)
- Shows entry title, date, and link to full entry
- Includes "View all Journal" link to journal archive
- Hook priority: 20

### Implementation

- Location: `inc/homepage/columns.php`
- Uses `WP_Query` to fetch recent posts
- Applies `wp_reset_postdata()` after queries
- Links use `get_permalink()` and archive link functions

## Asset Loading

- CSS loaded from `assets/css/home.css` on front page
- Conditional loading via `inc/core/assets.php`
- Cache busting with `filemtime()`

## Customization

The homepage system is extensible via the `chubes_homepage_columns` action hook. Additional columns can be added by hooking into this action with appropriate priorities.