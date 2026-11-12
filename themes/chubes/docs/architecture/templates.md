# # Template hierarchy and lookup

- Template organization: all theme templates live under the inc/core/templates/ directory
- Lookup routing: the theme maps WordPress template hierarchy lookups to inc/core/templates/ via filters in inc/core/filters.php

Examples of specific templates

- inc/core/templates/archive.php (generic archive template)
- inc/core/templates/single.php (generic single post template)
- inc/core/templates/page.php (generic page template)
- inc/core/templates/front-page.php (homepage template)
- inc/core/templates/404.php (404 error template)
- inc/core/templates/search.php (search results template)

- The filters in inc/core/filters.php prepend 'inc/core/templates/' to normal WordPress template names
 - `chubes_taxonomy_template_hierarchy()` filter ensures project taxonomy archives properly route to inc/core/templates/