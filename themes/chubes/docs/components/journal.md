# Journal Post Type

The theme provides a custom `journal` post type for personal journal entries and blog-style content separate from WordPress default posts.

## Features

- **Gutenberg Editor Support**: Full block editor capabilities
- **Public Archive**: Accessible at `/journal` URL
- **Standard Post Features**: Title, content, featured images, excerpts, custom fields, revisions
- **REST API Support**: Available via WordPress REST API
- **Admin UI**: Dedicated menu item with edit icon

## Registration Details

```php
$args = array(
    'label'         => __('Journal', 'chubes-theme'),
    'public'        => true,
    'show_ui'       => true,
    'menu_position' => 6,
    'menu_icon'     => 'dashicons-edit',
    'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
    'has_archive'   => true,
    'rewrite'       => array('slug' => 'journal'),
    'show_in_rest'  => true,
);
register_post_type('journal', $args);
```

## Usage

### Creating Journal Entries

1. In WordPress admin, navigate to **Journal > Add New**
2. Use the Gutenberg editor to create content
3. Set featured images, categories, and tags as needed
4. Publish to make entries available at `/journal`

### Displaying Journal Content

The theme automatically provides:
- Archive template at `/journal` showing all entries
- Single entry templates for individual journal posts
- Integration with theme's search functionality
- Homepage integration (latest journal entries can be displayed)

## Technical Implementation

- **Location**: `inc/journal/journal-post-type.php`
- **Hook**: Registered on `init` action
- **Templates**: Uses theme's standard template hierarchy
- **Search Integration**: Included in theme's search post types filter

## Notes

This post type is intended for personal journal/blog content and is separate from the default WordPress `post` type. Documentation and game content are handled by respective plugins (`chubes-docs` and `chubes-games`).</content>
<parameter name="filePath">chubes/docs/components/journal.md