<?php
/**
 * Custom Rewrite Rules for Documentation Post Type
 * 
 * Handles hierarchical URLs based on plugin and theme taxonomy structures:
 * - /docs/{plugin}/post-slug/
 * - /docs/{plugin}/{category}/post-slug/
 * - /docs/{plugin}/{category}/{subcategory}/post-slug/
 * - /docs/{plugin}/{category}/{subcategory}/{subsubcategory}/post-slug/
 * - /docs/{theme}/post-slug/
 * - /docs/{theme}/{category}/post-slug/
 * - /docs/{theme}/{category}/{subcategory}/post-slug/
 * - /docs/{theme}/{category}/{subcategory}/{subsubcategory}/post-slug/
 */

/**
 * Add custom rewrite rules for documentation posts with hierarchical codebase taxonomy URLs
 */
function chubes_documentation_rewrite_rules() {
    // Codebase taxonomy rewrites for clean URLs
    // /plugins/ -> archive for plugins category in codebase taxonomy
    add_rewrite_rule(
        '^plugins/?$',
        'index.php?codebase_archive=plugins',
        'top'
    );
    
    // /themes/ -> archive for themes category in codebase taxonomy
    add_rewrite_rule(
        '^themes/?$',
        'index.php?codebase_archive=themes',
        'top'
    );
    
    // /apps/ -> archive for apps category in codebase taxonomy (future)
    add_rewrite_rule(
        '^apps/?$',
        'index.php?codebase_archive=apps',
        'top'
    );
    
    // /tools/ -> archive for tools category in codebase taxonomy (future)
    add_rewrite_rule(
        '^tools/?$',
        'index.php?codebase_archive=tools',
        'top'
    );
    
    // Individual codebase project pages
    // /plugins/project-name/ -> taxonomy-codebase.php for plugins/project-name
    add_rewrite_rule(
        '^plugins/([^/]+)/?$',
        'index.php?codebase_project=plugins/$matches[1]',
        'top'
    );
    
    // /themes/project-name/ -> taxonomy-codebase.php for themes/project-name
    add_rewrite_rule(
        '^themes/([^/]+)/?$',
        'index.php?codebase_project=themes/$matches[1]',
        'top'
    );
    
    // /apps/project-name/ -> taxonomy-codebase.php for apps/project-name (future)
    add_rewrite_rule(
        '^apps/([^/]+)/?$',
        'index.php?codebase_project=apps/$matches[1]',
        'top'
    );
    
    // /tools/project-name/ -> taxonomy-codebase.php for tools/project-name (future)
    add_rewrite_rule(
        '^tools/([^/]+)/?$',
        'index.php?codebase_project=tools/$matches[1]',
        'top'
    );
    
    // CRITICAL: Hierarchical documentation archives MUST come BEFORE post rules
    
    // /docs/plugins/ -> archive for all plugin documentation
    add_rewrite_rule(
        '^docs/plugins/?$',
        'index.php?docs_category_archive=plugins',
        'top'
    );
    
    // /docs/themes/ -> archive for all theme documentation
    add_rewrite_rule(
        '^docs/themes/?$',
        'index.php?docs_category_archive=themes',
        'top'
    );
    
    // /docs/apps/ -> archive for all app documentation (future)
    add_rewrite_rule(
        '^docs/apps/?$',
        'index.php?docs_category_archive=apps',
        'top'
    );
    
    // /docs/tools/ -> archive for all tool documentation (future)
    add_rewrite_rule(
        '^docs/tools/?$',
        'index.php?docs_category_archive=tools',
        'top'
    );
    
    // /docs/plugins/project-name/ -> archive for specific plugin's documentation
    add_rewrite_rule(
        '^docs/plugins/([^/]+)/?$',
        'index.php?docs_project_archive=plugins/$matches[1]',
        'top'
    );
    
    // /docs/themes/project-name/ -> archive for specific theme's documentation
    add_rewrite_rule(
        '^docs/themes/([^/]+)/?$',
        'index.php?docs_project_archive=themes/$matches[1]',
        'top'
    );
    
    // /docs/apps/project-name/ -> archive for specific app's documentation (future)
    add_rewrite_rule(
        '^docs/apps/([^/]+)/?$',
        'index.php?docs_project_archive=apps/$matches[1]',
        'top'
    );
    
    // /docs/tools/project-name/ -> archive for specific tool's documentation (future)
    add_rewrite_rule(
        '^docs/tools/([^/]+)/?$',
        'index.php?docs_project_archive=tools/$matches[1]',
        'top'
    );
    
    // 6-level hierarchy: /docs/category/project/cat1/cat2/cat3/post-slug/
    add_rewrite_rule(
        '^docs/(plugins|themes|apps|tools)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/?$',
        'index.php?post_type=documentation&name=$matches[6]&plugin_path=$matches[1]/$matches[2]/$matches[3]/$matches[4]/$matches[5]',
        'top'
    );
    
    // 5-level hierarchy: /docs/category/project/cat1/cat2/post-slug/
    add_rewrite_rule(
        '^docs/(plugins|themes|apps|tools)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/?$',
        'index.php?post_type=documentation&name=$matches[5]&plugin_path=$matches[1]/$matches[2]/$matches[3]/$matches[4]',
        'top'
    );
    
    // 3-level hierarchy: /docs/category/project/post-slug/
    add_rewrite_rule(
        '^docs/(plugins|themes|apps|tools)/([^/]+)/([^/]+)/?$',
        'index.php?post_type=documentation&name=$matches[3]&plugin_path=$matches[1]/$matches[2]',
        'top'
    );
    
    // 4-level hierarchy: /docs/category/project/subcategory/post-slug/
    add_rewrite_rule(
        '^docs/(plugins|themes|apps|tools)/([^/]+)/([^/]+)/([^/]+)/?$',
        'index.php?post_type=documentation&name=$matches[4]&plugin_path=$matches[1]/$matches[2]/$matches[3]',
        'top'
    );
}
add_action('init', 'chubes_documentation_rewrite_rules');

