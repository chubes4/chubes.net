# Journal

Journal entries are regular WordPress posts in the **Journal** category (slug `journal`). Journal is not a separate post type: entries share categories, tags, search, sitemaps and the editor with every other post.

Implementation: `inc/journal/journal.php`.

## Behavior

| Concern | Behavior |
|---|---|
| Archive | `/journal` (and `/journal/page/N`) instead of `/category/journal`. `/category/journal/...` 301s to `/journal/...`. |
| Archive layout | Minimal title and date list (`.journal-list`) instead of the card grid, via `chubes_archive_content`. |
| Feed | `/journal/feed`. Journal entries are excluded from the main `/feed`. |
| Blog | Excluded from the posts page (`/blog`) and the homepage Blog column. |
| Entry URLs | `/{slug}`, like every post. Legacy `/journal/{slug}` URLs from the old post type 301 to the current permalink. |
| Breadcrumbs / back link | Home / Journal / Entry. |
| Search | Included, as regular posts. |

## Writing an entry

Create a normal post and give it the **Journal** category.

## Helpers

- `chubes_journal_term()`: the Journal `WP_Term`, or `null`.
- `chubes_journal_url()`: archive URL.
- `chubes_is_journal_post( $post )` / `chubes_is_journal_archive()`.
- `chubes_blog_query_args( $args )` / `chubes_journal_query_args( $args )`: `WP_Query` args for each stream.

## Migrating from the old `journal` post type

```bash
wp chubes journal migrate --dry-run
wp chubes journal migrate
```

Creates the Journal category if missing, converts every `journal` post type entry (any status) to a `post` with only the Journal category, and flushes rewrites. It refuses to run if a journal slug collides with an existing post or page, and it is safe to re-run.
