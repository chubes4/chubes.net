<?php
/**
 * Codebase Taxonomy Registration
 * 
 * Unified hierarchical taxonomy for organizing all code-based projects including
 * plugins, themes, apps, tools, and other software projects. Consolidates the
 * previous separate plugin and theme taxonomies into a single maintainable system.
 * 
 * Structure: codebase/plugins/, codebase/themes/, etc. with clean URL rewrites
 * Features custom fields with GitHub and WordPress.org URLs, plus install tracking.
 * Public archives available at /plugins, /themes, etc. with API integration.
 */
function chubes_register_codebase_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Codebase Projects', 'Taxonomy General Name', 'chubes' ),
        'singular_name'              => _x( 'Codebase Project', 'Taxonomy Singular Name', 'chubes' ),
        'menu_name'                  => __( 'Codebase', 'chubes' ),
        'all_items'                  => __( 'All Projects', 'chubes' ),
        'parent_item'                => __( 'Parent Project', 'chubes' ),
        'parent_item_colon'          => __( 'Parent Project:', 'chubes' ),
        'new_item_name'              => __( 'New Project Name', 'chubes' ),
        'add_new_item'               => __( 'Add New Project', 'chubes' ),
        'edit_item'                  => __( 'Edit Project', 'chubes' ),
        'update_item'                => __( 'Update Project', 'chubes' ),
        'view_item'                  => __( 'View Project', 'chubes' ),
        'separate_items_with_commas' => __( 'Separate projects with commas', 'chubes' ),
        'add_or_remove_items'        => __( 'Add or remove projects', 'chubes' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'chubes' ),
        'popular_items'              => __( 'Popular Projects', 'chubes' ),
        'search_items'               => __( 'Search Projects', 'chubes' ),
        'not_found'                  => __( 'Not Found', 'chubes' ),
        'no_terms'                   => __( 'No projects', 'chubes' ),
        'items_list'                 => __( 'Projects list', 'chubes' ),
        'items_list_navigation'      => __( 'Projects list navigation', 'chubes' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rewrite'                    => array( 'slug' => 'codebase', 'hierarchical' => true ),
    );
    register_taxonomy( 'codebase', array( 'documentation', 'journal', 'game' ), $args );
}
add_action( 'init', 'chubes_register_codebase_taxonomy', 0 );

/**
 * Get comprehensive repository information for codebase projects
 * 
 * Centralized function that retrieves repository-related metadata for a codebase taxonomy term including
 * install counts, external URLs (GitHub, WordPress.org), and content counts across all post types.
 * Automatically detects project type (plugin/theme/app) based on hierarchy and calls appropriate functions.
 * This eliminates duplicate queries and provides a consistent data structure across all templates.
 * 
 * @param WP_Term $term The codebase taxonomy term object
 * @return array Repository information array with install counts, URLs, and content statistics
 */
function chubes_get_repository_info($term) {
    if (!$term || $term->taxonomy !== 'codebase') {
        return [];
    }
    
    // Determine project type based on hierarchy
    $project_type = chubes_get_codebase_project_type($term);
    
    // Get install count and external URLs using unified functions
    $installs = function_exists('chubes_get_codebase_installs') ? chubes_get_codebase_installs($term->term_id) : 0;
    $wp_url = function_exists('chubes_get_codebase_wp_url') ? chubes_get_codebase_wp_url($term->term_id) : '';
    $github_url = function_exists('chubes_get_codebase_github_url') ? chubes_get_codebase_github_url($term->term_id) : '';
    
    // Get content counts for all supported post types
    $post_types = ['documentation', 'journal', 'game'];
    $content_counts = [];
    $total_content = 0;
    $has_content = false;
    
    foreach ($post_types as $post_type) {
        $query = new WP_Query([
            'post_type' => $post_type,
            'tax_query' => [
                [
                    'taxonomy' => 'codebase',
                    'field' => 'term_id',
                    'terms' => $term->term_id,
                ],
            ],
            'posts_per_page' => 1,
            'post_status' => 'publish'
        ]);
        
        $count = $query->found_posts;
        $content_counts[$post_type] = $count;
        $total_content += $count;
        
        if ($count > 0) {
            $has_content = true;
        }
        
        wp_reset_postdata();
    }
    
    return [
        'project_type' => $project_type,
        'installs' => $installs,
        'wp_url' => $wp_url,
        'github_url' => $github_url,
        'content_counts' => $content_counts,
        'total_content' => $total_content,
        'has_content' => $has_content
    ];
}

/**
 * Determine the project type (plugin, theme, app, etc.) for a codebase term
 * 
 * Traverses the taxonomy hierarchy to find the top-level parent term
 * which indicates the project type (plugins, themes, apps, etc.)
 * 
 * @param WP_Term $term The codebase taxonomy term
 * @return string The project type (plugin, theme, app, etc.) or empty string if not found
 */
