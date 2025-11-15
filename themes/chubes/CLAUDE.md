# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Chubes Theme** is a custom WordPress theme for https://chubes.net, serving as Chris Huber's showcase for AI-first WordPress development, music journalism, and content automation systems.

## Development Commands

This theme uses traditional WordPress development with automated build system:

- **Bash build script** - `build.sh` creates production WordPress theme packages
- **No package.json** - Direct CSS/JS editing without Node.js tooling
- **Asset cache busting** - Uses `filemtime()` for dynamic versioning
- **Local development** - Uses Local Sites environment
- **No automated testing** - Manual testing required

### Build Process
```bash
# Create production theme package
./build.sh

# Outputs:
# /dist/chubes.zip - For WordPress admin upload
# /dist/chubes/ - Unzipped directory for FTP deployment
```

## Architecture Overview

### Core Structure
- **Traditional WordPress theme** with modular PHP organization
- **Custom post types**: Journal, Game, Documentation
- **Codebase taxonomy**: Hierarchical organization for documentation
- **Contact form system** with anti-spam protection
- **Manual asset management** with conditional loading

### Directory Structure
```
/chubes/
├── /templates/ - Organized template files by type:
│   ├── /parts/ - Template parts organized by functionality
│   │   └── /codebase/ - Codebase-specific components
│   │       └── codebase-card.php - Individual codebase item display
│   ├── /archive/ - All archive templates
│   │   ├── archive.php - Generic archive template with dynamic headers
│   │   ├── archive-journal.php - Journal archive
│   │   ├── archive-codebase.php - Codebase taxonomy archive
│   │   ├── archive-documentation.php - Documentation archive
│   │   ├── archive-docs-category.php - Documentation category archive
│   │   ├── archive-docs-taxonomy.php - Documentation taxonomy archive
│   │   └── search.php - Search results template
│   ├── /single/ - All single post templates
│   │   ├── single.php - Default single post template
│   │   └── single-documentation.php - Documentation single view
│   ├── /page/ - All page templates
│   │   ├── page.php - Default page template
│   │   └── page-contact.php - Contact page template
│   ├── /taxonomy/ - All taxonomy templates
│   │   └── taxonomy-codebase.php - Individual codebase taxonomy pages
│   ├── 404.php - Custom 404 error page
│   ├── front-page.php - Homepage template
│   ├── home.php - Blog posts index template
│   └── index.php - Default template
├── /assets/
│   ├── /css/ - Page-specific stylesheets
│   ├── /js/ - JavaScript for navigation and interactions  
│   └── /fonts/ - Custom fonts (Inter, Space Grotesk) + SVG icons
├── /inc/ - Modular PHP functionality:
│   ├── breadcrumbs.php - Navigation breadcrumb system
│   ├── contact-rest-api.php - Contact form REST API endpoint
│   ├── /core/ - Core WordPress functionality:
│   │   ├── assets.php - Centralized asset enqueuing (CSS/JS)
│   │   ├── custom-post-types.php - Journal, Game, Documentation CPTs
│   │   ├── custom-taxonomies.php - Codebase taxonomy registration
│   │   ├── rewrite-rules.php - Custom URL rewrite rules for documentation
│   │   ├── filters.php - Template hierarchy filters for organized template loading
│   │   └── related-posts.php - Related posts functionality
│   ├── /plugins/ - Codebase tracking system with taxonomy fields
│   └── /utils/ - Instagram embeds
├── /dist/ - Production build directory with optimized theme files
└── functions.php - Theme setup, navigation, & module includes
```

### Template Hierarchy System

The theme uses WordPress template hierarchy filters to organize templates into logical subdirectories within `/templates/`. All template lookups are redirected via `/inc/core/filters.php`:

- **Archive templates** → `/templates/archive/` (archive.php, archive-journal.php, etc.)
- **Single post templates** → `/templates/single/` (single.php, single-documentation.php)  
- **Page templates** → `/templates/page/` (page.php, page-contact.php)
- **Taxonomy templates** → `/templates/taxonomy/` (taxonomy-codebase.php)
- **Root-level templates** → `/templates/` (404.php, front-page.php, home.php, index.php)

This system maintains WordPress template hierarchy while enabling organized file structure.

### Key Systems

#### Contact System
Simple, secure contact form system using REST API:
- Single contact form with honeypot/timestamp spam protection
- REST API submission to `/wp-json/chubes/v1/contact`
- Assets and nonce managed in `/inc/core/assets.php`
- REST endpoint handler in `/inc/contact-rest-api.php`
- Sends both admin and user notification emails
- WordPress nonce security and input sanitization

#### Codebase Documentation & Tracking System
- **Documentation CPT** with codebase taxonomy organization
- **Unified codebase taxonomy** with four top-level categories: `wordpress-plugins`, `wordpress-themes`, `discord-bots`, `php-libraries`
- **Hierarchical project structure** (category → project-name → subcategories)
- **Repository information tracking** for GitHub and WordPress.org projects
- **Install count tracking** for WordPress plugins and themes via WordPress.org API
- **Admin interface** with manual update controls and statistics
- **Custom admin columns** showing full taxonomy hierarchy path
- **Card-based public archive** with dynamic content type buttons
- **Project type detection** returns standardized values: `wordpress-plugin`, `wordpress-theme`, `discord-bot`, `php-library`
- **Hierarchy helpers** in `inc/core/custom-taxonomies.php`:
  - `chubes_get_codebase_primary_term($terms)` – pick the deepest assigned term.
  - `chubes_get_codebase_project_term_from_terms($terms)` – derive project-level term (child of top-level category).
  - `chubes_get_codebase_top_level_term_from_terms($terms)` – resolve parent category term (`wordpress-plugins`, etc.).
  - Always prefer these helpers when building URLs or breadcrumbs instead of walking parents manually.

