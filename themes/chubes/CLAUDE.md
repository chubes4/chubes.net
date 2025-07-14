# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Chubes Theme** is a custom WordPress theme for https://chubes.net, serving as a personal/professional portfolio and service showcase for Chris Huber's WordPress development, creative services, and digital tools business.

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
- **Service-focused contact forms** with anti-spam protection
- **Manual asset management** with conditional loading

### Directory Structure
```
/chubes/
├── Template files (404.php, front-page.php, page-*.php, single*.php, etc.)
├── /assets/
│   ├── /css/ - Page-specific stylesheets
│   ├── /js/ - JavaScript for AJAX, animations, interactions  
│   └── /fonts/ - Custom fonts (Inter, Space Grotesk) + SVG icons
├── /inc/ - Modular PHP functionality:
│   ├── /services/ - Contact forms for different services
│   ├── /portfolio/ - Portfolio custom fields & image overlays
│   ├── /plugins/ - Plugin install tracking system
│   └── /utils/ - Load more, Instagram embeds, breadcrumbs
└── functions.php - Theme setup & asset loading
```

### Key Systems

#### Contact & Lead Generation
Multiple specialized contact forms in `/inc/services/`:
- AI Integration, WordPress Customization, Web Development
- Boat Website Development, SEO Audits
- All use honeypot/timestamp spam protection + AJAX
- Send both admin and user notification emails

#### Portfolio System
- Grid display with hover overlays (`/inc/portfolio/`)
- Custom fields: `project_url`, `tech_stack`
- AJAX load-more functionality
- Image overlay with "Visit Live Site" buttons

#### Plugin Distribution (New Feature)
- WordPress.org API integration for install tracking
- Automated daily updates via cron jobs
- Admin interface for manual updates (`/inc/plugins/`)

### Asset Loading Patterns

CSS/JS conditionally loaded in `functions.php`:
- **Front page**: `home.css`
- **Services page**: `page-services.css` + `services.js`
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

## Recent Major Changes

Based on git history:
- Restructured theme (removed old PHP directory/autoloader)
- Added Games and Plugins custom post types
- Implemented plugin install tracking system
- Preparation for expansion into plugin/game distribution

## Development Guidelines

### Following Cursor Rules
The project maintains documentation in `.cursor/rules/`:
- `project-architecture.mdc` - Current codebase documentation
- `project-plan.mdc` - Future development roadmap
- `documentation-rules.mdc` - Documentation standards

### Code Conventions
- **No build tools** - direct file editing
- **Modular PHP** - organized by feature in `/inc/`
- **Security practices** - honeypot protection, nonce verification
- **Performance optimizations** - disabled emojis, removed WP version

### Common Development Tasks
- **Add new contact form**: Create in `/inc/services/`, add to `functions.php` includes
- **Portfolio modifications**: Work with `/inc/portfolio/` files
- **Asset changes**: Edit directly in `/assets/`, cache-busting automatic
- **Custom post type changes**: Modify `/inc/custom-post-types.php`

## Important Notes

- **No automated testing framework** - manual verification required
- **No CSS preprocessors** - vanilla CSS organization
- **WordPress.org plugin API integration** for install tracking
- **Security focused** - version hiding, spam protection, nonce verification
- **Performance optimized** - selective asset loading, emoji disabled