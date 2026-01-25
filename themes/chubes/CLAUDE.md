# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository. It documents the current implementation, coding conventions, and operational constraints. All statements are present-tense and reflect repository contents.

## Project Overview

Chubes Theme is a custom WordPress theme for https://chubes.net. It is a modular, performance-oriented theme that exposes a documentation/codebase tracking system and a lightweight templating organization.

## Agent Guidelines

- Verify every documented feature against the repository before making or asserting changes.
- Make minimal edits to documentation: change only what is inaccurate while preserving correct content.
- Preserve present-tense, verifiable descriptions only.
- Remove references to code or files that are not present in the repository.

## Development Commands

- The project uses a bash build script for packaging: `./build.sh`.
- There is no Node.js build pipeline or `package.json` in the repository; CSS and JS are edited directly.
- Asset cache-busting uses `filemtime()` at enqueue time in PHP.

### Build Process

- Run: `./build.sh`
- Output: `/build/chubes.zip` (production theme package)
- The build script copies repository files into a temporary build directory and packages them. The script excludes development files such as `.git*`, `dist/`, `build/`, `build.sh`, `CLAUDE.md`, and `README.md` during packaging (see `build.sh` for exact exclusions).

## Architecture Overview

### Core Structure
- Traditional WordPress theme with modular PHP organization under `/inc/core/` and templates under `/inc/core/templates/`.
- Custom post types (registered in `/inc/journal/journal-post-type.php`): `journal`.
- Unified hierarchical project taxonomy (registered in the `chubes-docs` plugin) used to organize project documentation and repositories.
- Asset enqueuing and conditional loading are centralized in `/inc/core/assets.php`.
- Template hierarchy filters implemented in `/inc/core/filters.php` route templates to the flattened directory.

### Directory Notes (selected)
- `/inc/core/templates/` contains the theme template files (front-page, archive, single, page, etc.).
- `/assets/css/` and `/assets/js/` contain styles and scripts loaded conditionally by the theme.
- `/inc/core/` contains modular PHP: assets, breadcrumbs, custom post types, filters, and back navigation.
- `/inc/utils/` contains utility functions like Instagram embeds.
- `functions.php` wires up theme setup and includes `/inc/` modules.

## Template Hierarchy System

- Template lookups are routed to `/inc/core/templates/` via filters implemented in `/inc/core/filters.php`.

## Key Systems



### Codebase Documentation & Tracking
- Documentation is implemented as a `documentation` post type and a hierarchical `codebase` taxonomy (both registered in the `chubes-docs` plugin).
- Top-level taxonomy categories are expected to be: `wordpress-plugins`, `wordpress-themes`, `discord-bots`, `php-libraries` (these names are used by templates and helpers in the repository).
- Helper functions in the chubes-docs plugin `inc/Core/Codebase.php` provide canonical resolution helpers for project taxonomy; templates and breadcrumb logic prefer these helpers.
- Repository metadata fields and install tracking are provided by the `chubes-docs` plugin, which hooks into the theme's `chubes_codebase_registered` action.

### Related Posts
- Related documentation is selected using hierarchy-aware logic implemented in the `chubes-docs` plugin's `inc/Templates/RelatedPosts.php`.

## Asset Loading Patterns

- Assets are enqueued in `/inc/core/assets.php` with conditional checks:
  - Front page: `assets/css/home.css`
  - Documentation single posts: plugin-managed docs CSS (enqueued by `chubes-docs`)
  - Archives and taxonomy pages: `assets/css/archives.css`
  - Global mobile navigation JS: `assets/js/navigation.js`
- Asset versions use `filemtime()` to bust caches.
- PHP-to-JS data is localized via `wp_localize_script()` using `*_params` or `*_vars` naming patterns.

## URLs and Routing

- Project archive slugs follow the top-level taxonomy slugs (e.g., `/wordpress-plugins/`, `/wordpress-themes/`, `/discord-bots/`, `/php-libraries/`).
- Documentation archives and single docs use routes under `/docs/` and are implemented with custom rewrite rules in the chubes-docs plugin `inc/Core/RewriteRules.php`.

## Development Guidelines

- Edit templates in `/inc/core/templates/` and functionality in `/inc/`.
- Use the helper functions in the chubes-docs plugin `inc/Core/Codebase.php` for all codebase-related URL and breadcrumb generation.
- The project does not use automated tests; manual verification is required for changes.

## Special Rules & Constraints

- Preserve existing CLAUDE.md structure and formatting when updating agent-facing guidance.
- All changes to documentation must be verifiable against code before committing.

## Architectural Principles

- KISS: prefer simple, centralized solutions.
- Single responsibility: keep files focused on a single responsibility.
- Centralize complex transformations using WordPress filters and actions.
- Use the backend as the single source of truth and avoid duplicate data sources.

## Contact

- Owner: Chris Huber — https://chubes.net