### Asset Loading Patterns

All CSS/JS conditionally loaded in `/inc/core/assets.php`:
- **Front page**: `home.css`
- **Documentation posts**: `documentation.css` for single documentation view
- **Archive pages**: `archives.css` for all archive pages and taxonomy pages
- **Contact page**: `contact.css` and `contact.js` for contact form (with REST API)
- **Global**: `navigation.js` for mobile menu functionality

### Data Passing Convention
PHP to JS data uses `wp_localize_script` with naming pattern:
- `*_params` or `*_vars` (e.g., `chubes_vars`)

### Custom Post Types & Taxonomy
- **Journal** (`/journal`) - Blog-style content
- **Game** (`/games`) - Gutenberg block-based games
- **Documentation** (`/docs`) - Plugin guides and tutorials
- **Codebase taxonomy** - Hierarchical organization with four categories:
  - `wordpress-plugins` - WordPress plugin projects
  - `wordpress-themes` - WordPress theme projects
  - `discord-bots` - Discord bot projects
  - `php-libraries` - PHP library projects

### URL Architecture
Clean URL structure for codebase projects and documentation:
- `/wordpress-plugins/` - Archive of WordPress plugin projects
- `/wordpress-themes/` - Archive of WordPress theme projects
- `/discord-bots/` - Archive of Discord bot projects
- `/php-libraries/` - Archive of PHP library projects
- `/wordpress-plugins/project-name/` - Individual project page
- `/docs/wordpress-plugins/` - Documentation category archive
- `/docs/wordpress-plugins/project-name/` - Project-specific documentation archive
- `/docs/wordpress-plugins/project-name/doc-slug/` - Individual documentation post

### Navigation System
Advanced parent page navigation with dynamic breadcrumb support:
- **Context-aware back navigation** (Blog posts → Blog, Documentation → Docs, etc.)
- **Hierarchical page support** with ancestor detection
- **Custom post type archive detection**
- Breadcrumb functionality in `/inc/breadcrumbs.php`

Documentation breadcrumbs now mirror the `/docs/{category}/{project}/` routes. They leverage the
hierarchy helpers, so any new documentation UI should pass full term arrays into the helpers
instead of hardcoding taxonomy IDs.

Related docs and the “Browse all …” archive button rely on `chubes_get_documentation_archive_link()`
and share the same helper layer. If those links are wrong, verify the taxonomy assignments and
helper outputs before changing templates.

## Recent Major Changes

Based on git history and current implementation:
- **Project type standardization** (unified values: `wordpress-plugin`, `wordpress-theme`, `discord-bot`, `php-library`)
- **Template config array updates** (codebase-card.php, taxonomy-codebase.php, archive-docs-taxonomy.php use new project type values)
- **CSS selector updates** (project type data attributes updated for styling consistency)
- **Four-category codebase system** (wordpress-plugins, wordpress-themes, discord-bots, php-libraries)
- **Clean URL architecture** (custom rewrites for category archives and project pages)
- **Build directory migration** (moved from `/build/` to `/dist/` for production output)

## Development Guidelines

### Development Documentation
Project documentation is maintained in:
- `CLAUDE.md` - Current codebase architecture and guidance
- `README.md` - Developer setup and overview

### Code Conventions
- **Build system available** - `build.sh` for production deployment
- **Direct file editing** - no Node.js build pipeline
- **Modular PHP** - organized by feature in `/inc/`
- **Security practices** - honeypot protection, nonce verification
- **Performance optimizations** - disabled emojis, removed WP version

### Common Development Tasks
- **Template modifications**: Edit files in `/templates/` subdirectories, organized by type
- **Template hierarchy changes**: Modify `/inc/core/filters.php` for new template organization
- **Contact form modifications**: Update `/templates/page/page-contact.php` (form HTML), `/assets/js/contact.js` (client logic), and `/inc/contact-rest-api.php` (REST endpoint handler). Assets enqueued via `/inc/core/assets.php`.
- **Asset changes**: Edit directly in `/assets/`, cache-busting automatic via `filemtime()`
- **Custom post type changes**: Modify `/inc/core/custom-post-types.php` and `/inc/core/custom-taxonomies.php`
- **Codebase system changes**: Work with `/inc/plugins/codebase-repository-info-fields.php` and `/inc/plugins/track-codebase-installs.php` for unified tracking system
- **Admin column customization**: Modify hierarchy display functions in `/inc/core/custom-taxonomies.php`

## Important Notes

- **No automated testing framework** - manual verification required
- **No CSS preprocessors** - vanilla CSS organization
- **WordPress.org plugin API integration** for install tracking
- **Security focused** - version hiding, spam protection, nonce verification
- **Performance optimized** - selective asset loading, emoji disabled