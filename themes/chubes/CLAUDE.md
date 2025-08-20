# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Chubes Theme** is a custom WordPress theme for https://chubes.net, serving as a personal/professional portfolio and showcase for Chris Huber's AI-first WordPress development, music journalism, and content automation systems.

*For detailed context about Chris's background, expertise, and strategic positioning, see `who-is-chubes.md` (private developer reference, gitignored).*

## Development Commands

This theme uses traditional WordPress development without build tools:

- **No package.json or build process** - Direct CSS/JS editing
- **Asset cache busting** - Uses `filemtime()` for dynamic versioning
- **Local development** - Uses Local Sites environment
- **No automated testing** - Manual testing required

## Architecture Overview

### Core Structure
- **Traditional WordPress theme** with modular PHP organization
- **Custom post types**: Portfolio, Journal, Game, Plugin
- **Contact form system** with anti-spam protection
- **Manual asset management** with conditional loading

### Directory Structure
```
/chubes/
├── Template files:
│   ├── 404.php - Custom 404 error page
│   ├── archive.php - Generic archive template with dynamic headers
│   ├── front-page.php - Homepage template
│   ├── page-*.php - Specialized page templates
│   └── single*.php - Post type templates
├── /assets/
│   ├── /css/ - Page-specific stylesheets
│   ├── /js/ - JavaScript for AJAX, animations, interactions  
│   └── /fonts/ - Custom fonts (Inter, Space Grotesk) + SVG icons
├── /inc/ - Modular PHP functionality:
│   ├── contact-ajax.php - Contact form implementation
│   ├── /portfolio/ - Portfolio custom fields & image overlays
│   ├── /plugins/ - Plugin install tracking system
│   └── /utils/ - Load more, Instagram embeds
└── functions.php - Theme setup, navigation, & asset loading
```

### Key Systems

#### Contact System
Simple, secure contact form system:
- Single contact form with honeypot/timestamp spam protection
- AJAX submission with user feedback
- Sends both admin and user notification emails
- WordPress nonce security and input sanitization

#### Portfolio System
- Grid display with hover overlays (`/inc/portfolio/`)
- Custom fields: `project_url`, `tech_stack`
- AJAX load-more functionality
- Image overlay with "Visit Live Site" buttons

#### Plugin Distribution System
- **Full WordPress.org API integration** for real-time install tracking
- **Automated daily cron updates** via `wp_schedule_event()`
- **Admin interface** with manual update controls (`/inc/plugins/`)
- **Install count aggregation** across all plugins
- **Auto-updates on post save** for immediate data refresh

### Asset Loading Patterns

CSS/JS conditionally loaded in `functions.php`:
- **Front page**: `home.css`
- **Contact page**: `contact.css` + `contact.js`
- **Portfolio archive**: `load-more.js` with localized AJAX params
- **Global**: `reveal.js` for scroll animations

### Data Passing Convention
PHP to JS data uses `wp_localize_script` with naming pattern:
- `*_params` or `*_vars` (e.g., `loadmore_params`, `chubes_vars`)

### Custom Post Types
- **Portfolio** (`/portfolio`) - Project showcases
- **Journal** (`/journal`) - Blog-style content
- **Game** (`/game`) - Game hosting/showcase (new)
- **Plugin** (`/plugin`) - Plugin distribution (new)

### Navigation System
Advanced parent page navigation implemented in `functions.php:70-138`:
- **Dynamic breadcrumb generation** for all post types and archives
- **Context-aware back navigation** (Blog posts → Blog, Portfolio → Portfolio, etc.)
- **Hierarchical page support** with ancestor detection
- **Custom post type archive detection**

## Recent Major Changes

Based on git history:
- **Theme restructuring** (removed old PHP directory/autoloader)
- **Asset reorganization** (moved CSS/fonts from root to `/assets/`)
- **New template additions** (`404.php`, `archive.php` with dynamic headers)
- **Enhanced plugin tracking system** (full API integration, admin interface)
- **Advanced navigation system** (parent page detection, dynamic breadcrumbs)

## Development Guidelines

### Development Documentation
Project documentation is maintained in:
- `CLAUDE.md` - Current codebase architecture and guidance
- `README.md` - Developer setup and overview

### Code Conventions
- **No build tools** - direct file editing
- **Modular PHP** - organized by feature in `/inc/`
- **Security practices** - honeypot protection, nonce verification
- **Performance optimizations** - disabled emojis, removed WP version

### Common Development Tasks
- **Contact form modifications**: Update `/inc/contact-ajax.php` and contact page assets
- **Portfolio modifications**: Work with `/inc/portfolio/` files
- **Asset changes**: Edit directly in `/assets/`, cache-busting automatic
- **Custom post type changes**: Modify `/inc/custom-post-types.php`

## Important Notes

- **No automated testing framework** - manual verification required
- **No CSS preprocessors** - vanilla CSS organization
- **WordPress.org plugin API integration** for install tracking
- **Security focused** - version hiding, spam protection, nonce verification
- **Performance optimized** - selective asset loading, emoji disabled