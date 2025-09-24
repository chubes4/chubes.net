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

// Codebase taxonomy - Unified hierarchical organization for all projects
register_taxonomy('codebase', array('documentation', 'journal', 'game'), $args);
```

### Contact System
Simple, unified contact form:
- Main contact form in `page-contact.php` with comprehensive spam protection
- AJAX submission handled by `/inc/contact-ajax.php`
- Contact assets (`contact.css`, `contact.js`) exist but are not currently enqueued in functions.php

Form features:
- Honeypot and timestamp spam protection
- AJAX submission with user feedback
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

// Project type detection
$project_type = chubes_get_codebase_project_type($term);
```

### Navigation System
Dynamic parent page detection in `functions.php`:
```php
// Context-aware breadcrumb navigation
chubes_get_parent_page();
// Returns: ['url' => $parent_url, 'title' => $parent_title]
```

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
│   ├── /js/                      # AJAX, animations, interactions
│   └── /fonts/                   # Inter, Space Grotesk + SVG icons
├── /inc/
│   ├── breadcrumbs.php          # Navigation breadcrumb system
│   ├── contact-ajax.php         # Contact form implementation
│   ├── /core/                   # Core WordPress functionality
│   │   ├── custom-post-types.php    # Journal, Game, Documentation CPTs
│   │   ├── custom-taxonomies.php    # Plugin taxonomy registration
│   │   ├── rewrite-rules.php        # Custom URL rewrite rules for documentation
│   │   └── filters.php              # Template hierarchy filters for organized template loading
│   ├── /plugins/                # Codebase tracking & taxonomy fields
│   └── /utils/                  # Instagram embeds
├── /dist/                       # Production build directory
└── functions.php                # Theme setup & asset management
```

## Key Features

### Asset Loading
Conditional loading based on page context:
```php
// Homepage specific styles
if (is_front_page()) {
    wp_enqueue_style('home-style', get_template_directory_uri() . '/assets/css/home.css', array(), filemtime(get_template_directory() . '/assets/css/home.css'));
}

// Documentation specific styles
if (is_singular('documentation')) {
    wp_enqueue_style('documentation-style', get_template_directory_uri() . '/assets/css/documentation.css', array(), filemtime(get_template_directory() . '/assets/css/documentation.css'));
}

// Archives styles on archive and taxonomy pages
if (is_post_type_archive() || is_tax('codebase') || is_post_type_archive('documentation') || is_category() || is_tag() || is_tax()) {
    wp_enqueue_style('archives-style', get_template_directory_uri() . '/assets/css/archives.css', array(), filemtime(get_template_directory() . '/assets/css/archives.css'));
}

// Global mobile navigation
wp_enqueue_script('navigation', get_template_directory_uri() . '/assets/js/navigation.js', array('jquery'), filemtime(get_template_directory() . '/assets/js/navigation.js'), true);
```

*Note: Contact page assets exist (`/assets/css/contact.css`, `/assets/js/contact.js`) but are not currently enqueued conditionally in functions.php.*

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
- Project type detection (plugin, theme, app, tool) based on taxonomy hierarchy

## Contact Form Implementation

The contact form follows this secure pattern:
1. Honeypot field for spam detection
2. Timestamp verification
3. WordPress nonce security
4. AJAX submission with jQuery
5. Dual notification emails (admin + user)
6. Success/error feedback to user

Example implementation:
```php
// Nonce verification
if (!wp_verify_nonce($_POST['nonce'], 'contact_nonce')) {
    wp_die('Security check failed');
}

// Sanitize inputs
$name = sanitize_text_field(wp_unslash($_POST['name']));
$email = sanitize_email(wp_unslash($_POST['email']));
```

Built by [Chris Huber](https://chubes.net) • Custom WordPress Development