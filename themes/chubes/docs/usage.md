# Usage Guide

## Homepage Customization

The theme supports Gutenberg blocks for homepage content:

1. Go to Appearance > Customize
2. Add blocks to create your homepage layout
3. Use the homepage columns component for structured content

## Content Types

### Posts
- Standard WordPress posts appear on the blog page
- Use categories and tags for organization
- Featured images are displayed prominently

### Journal Posts
- Custom post type for personal journal entries
- Accessible at `/journal`
- Supports all standard post features
- [See detailed Journal documentation](components/journal.md)

### Pages
- Standard WordPress pages
- Use for static content like About, Contact

## Navigation

The theme includes:
- Main navigation menu
- Mobile-responsive overlay menu
- Breadcrumb navigation on content pages
- Back navigation links

## Asset Loading

CSS and JavaScript are loaded conditionally:
- Homepage styles on front page
- Archive styles on archive pages
- Single post styles on individual posts
- Global navigation scripts on all pages

## Customization

### CSS Variables
The theme uses CSS variables defined in `assets/css/root.css`:
- `--chubes-body-text-color`
- `--chubes-accent-color-1`
- `--chubes-accent-color-2`

### Template Hierarchy
Templates are located in `/inc/core/templates/` and follow WordPress standards.

### Hooks and Filters
The theme provides several WordPress hooks:
- `chubes_before_main_content` - for breadcrumbs
- Template hierarchy filters for custom template loading