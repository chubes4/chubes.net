# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.3] - 2026-11-12

### Fixed
- restore the wordpress extension and untrack Homeboy runtime state

## [0.1.2] - 2026-11-12

### Fixed
- ship src/ for chubes-contact and poem-block

## [0.1.1] - 2025-11-30

### Changed
- Incorporated uncommitted local development changes and improvements

## [0.1.0] - 2025-11-30

### Added
- Initial release: Migrated contact form functionality from Chubes theme to standalone plugin
- Gutenberg block-based contact form with drag-and-drop functionality
- REST API endpoint (POST /wp-json/chubes/v1/contact) for secure form submissions
- Security features: nonce verification, honeypot spam protection, timestamp validation
- Email notifications to admin and user confirmation emails
- Vanilla JavaScript for asynchronous submissions without page reloads
- PSR-4 autoloading and WordPress coding standards compliance
- Production build system with ./build.sh for ZIP generation
