# Chubes Theme

Custom WordPress theme for [https://chubes.net](https://chubes.net) - Chris Huber's professional portfolio and service showcase.

## Overview

This theme serves as a portfolio website and showcase platform for WordPress development, music journalism, and digital tools. Built with performance and modularity in mind.

## Architecture

### Development Approach
- **Traditional WordPress** - No build tools or package.json
- **Modular PHP structure** - Organized by feature in `/inc/` directory
- **Dynamic asset loading** - Conditional CSS/JS with cache-busting via `filemtime()`
- **Security-focused** - Version hiding, nonce verification, honeypot protection

### Custom Post Types
```php
// Portfolio - Project showcases
register_post_type('portfolio', $args);

// Journal - Personal blog content  
register_post_type('journal', $args);

// Game - Interactive content hosting
register_post_type('game', $args);

// Plugin - WordPress plugin distribution
register_post_type('plugin', $args);
```

### Contact System
Unified contact form with specialized styling:
- Main contact form in `page-contact.php` with comprehensive spam protection
- AJAX submission handled by `/inc/contact-ajax.php`
- Service-specific CSS/JS assets for different contexts:
  - `ai-integration-contact.css/.js` - AI integration services
  - `wordpress-customization-contact.css/.js` - WordPress customization
  - `web-dev-contact.css/.js` - Web development services
  - `boat-contact-modal.css/.js` - Marine industry websites
  - `free-local-seo-audits.css` and `seo-audit.js` - SEO audits

Form features:
- Honeypot and timestamp spam protection
- AJAX submission with user feedback
- Admin and user notification emails
- WordPress nonce security

### Plugin Tracking System
Automated WordPress.org API integration:
```php
// Fetch plugin install counts
chubes_fetch_plugin_data($plugin_slug);

// Update all plugins daily via cron
wp_schedule_event(time(), 'daily', 'chubes_update_plugin_installs');

// Get total installs across all plugins
chubes_get_total_plugin_installs();
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
├── Template Files
│   ├── 404.php                    # Custom error page
│   ├── archive.php                # Generic archive with dynamic headers
│   ├── archive-journal.php        # Journal archive
│   ├── archive-portfolio.php      # Portfolio archive
│   ├── front-page.php             # Homepage
│   ├── home.php                   # Blog home template
│   ├── index.php                  # Default template
│   ├── page-contact.php           # Contact page
│   ├── single-portfolio.php       # Portfolio single view
│   └── single.php                 # Default single post
├── /assets/
│   ├── /css/                     # Page-specific stylesheets
│   ├── /js/                      # AJAX, animations, interactions
│   └── /fonts/                   # Inter, Space Grotesk + SVG icons
├── /inc/
│   ├── breadcrumbs.php          # Navigation breadcrumb system
│   ├── contact-ajax.php         # Contact form implementation
│   ├── custom-post-types.php    # Portfolio, Journal, Game, Plugin CPTs
│   ├── customizer.php           # Theme customizer settings
│   ├── /portfolio/              # Portfolio custom fields & overlays
│   ├── /plugins/                # Plugin tracking system
│   └── /utils/                  # Load more, Instagram embeds
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

// Portfolio archive load-more functionality  
if (is_post_type_archive('portfolio')) {
    wp_enqueue_script('load-more', get_template_directory_uri() . '/assets/js/load-more.js', array('jquery'), filemtime(get_template_directory() . '/assets/js/load-more.js'), true);
    wp_localize_script('load-more', 'loadmore_params', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'current_page' => max(1, get_query_var('paged')),
        'max_page' => $wp_query->max_num_pages
    ));
}
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
- No build process required
- Direct file editing
- Manual testing workflow
- Git-based version control

## API Integration

WordPress.org Plugin API:
- Real-time install count tracking
- Daily automated updates via cron
- Admin interface for manual updates
- Install aggregation across all plugins

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