/**
 * Generate custom permalinks for documentation posts based on any assigned taxonomy hierarchy
 *
 * @param string $permalink The existing permalink URL
 * @param WP_Post $post The post object
 * @return string The custom permalink
 */
function chubes_documentation_permalink($permalink, $post) {
    if ($post->post_type !== 'documentation') {
        return $permalink;
    }
    
    // Get all taxonomies registered to documentation post type
    $doc_taxonomies = get_object_taxonomies('documentation', 'names');
    
    // Try each taxonomy until we find one with terms assigned
    foreach ($doc_taxonomies as $taxonomy) {
        $terms = get_the_terms($post->ID, $taxonomy);
        if ($terms && !is_wp_error($terms)) {
            // Build full hierarchy by following parent relationships to root
            $full_hierarchy_terms = [];
            foreach ($terms as $term) {
                // Add the assigned term
                $full_hierarchy_terms[] = $term;
                
                // Follow parent chain to get full hierarchy
                $parent_id = $term->parent;
                while ($parent_id != 0) {
                    $parent_term = get_term($parent_id, $taxonomy);
                    if ($parent_term && !is_wp_error($parent_term)) {
                        array_unshift($full_hierarchy_terms, $parent_term);
                        $parent_id = $parent_term->parent;
                    } else {
                        break;
                    }
                }
            }
            
            $hierarchy_path = chubes_build_term_hierarchy($full_hierarchy_terms);
            if ($hierarchy_path) {
                return home_url('/docs/' . $hierarchy_path . '/' . $post->post_name . '/');
            }
        }
    }
    
    return $permalink;
}
add_filter('post_type_link', 'chubes_documentation_permalink', 10, 2);

/**
 * Build hierarchical URL path from plugin taxonomy terms
 *
 * @param array $terms Array of taxonomy term objects
 * @return string Hierarchical path like 'parent/child/grandchild'
 */
function chubes_build_term_hierarchy($terms) {
    if (empty($terms)) {
        return '';
    }
    
    // Build hierarchy array with proper parent-child relationships
    $hierarchy = [];
    $term_map = [];
    
    // Create term map for easy lookup
    foreach ($terms as $term) {
        $term_map[$term->term_id] = $term;
    }
    
    // Find the root term (no parent or parent not in our term list)
    $root_term = null;
    foreach ($terms as $term) {
        if ($term->parent == 0 || !isset($term_map[$term->parent])) {
            $root_term = $term;
            break;
        }
    }
    
    if (!$root_term) {
        // Fallback: use first term if no clear hierarchy
        $root_term = reset($terms);
    }
    
    // Build hierarchy starting from root
    $current_term = $root_term;
    $hierarchy[] = $current_term->slug;
    
    // Follow the hierarchy chain
    while (true) {
        $child_found = false;
        foreach ($terms as $term) {
            if ($term->parent == $current_term->term_id) {
                $hierarchy[] = $term->slug;
                $current_term = $term;
                $child_found = true;
                break;
            }
        }
        if (!$child_found) {
            break;
        }
    }
    
    return implode('/', $hierarchy);
}

/**
 * Handle query vars for custom plugin and theme path parameters
 *
 * @param array $vars Existing query vars
 * @return array Modified query vars
 */
function chubes_documentation_query_vars($vars) {
    $vars[] = 'plugin_path';
    $vars[] = 'theme_path';
    // New hierarchical documentation query vars
    $vars[] = 'docs_category_archive';
    $vars[] = 'docs_project_archive';
    // Codebase taxonomy query vars
    $vars[] = 'codebase_archive';
    $vars[] = 'codebase_project';
    return $vars;
}
add_filter('query_vars', 'chubes_documentation_query_vars');

/**
 * Handle template include for custom archives using WordPress hierarchy
 */