function chubes_get_codebase_project_type($term) {
    if (!$term || $term->taxonomy !== 'codebase') {
        return '';
    }
    
    // If this term has no parent, it might be a top-level category itself
    if ($term->parent == 0) {
        // Check if this is a known category term (plugins, themes, etc.)
        $category_slugs = ['plugins', 'themes', 'apps', 'tools'];
        if (in_array($term->slug, $category_slugs)) {
            return rtrim($term->slug, 's'); // Remove the 's' (plugins -> plugin)
        }
        return '';
    }
    
    // Traverse up the hierarchy to find the top-level parent
    $current_term = $term;
    while ($current_term->parent != 0) {
        $parent_term = get_term($current_term->parent, 'codebase');
        if ($parent_term && !is_wp_error($parent_term)) {
            $current_term = $parent_term;
        } else {
            break;
        }
        
        // Prevent infinite loops
        if ($current_term->term_id === $term->term_id) {
            break;
        }
    }
    
    // The top-level parent should indicate the project type
    $category_mapping = [
        'plugins' => 'plugin',
        'themes' => 'theme',
        'apps' => 'app',
        'tools' => 'tool'
    ];
    
    return $category_mapping[$current_term->slug] ?? '';
}

/**
 * Customize Documentation admin columns to show hierarchy
 *
 * Override the default "Codebase Projects" column header to indicate
 * that we're showing the full hierarchy path instead of just the term name.
 *
 * @param array $columns Existing admin columns
 * @return array Modified columns array
 */
function chubes_customize_documentation_columns($columns) {
    if (isset($columns['taxonomy-codebase'])) {
        $columns['taxonomy-codebase'] = 'Project Hierarchy';
    }
    return $columns;
}
add_filter('manage_documentation_posts_columns', 'chubes_customize_documentation_columns');

/**
 * Display custom hierarchy content for Documentation admin column
 *
 * Shows the full taxonomy hierarchy path (e.g., "plugins → data-machine → filters")
 * instead of WordPress default behavior of showing only the most specific term.
 * Handles multiple taxonomy assignments by prioritizing the deepest hierarchy.
 *
 * @param string $column Column name being processed
 * @param int $post_id Current post ID
 */
function chubes_display_documentation_hierarchy_column($column, $post_id) {
    if ($column === 'taxonomy-codebase') {
        $terms = get_the_terms($post_id, 'codebase');

        if ($terms && !is_wp_error($terms)) {
            $hierarchy_path = chubes_build_term_hierarchy_path($terms);
            if ($hierarchy_path) {
                echo esc_html($hierarchy_path);
            } else {
                echo '—'; // Em dash for empty state
            }
        } else {
            echo '—'; // Em dash for no terms
        }
    }
}
add_action('manage_documentation_posts_custom_column', 'chubes_display_documentation_hierarchy_column', 10, 2);

/**
 * Build hierarchy path string from codebase taxonomy terms
 *
 * Creates a visual hierarchy path showing the complete taxonomy structure
 * from root category to most specific term, using arrow separators.
 * Handles multiple term assignments by selecting the most appropriate hierarchy.
 *
 * @param array $terms Array of WP_Term objects from codebase taxonomy
 * @return string Formatted hierarchy path (e.g., "plugins → data-machine → filters")
 */
function chubes_build_term_hierarchy_path($terms) {
    if (empty($terms)) {
        return '';
    }

    // Sort terms by hierarchy depth to prioritize deepest paths
    $sorted_terms = chubes_sort_terms_by_hierarchy($terms);

    // Use the deepest hierarchy (first after sorting)
    $primary_term = $sorted_terms[0];

    // Build the complete hierarchy path for this term
    $hierarchy = [];
    $current_term = $primary_term;

    // Traverse up the hierarchy to build the complete path
    while ($current_term) {
        array_unshift($hierarchy, $current_term->name);

        if ($current_term->parent == 0) {
            break; // Reached root level
        }

        $parent_term = get_term($current_term->parent, 'codebase');
        if ($parent_term && !is_wp_error($parent_term)) {
            $current_term = $parent_term;
        } else {
            break; // Invalid parent, stop traversal
        }

        // Prevent infinite loops
        if (count($hierarchy) > 10) {
            break;
        }
    }

    // Join hierarchy components with arrow separator
    return implode(' → ', $hierarchy);
}

/**
 * Sort codebase terms by hierarchy depth
 *
 * Orders terms with deepest hierarchy first to prioritize showing
 * the most specific project context in admin columns.
 *
 * @param array $terms Array of WP_Term objects
 * @return array Terms sorted by hierarchy depth (deepest first)
 */
function chubes_sort_terms_by_hierarchy($terms) {
    // Calculate depth for each term
    $terms_with_depth = [];

    foreach ($terms as $term) {
        $depth = chubes_calculate_term_depth($term);
        $terms_with_depth[] = [
            'term' => $term,
            'depth' => $depth
        ];
    }

    // Sort by depth (deepest first)
    usort($terms_with_depth, function($a, $b) {
        return $b['depth'] - $a['depth'];
    });

    // Extract just the terms
    return array_map(function($item) {
        return $item['term'];
    }, $terms_with_depth);
}

/**
 * Calculate the depth of a taxonomy term in the hierarchy
 *
 * Counts how many parent levels exist above this term to determine
 * its position in the taxonomy hierarchy.
 *
 * @param WP_Term $term The taxonomy term to measure
 * @return int Depth level (0 = root, 1 = first child, etc.)
 */
function chubes_calculate_term_depth($term) {
    $depth = 0;
    $current_term = $term;

    while ($current_term->parent != 0) {
        $depth++;
        $parent_term = get_term($current_term->parent, 'codebase');

        if ($parent_term && !is_wp_error($parent_term)) {
            $current_term = $parent_term;
        } else {
            break; // Invalid parent, stop counting
        }

        // Prevent infinite loops
        if ($depth > 10) {
            break;
        }
    }

    return $depth;
}