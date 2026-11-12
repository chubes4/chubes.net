# Installation

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Node.js 16 or higher (for development)

## Installation Steps

1. Download the plugin ZIP file from the releases page
2. In WordPress admin, go to Plugins > Add New
3. Click "Upload Plugin"
4. Upload the `chubes-contact.zip` file
5. Activate the plugin

## Development Setup

For development:

1. Clone the repository
2. Run `npm install` to install dependencies
3. Run `npm run start` for development server
4. Run `npm run build` to create production build

## Features

- Gutenberg contact form block
- REST API endpoint for form submission
- Security features: nonce verification, honeypot, timestamp checks
- PSR-4 autoloading
- Email notifications (configurable)