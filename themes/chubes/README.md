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

### Service Contact System
Multiple specialized contact forms with AJAX submission:
- AI Integration Services (`ai-integration-contact-form.php`)
- WordPress Customization (`wordpress-customization-contact.php`)
- Web Development (`web-development-contact.php`) 
- Boat Website Development (`boat-website-contact.php`)
- SEO Audits (`free-local-seo-audits.php`)

Each form includes:
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
│   ├── archive.php                # Dynamic archive headers
│   ├── front-page.php             # Homepage
│   ├── page-contact.php           # Contact page
│   └── single-portfolio.php       # Portfolio single view
├── /assets/
│   ├── /css/                     # Page-specific stylesheets
│   ├── /js/                      # AJAX, animations, interactions
│   └── /fonts/                   # Inter, Space Grotesk + SVG icons
├── /inc/
│   ├── contact-ajax.php         # Contact form implementation
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
    wp_enqueue_style('home-style', '...home.css');
}

// Portfolio archive load-more functionality  
if (is_post_type_archive('portfolio')) {
    wp_enqueue_script('load-more', '...load-more.js');
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

## Contact Forms

All forms follow the same pattern:
1. Honeypot field for spam detection
2. Timestamp verification
3. WordPress nonce security
4. AJAX submission with jQuery
5. Dual notification emails (admin + user)
6. Success/error feedback to user

Example implementation:
```php
// Nonce verification
if (!wp_verify_nonce($_POST['nonce'], 'service_contact_nonce')) {
    wp_die('Security check failed');
}

// Sanitize inputs
$name = sanitize_text_field(wp_unslash($_POST['name']));
$email = sanitize_email(wp_unslash($_POST['email']));
```

Built by [Chris Huber](https://chubes.net) • Custom WordPress Development