function chubes_custom_template_include($template) {
    // Handle codebase archive requests (e.g., /plugins/, /themes/, /apps/, /tools/)
    if ($archive_type = get_query_var('codebase_archive')) {
        // Find the parent term for this archive type
        $parent_term = get_term_by('slug', $archive_type, 'codebase');
        if ($parent_term && !is_wp_error($parent_term)) {
            // Set WordPress globals for template access
            global $wp_query;
            $wp_query->queried_object = $parent_term;
            $wp_query->queried_object_id = $parent_term->term_id;
            $wp_query->is_tax = true;
            $wp_query->is_archive = true;
            
            // Set custom global for template to identify archive type
            global $chubes_codebase_archive_type;
            $chubes_codebase_archive_type = $archive_type;
            
            return locate_template('templates/archive/archive-codebase.php');
        }
        
        // Fallback for missing parent term - still show archive
        global $chubes_codebase_archive_type;
        $chubes_codebase_archive_type = $archive_type;
        return locate_template('templates/archive/archive-codebase.php');
    }
    
    // Handle individual codebase project requests (e.g., /plugins/project-name/)
    if ($project_path = get_query_var('codebase_project')) {
        // Parse the path (e.g., "plugins/project-name")
        $path_parts = explode('/', $project_path);
        if (count($path_parts) >= 2) {
            $category = $path_parts[0]; // plugins, themes, etc.
            $project_slug = $path_parts[1]; // project-name
            
            // Find the parent term (plugins, themes, etc.)
            $parent_term = get_term_by('slug', $category, 'codebase');
            if ($parent_term) {
                // Find the project term under this parent
                $project_terms = get_terms([
                    'taxonomy' => 'codebase',
                    'parent' => $parent_term->term_id,
                    'slug' => $project_slug,
                    'hide_empty' => false
                ]);
                
                if (!empty($project_terms)) {
                    $project_term = $project_terms[0];
                    
                    // Set WordPress globals for template access
                    global $wp_query;
                    $wp_query->queried_object = $project_term;
                    $wp_query->queried_object_id = $project_term->term_id;
                    $wp_query->is_tax = true;
                    $wp_query->is_archive = true;
                    
                    return locate_template('templates/taxonomy/taxonomy-codebase.php');
                }
            }
        }
        
        // If project not found, 404
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        return locate_template('templates/404.php');
    }
    
    // Handle documentation category archives (e.g., /docs/plugins/, /docs/themes/)
    if ($category = get_query_var('docs_category_archive')) {
        // Find the parent term for this category type
        $parent_term = get_term_by('slug', $category, 'codebase');
        if ($parent_term && !is_wp_error($parent_term)) {
            // Set WordPress globals for template access
            global $wp_query;
            $wp_query->queried_object = $parent_term;
            $wp_query->queried_object_id = $parent_term->term_id;
            $wp_query->is_tax = true;
            $wp_query->is_archive = true;
            
            // Set custom global for template to identify docs category
            global $chubes_docs_category_type;
            $chubes_docs_category_type = $category;
            
            return locate_template('templates/archive/archive-docs-category.php');
        }
        
        // Fallback for missing parent term - still show archive
        global $chubes_docs_category_type;
        $chubes_docs_category_type = $category;
        return locate_template('templates/archive/archive-docs-category.php');
    }
    
    // Handle documentation project archives (e.g., /docs/plugins/data-machine/)
    if ($project_path = get_query_var('docs_project_archive')) {
        // Parse the path (e.g., "plugins/data-machine")
        $path_parts = explode('/', $project_path);
        if (count($path_parts) >= 2) {
            $category = $path_parts[0]; // plugins, themes, etc.
            $project_slug = $path_parts[1]; // data-machine
            
            // Find the parent term (plugins, themes, etc.)
            $parent_term = get_term_by('slug', $category, 'codebase');
            if ($parent_term) {
                // Find the project term under this parent
                $project_terms = get_terms([
                    'taxonomy' => 'codebase',
                    'parent' => $parent_term->term_id,
                    'slug' => $project_slug,
                    'hide_empty' => false
                ]);
                
                if (!empty($project_terms)) {
                    $project_term = $project_terms[0];
                    
                    // Set WordPress globals for template access
                    global $wp_query;
                    $wp_query->queried_object = $project_term;
                    $wp_query->queried_object_id = $project_term->term_id;
                    $wp_query->is_tax = true;
                    $wp_query->is_archive = true;
                    
                    // Set custom globals for template to identify project docs
                    global $chubes_docs_taxonomy, $chubes_docs_term;
                    $chubes_docs_taxonomy = 'codebase';
                    $chubes_docs_term = $project_term;
                    
                    return locate_template('templates/archive/archive-docs-taxonomy.php');
                }
            }
        }
        
        // If project not found, 404
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        return locate_template('templates/404.php');
    }
    
    return $template;
}
add_filter('template_include', 'chubes_custom_template_include');