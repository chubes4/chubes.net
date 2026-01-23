# # Rewrite rules and documentation routing

- Location: chubes-docs plugin `inc/Core/RewriteRules.php`

High-level behavior

- The theme adds custom rewrite rules that expose top-level codebase archives at both root and under /docs/.
  - Examples:
    - /wordpress-plugins/  (index.php?codebase_archive=wordpress-plugins)
    - /docs/wordpress-plugins/  (index.php?docs_category_archive=wordpress-plugins)

- Documentation permalinks: documentation post permalinks are generated from the assigned codebase taxonomy hierarchy. The post_type_link filter in the chubes-docs plugin builds permalinks like:
  - /docs/{project}/{post-slug}/
  - Implementation returns home_url('/docs/' . $project->slug . '/' . $post->post_name . '/')

- Query vars and template handling: the rewrite rules set query vars like 'codebase' for taxonomy archives and 'post_type=documentation&name=...' for documentation posts. Templates are resolved via WordPress's standard template hierarchy.

- Top-level slugs: wordpress-plugins, wordpress-themes, discord-bots, php-libraries are used consistently by the rules and taxonomy code.