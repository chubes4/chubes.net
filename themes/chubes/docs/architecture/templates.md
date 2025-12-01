Template hierarchy and lookup

- Template organization: all theme templates live under the templates/ directory organized by type:
  - templates/archive/
  - templates/single/
  - templates/page/

- Lookup routing: the theme maps WordPress template hierarchy lookups into these subdirectories via filters in inc/core/filters.php (for example, single_template_hierarchy => templates/single/{template}).

Examples of specific templates

- templates/page/page-contact.php (contact page template)
- templates/archive/archive.php (generic archive template)
- templates/single/single.php (generic single post template)

- The filters in inc/core/filters.php prepend the templates/ subdirectory to normal WordPress template names so WordPress resolves templates from these organized folders.