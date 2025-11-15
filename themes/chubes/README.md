# Chubes Theme

Custom WordPress theme for [https://chubes.net](https://chubes.net) - Chris Huber's showcase platform for WordPress development, music journalism, and digital tools.

## Overview

This theme serves as a showcase and informational platform for WordPress development, music journalism, and digital tools. Built with performance and modularity in mind, featuring comprehensive plugin documentation and tracking systems.

## Architecture

### Development Approach
- **Traditional WordPress** - Bash build script for production, no Node.js tooling
- **Modular PHP structure** - Organized by feature in `/inc/` directory
- **Organized template system** - Templates in `/templates/` subdirectories with hierarchy filters
- **Dynamic asset loading** - Conditional CSS/JS with cache-busting via `filemtime()`
- **Security-focused** - Version hiding, nonce verification, honeypot protection
- **Production build system** - `build.sh` creates optimized theme packages

### Custom Post Types & Taxonomy
```php
// Journal - Personal blog content  
register_post_type('journal', $args);

// Game - Interactive content hosting
register_post_type('game', $args);

// Documentation - Plugin guides and tutorials
register_post_type('documentation', $args);

// Codebase taxonomy - Unified hierarchical organization with four categories:
// wordpress-plugins, wordpress-themes, discord-bots, php-libraries
register_taxonomy('codebase', array('documentation', 'journal', 'game'), $args);
```

### Contact System
Simple, unified contact form using REST API:
- Main contact form in `page-contact.php` with comprehensive spam protection
- REST API endpoint at `/wp-json/chubes/v1/contact` handled by `/inc/contact-rest-api.php`
- Contact assets (`contact.css`, `contact.js`) enqueued via `/inc/core/assets.php`

Form features:
- Honeypot and timestamp spam protection
- REST API submission with user feedback
- Admin and user notification emails
- WordPress nonce security and input sanitization

### Codebase Documentation & Tracking System
Unified project management system with WordPress.org API integration:
```php
// Unified codebase taxonomy with custom fields
update_term_meta($term_id, 'codebase_github_url', $github_url);
update_term_meta($term_id, 'codebase_wp_url', $wp_url);
update_term_meta($term_id, 'codebase_install_count', $install_count);

// Fetch repository information
$repo_info = chubes_get_repository_info($term);

// Helper functions for codebase projects
chubes_get_codebase_wp_url($term_id);
chubes_get_codebase_github_url($term_id);
chubes_get_codebase_installs($term_id);

// Project type detection (returns: wordpress-plugin, wordpress-theme, discord-bot, php-library)
$project_type = chubes_get_codebase_project_type($term);

// Hierarchy helpers (always derive project + top-level terms from assigned taxonomy)
$project_term   = chubes_get_codebase_project_term_from_terms($terms);
$category_term  = chubes_get_codebase_top_level_term_from_terms($terms);
$primary_term   = chubes_get_codebase_primary_term($terms);

Hierarchy helpers guarantee that every documentation feature (breadcrumbs, archive links,
related lists) resolves to the correct project slug (e.g., `data-machine`) instead of generic
category labels (e.g., `WordPress Plugins`). Always use these utilities rather than trying
to walk the taxonomy tree manually.
```

### Navigation System
Dynamic parent page detection in `functions.php`:
```php
// Context-aware breadcrumb navigation
chubes_get_parent_page();
// Returns: ['url' => $parent_url, 'title' => $parent_title]

```

Documentation-specific breadcrumbs live in `inc/breadcrumbs.php` and rely on the codebase
helpers to emit paths like `Documentation → WordPress Plugins → Data Machine → Article` with
links that mirror the `/docs/{category}/{project}/` routing. If breadcrumbs look wrong for a
doc post, inspect the helper inputs first.

## File Structure

```
/chubes/
├── /templates/ - Organized template files with hierarchy filters:
│   ├── /archive/ - All archive templates
│   │   ├── archive.php - Generic archive template with dynamic headers
│   │   ├── archive-journal.php - Journal archive
│   │   ├── archive-codebase.php - Codebase taxonomy archive
│   │   └── archive-documentation.php - Documentation archive
│   ├── /single/ - All single post templates
│   │   └── single.php - Default single post template
│   ├── /page/ - All page templates
│   │   ├── page.php - Default page template
│   │   └── page-contact.php - Contact page template
│   ├── /taxonomy/ - All taxonomy templates
│   │   └── taxonomy-codebase.php - Individual codebase project pages
│   ├── /parts/ - Template parts organized by functionality
│   ├── 404.php - Custom 404 error page
│   ├── front-page.php - Homepage template
│   ├── home.php - Blog posts index template
│   └── index.php - Default template
├── /assets/
│   ├── /css/                     # Page-specific stylesheets
│   ├── /js/                      # Navigation, contact form, interactions
│   └── /fonts/                   # Inter, Space Grotesk + SVG icons
├── /inc/
│   ├── breadcrumbs.php          # Navigation breadcrumb system
│   ├── contact-rest-api.php     # Contact form REST API endpoint
│   ├── /core/                   # Core WordPress functionality
│   │   ├── assets.php               # Centralized CSS/JS enqueuing
│   │   ├── custom-post-types.php    # Journal, Game, Documentation CPTs
│   │   ├── custom-taxonomies.php    # Codebase taxonomy registration
│   │   ├── rewrite-rules.php        # Custom URL rewrite rules for documentation
│   │   └── filters.php              # Template hierarchy filters for organized template loading
│   ├── /plugins/                # Codebase tracking & taxonomy fields
│   └── /utils/                  # Instagram embeds
├── /dist/                       # Production build directory
└── functions.php                # Theme setup & module includes
```

## Key Features

### Asset Loading
Centralized asset loading via `/inc/core/assets.php` with conditional loading based on page context:
```php
// Homepage specific styles
if (is_front_page()) {
    wp_enqueue_style('home-style', $theme_dir . '/assets/css/home.css', array(), filemtime($theme_path . '/assets/css/home.css'));
}

// Documentation specific styles
if (is_singular('documentation')) {
    wp_enqueue_style('documentation-style', $theme_dir . '/assets/css/documentation.css', array(), filemtime($theme_path . '/assets/css/documentation.css'));
}

// Archives styles on archive and taxonomy pages
if (is_post_type_archive() || is_tax('codebase') || is_post_type_archive('documentation') || is_category() || is_tag() || is_tax()) {
    wp_enqueue_style('archives-style', $theme_dir . '/assets/css/archives.css', array(), filemtime($theme_path . '/assets/css/archives.css'));
}

// Contact form assets (CSS & JS + localized nonce & REST URL)
if (is_page('contact') || is_page_template('page-contact.php')) {
    wp_enqueue_style('contact-css', $theme_dir . '/assets/css/contact.css', array(), filemtime($theme_path . '/assets/css/contact.css'));
    wp_enqueue_script('contact-js', $theme_dir . '/assets/js/contact.js', array(), filemtime($theme_path . '/assets/js/contact.js'), true);
    wp_localize_script('contact-js', 'chubes_contact_params', array(
        'rest_url' => rest_url('chubes/v1/contact'),
        'nonce' => wp_create_nonce('contact_nonce'),
    ));
}

// Global mobile navigation
wp_enqueue_script('navigation', $theme_dir . '/assets/js/navigation.js', array(), filemtime($theme_path . '/assets/js/navigation.js'), true);
```

### Template Hierarchy System
WordPress template lookups are redirected to organized subdirectories via `/inc/core/filters.php`:
```php
// Archive templates → /templates/archive/
add_filter('archive_template_hierarchy', 'chubes_archive_template_hierarchy');

// Single post templates → /templates/single/  
add_filter('single_template_hierarchy', 'chubes_single_template_hierarchy');

// Page templates → /templates/page/
add_filter('page_template_hierarchy', 'chubes_page_template_hierarchy');

// Taxonomy templates → /templates/taxonomy/
add_filter('taxonomy_template_hierarchy', 'chubes_taxonomy_template_hierarchy');

// Root-level templates → /templates/
add_filter('frontpage_template_hierarchy', 'chubes_frontpage_template_hierarchy');
add_filter('index_template_hierarchy', 'chubes_index_template_hierarchy');
add_filter('404_template_hierarchy', 'chubes_404_template_hierarchy');
add_filter('home_template_hierarchy', 'chubes_home_template_hierarchy');
```

### Performance Optimizations
- Disabled WordPress emoji scripts
- Removed version numbers for security
- Dynamic asset versioning with `filemtime()`
- Selective script/style loading

### Security Measures
- All form submissions use WordPress nonces
- Input sanitization with `wp_unslash()` and `sanitize_text_field()`
- Output escaping with `esc_html()`, `esc_url()`, `esc_attr()`
- Honeypot protection on all contact forms

### Documentation Experience
- **Breadcrumb trail**: Uses codebase helpers to link back to `/docs/{category}/{project}/`.
- **Project meta block**: Highlights both project + category with canonical URLs.
- **Related documentation**: `chubes_get_related_documentation()` + `chubes_get_documentation_archive_link()` ensure suggestions and archive buttons stay within the same project context (e.g., all Data Machine docs).

## Local Development

This theme runs on Local Sites (WordPress local development):
- Build system available via `build.sh` for production deployment
- Direct file editing for development
- Manual testing workflow
- Git-based version control

### Production Build
```bash
# Create optimized theme package
./build.sh

# Creates:
# /dist/chubes.zip    - WordPress admin upload
# /dist/chubes/       - FTP deployment directory
```

## API Integration

WordPress.org Plugin API & Unified Codebase System:
- Real-time install count tracking for codebase taxonomy terms
- Unified repository information system for all project types
- Repository metadata with GitHub and WordPress.org URL fields
- Admin interface with custom columns showing full taxonomy hierarchy
- Card-based public archives with dynamic content type buttons
- Documentation filtering by hierarchical codebase taxonomy
- Project type detection returns standardized values: `wordpress-plugin`, `wordpress-theme`, `discord-bot`, `php-library`

## URL Structure

Clean URL architecture for codebase projects and documentation:

### Project Archives
- `/wordpress-plugins/` - Archive of WordPress plugin projects
- `/wordpress-themes/` - Archive of WordPress theme projects
- `/discord-bots/` - Archive of Discord bot projects
- `/php-libraries/` - Archive of PHP library projects

### Individual Projects
- `/wordpress-plugins/project-name/` - Individual plugin project page
- `/wordpress-themes/project-name/` - Individual theme project page
- `/discord-bots/project-name/` - Individual bot project page
- `/php-libraries/project-name/` - Individual library project page

### Documentation Archives
- `/docs/wordpress-plugins/` - All WordPress plugin documentation
- `/docs/wordpress-themes/` - All WordPress theme documentation
- `/docs/discord-bots/` - All Discord bot documentation
- `/docs/php-libraries/` - All PHP library documentation

### Project Documentation
- `/docs/wordpress-plugins/project-name/` - Project-specific documentation archive
- `/docs/wordpress-plugins/project-name/doc-slug/` - Individual documentation post
- `/docs/wordpress-plugins/project-name/category/doc-slug/` - Hierarchical documentation URLs

## Contact Form Implementation

The contact form follows this secure pattern:
1. Honeypot field for spam detection
2. Timestamp verification
3. WordPress nonce security
4. REST API submission with Fetch API
5. Dual notification emails (admin + user)
6. Success/error feedback to user

Example implementation:
```php
// Nonce verification
if (!wp_verify_nonce($nonce, 'contact_nonce')) {
    return new WP_Error('invalid_nonce', 'Security check failed.', array('status' => 403));
}

// Sanitize inputs
$name = sanitize_text_field($request->get_param('contactName'));
$email = sanitize_email($request->get_param('contactEmail'));
```

Built by [Chris Huber](https://chubes.net) • Custom WordPress Development