<?php
/**
 * Codebase Taxonomy Registration
 *
 * Unified hierarchical taxonomy for organizing all code-based projects including
 * wordpress-plugins, wordpress-themes, discord-bots, php-libraries, and other software projects.
 * Consolidates the previous separate plugin and theme taxonomies into a single maintainable system.
 *
 * Structure: codebase/wordpress-plugins/, codebase/wordpress-themes/, etc. with clean URL rewrites
 * Features custom fields with GitHub and WordPress.org URLs, plus install tracking.
 * Public archives available at /wordpress-plugins, /wordpress-themes, /discord-bots, /php-libraries with API integration.
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
 * Get the list of top-level codebase slugs used throughout the theme.
 *
 * @return array
 */
function chubes_get_codebase_top_level_slugs() {
    return ['wordpress-plugins', 'wordpress-themes', 'discord-bots', 'php-libraries'];
}

/**
 * Check whether a term is one of the known top-level codebase categories.
 *
 * @param WP_Term $term
 * @return bool
 */
function chubes_is_codebase_top_level_term($term) {
    if (!$term || !($term instanceof WP_Term) || $term->taxonomy !== 'codebase') {
        return false;
    }

    return in_array($term->slug, chubes_get_codebase_top_level_slugs(), true);
}

/**
 * Walk up the hierarchy to find the top-level codebase term for a given term.
 *
 * @param WP_Term $term
 * @return WP_Term|null
 */
function chubes_get_codebase_top_level_term($term) {
    if (!$term || !($term instanceof WP_Term) || $term->taxonomy !== 'codebase') {
        return null;
    }

    $current = $term;

    while ($current->parent !== 0) {
        $parent = get_term($current->parent, 'codebase');
        if ($parent && !is_wp_error($parent)) {
            $current = $parent;
        } else {
            break;
        }
    }

    return $current;
}

/**
 * Return the deepest term (primary term) from a list of codebase terms.
 *
 * @param array $terms
 * @return WP_Term|null
 */
function chubes_get_codebase_primary_term($terms) {
    if (empty($terms) || is_wp_error($terms)) {
        return null;
    }

    $sorted_terms = chubes_sort_terms_by_hierarchy($terms);
    return $sorted_terms[0] ?? null;
}

/**
 * Given any codebase term, return the project-level term (child of a top-level category).
 *
 * @param WP_Term $term
 * @return WP_Term|null
 */
function chubes_get_codebase_project_term_from_term($term) {
    if (!$term || !($term instanceof WP_Term) || $term->taxonomy !== 'codebase') {
        return null;
    }

    $current = $term;

    while ($current->parent !== 0) {
        $parent = get_term($current->parent, 'codebase');
        if (!$parent || is_wp_error($parent)) {
            return $current;
        }

        if (chubes_is_codebase_top_level_term($parent)) {
            return $current;
        }

        $current = $parent;
    }

    return $current;
}

/**
 * Resolve the project-level term using the current post's assigned codebase terms.
 *
 * @param array $terms
 * @return WP_Term|null
 */
function chubes_get_codebase_project_term_from_terms($terms) {
    $primary_term = chubes_get_codebase_primary_term($terms);
    if (!$primary_term) {
        return null;
    }

    return chubes_get_codebase_project_term_from_term($primary_term);
}

/**
 * Resolve the top-level term for a set of assigned codebase terms.
 *
 * @param array $terms
 * @return WP_Term|null
 */
function chubes_get_codebase_top_level_term_from_terms($terms) {
    $primary_term = chubes_get_codebase_primary_term($terms);
    if (!$primary_term) {
        return null;
    }

    return chubes_get_codebase_top_level_term($primary_term);
}

/**
 * Get comprehensive repository information for codebase projects
 *
 * Centralized function that retrieves repository-related metadata for a codebase taxonomy term including
 * install counts, external URLs (GitHub, WordPress.org), and content counts across all post types.
 * Automatically detects project type (wordpress-plugin/wordpress-theme/discord-bot/php-library) based on
 * hierarchy and calls appropriate functions. This eliminates duplicate queries and provides a consistent
 * data structure across all templates.
 *
 * @param WP_Term $term The codebase taxonomy term object
 * @return array Repository information array with install counts, URLs, and content statistics
 */
function chubes_get_repository_info($term) {
    if (!$term || $term->taxonomy !== 'codebase') {
        return [];
    }
    
    $project_type = chubes_get_codebase_project_type($term);

    // Get install count and external URLs
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
 * Determine the project type for a codebase term
 *
 * Traverses the taxonomy hierarchy to find the top-level parent term
 * which indicates the project type (wordpress-plugins, wordpress-themes, discord-bots, php-libraries).
 *
 * @param WP_Term $term The codebase taxonomy term
 * @return string The project type (wordpress-plugin, wordpress-theme, discord-bot, php-library) or empty string if not found
 */
function chubes_get_codebase_project_type($term) {
    if (!$term || $term->taxonomy !== 'codebase') {
        return '';
    }

    $category_mapping = [
        'wordpress-plugins' => 'wordpress-plugin',
        'wordpress-themes' => 'wordpress-theme',
        'discord-bots' => 'discord-bot',
        'php-libraries' => 'php-library'
    ];

    $top_level_term = chubes_get_codebase_top_level_term($term);

    if ($top_level_term && isset($category_mapping[$top_level_term->slug])) {
        return $category_mapping[$top_level_term->slug];
    }

    return '';
}

/**
 * Customize Documentation admin columns to show hierarchy
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
 * Shows the full taxonomy hierarchy path (e.g., "wordpress-plugins → data-machine → filters")
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
                echo '—';
            }
        } else {
            echo '—';
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
 * @return string Formatted hierarchy path (e.g., "wordpress-plugins → data-machine → filters")
 */
function chubes_build_term_hierarchy_path($terms) {
    if (empty($terms)) {
        return '';
    }

    $sorted_terms = chubes_sort_terms_by_hierarchy($terms);
    $primary_term = $sorted_terms[0];
    $hierarchy = [];
    $current_term = $primary_term;

    while ($current_term) {
        array_unshift($hierarchy, $current_term->name);

        if ($current_term->parent == 0) {
            break;
        }

        $parent_term = get_term($current_term->parent, 'codebase');
        if ($parent_term && !is_wp_error($parent_term)) {
            $current_term = $parent_term;
        } else {
            break;
        }

        if (count($hierarchy) > 10) {
            break;
        }
    }

    return implode(' → ', $hierarchy);
}

/**
 * Sort codebase terms by hierarchy depth
 *
 * @param array $terms Array of WP_Term objects
 * @return array Terms sorted by hierarchy depth (deepest first)
 */
function chubes_sort_terms_by_hierarchy($terms) {
    $terms_with_depth = [];

    foreach ($terms as $term) {
        $depth = chubes_calculate_term_depth($term);
        $terms_with_depth[] = [
            'term' => $term,
            'depth' => $depth
        ];
    }

    usort($terms_with_depth, function($a, $b) {
        return $b['depth'] - $a['depth'];
    });

    return array_map(function($item) {
        return $item['term'];
    }, $terms_with_depth);
}

/**
 * Calculate the depth of a taxonomy term in the hierarchy
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
            break;
        }

        if ($depth > 10) {
            break;
        }
    }

    return $depth;
}