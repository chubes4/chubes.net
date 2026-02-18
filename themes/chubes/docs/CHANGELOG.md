# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.9] - 2026-02-18

### Changed
- Add docs-api-hint styling for homepage

## [2.0.8] - 2026-02-14

### Changed
- Move header outside pre for clean Prism highlighting
- Add auto language detection for code blocks
- Broaden code block detection to include raw pre/code HTML
- Add Prism.js syntax highlighting for code blocks
- codebase term to project in docs

### Fixed
- Fix Prism autoloader languages_path timing
- Fix Prism syntax highlighting and code block layout
- Fix code block styling for REST API posts
- Fix syntax highlighting + mobile code block scroll

## [2.0.7] - 2026-01-23

- Added inline search form styles (.search-form-inline) for reuse by plugins
- Added typography scale CSS variables (--chubes-font-size-*)
- Added spacing scale CSS variables (--chubes-space-*)

## [2.0.6] - 2026-01-23

- Add responsive font sizing for archive headers on mobile
- Center post title with flexbox in single template
- Remove build.sh in favor of Homeboy builds

## [2.0.5] - 2026-01-20

- Changed: consolidated CSS design system with semantic variables for colors, shadows, and overlays

## [2.0.4] - 2026-01-20

- docs
- build system update

## [2.0.3] - 2025-12-05

### Added
- **Code Block Enhancements**: Copy-to-clipboard functionality with language labels for all core/code blocks
- **Theme Customizer**: Hero section customization (heading, image, bio) via WordPress Customizer
- **Documentation**: Installation and usage guides added to /docs/
- **SVG Sprite System**: Reusable icon system with copy/check icons

### Changed
- **Archive System**: Refactored from action-based to filter-based system for better extensibility
- **CSS Design System**: Added semantic color variables for improved theming
- **Template Refinements**: Enhanced responsive design and code organization
- **Asset Management**: Improved conditional loading and caching

### Technical
- Internal architectural improvements maintaining backward compatibility

## [2.0.2] - 2025-12-02

### Added
- **Code Block Enhancements**: Added copy-to-clipboard functionality for all `core/code` blocks with visual feedback
- **SVG Sprite System**: Created `assets/icons/chubes.svg` sprite file with copy and check icons for reusable iconography
- **Language Labels**: Code blocks now display the programming language when specified (from `language-*` class)
- **Documentation**: Added comprehensive installation and usage guides in `/docs/`
- **Customizer Integration**: Added theme customizer support via `inc/core/customizer.php`

### Changed
- Updated README.md to reflect current file structure and removed outdated codebase taxonomy references
- Updated functions.php to include new code-blocks and customizer modules
- Various template refinements and documentation improvements

## [2.0.1] - 2025-11-30

### Changed
- **Template Organization**: Moved theme templates from `/templates/` to `/inc/core/templates/` for better code organization
- **Custom Post Type Structure**: Reorganized journal CPT registration into dedicated `/inc/journal/journal-post-type.php` file
- **Documentation Updates**: Updated all documentation files to reflect new file structure and organization
- **Template Hierarchy**: Updated filters in `/inc/core/filters.php` to reference new template locations

## [2.0.0] - 2025-11-30

### Changed (Breaking)
- **Major Architectural Refactor**: Moved contact forms, documentation system, and project tracking to separate plugins
  - Contact functionality moved to `chubes-contact` plugin
  - Documentation system moved to `chubes-docs` plugin
  - Project taxonomy and tracking moved to `chubes-docs` plugin
- **File Structure Reorganization**:
  - Templates moved to `/inc/core/templates/`
  - PHP modules restructured under `/inc/core/`, `/inc/homepage/`, `/inc/journal/`
  - Removed `/php/` directory and autoloader system
- **Theme Name**: Changed from "Chubes Theme" to "Chubes"
- **CSS Design System**: Added `assets/css/root.css` with CSS variables and standardized button styles
- **Fonts**: Removed local font files (Inter, Space Grotesk), switched to Google Fonts API
- **Dependencies**: Removed jQuery dependency
- **Search Functionality**: Updated to use filters for extensible post type inclusion
- **Build System**: Updated `build.sh` to exclude new development files and directories

### Added
- **Documentation System**: Added comprehensive `/docs/` directory with architecture and component documentation
- **CSS Variables**: Implemented design system with semantic color variables (`--chubes-body-text-color`, `--chubes-accent-color-1`, etc.)
- **Template Hierarchy**: Enhanced template routing system for organized file structure
- **Customizer Settings**: Added homepage custom content section with Gutenberg block support
- **Navigation System**: Improved mobile navigation with overlay menu and search functionality
- **Asset Management**: Conditional CSS/JS loading based on page context
- **Performance**: Removed WordPress emoji scripts, improved asset caching with `filemtime()`

### Removed
- Contact form functionality (now in `chubes-contact` plugin)
- Documentation post type and taxonomy (now in `chubes-docs` plugin)
- Codebase tracking and repository integration (now in `chubes-docs` plugin)
- Local font files (Inter-VariableFont, SpaceGrotesk-VariableFont)
- jQuery dependency
- Old PHP autoloader and `/php/` directory structure
- Various service-specific pages and functionality

### Fixed
- Mobile navigation and responsive design improvements
- Search result styling and post type handling
- Breadcrumb navigation for improved user experience
- Image overlay effects and gallery styling

### Technical
- **WordPress Compatibility**: Maintained compatibility with WordPress core features
- **Security**: Enhanced with improved nonce handling and input sanitization
- **Performance**: Optimized asset loading and reduced HTTP requests
- **Maintainability**: Improved code organization and separation of concerns

## [1.0.0] - 2025-02-25

### Added
- Initial release of Chubes WordPress theme
- Basic theme structure with custom post types (journal, portfolio, game)
- Contact form functionality
- Documentation system with project taxonomy
- Asset management and responsive design
- Custom navigation and footer components
