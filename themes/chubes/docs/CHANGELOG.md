# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2025-11-30

### Changed (Breaking)
- **Major Architectural Refactor**: Moved contact forms, documentation system, and codebase tracking to separate plugins
  - Contact functionality moved to `chubes-contact` plugin
  - Documentation system moved to `chubes-docs` plugin
  - Codebase taxonomy and tracking moved to `chubes-docs` plugin
- **File Structure Reorganization**:
  - Templates moved from theme root to `/templates/` subdirectories (`archive/`, `single/`, `page/`)
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
- Documentation system with codebase taxonomy
- Asset management and responsive design
- Custom navigation and footer components