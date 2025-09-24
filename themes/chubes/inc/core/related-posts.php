<?php
/**
 * Related Posts System
 *
 * Comprehensive related posts functionality for all post types throughout the theme.
 * Features smart hierarchical algorithms for documentation posts using codebase
 * taxonomy relationships, with fallback systems for other post types.
 */

/**
 * Get related documentation posts using smart hierarchy traversal
 * 
 * Searches for related posts by traversing taxonomy hierarchy from most specific
 * to most general, stopping when enough posts are found. Prioritizes:
 * 1. Same taxonomy leaf + same parent post (most immediate relatives)
 * 2. Same taxonomy leaf + any parent post (siblings in category)  
 * 3. Same taxonomy parent + any post (broader category)
 * 4. Same taxonomy grandparent + any post (even broader)
 * 5. Same plugin/theme root + any post (all docs for plugin/theme)
 * 
 * @param int $post_id Current documentation post ID
 * @param int $limit Number of related posts to return (default: 3)
 * @return array Array of related post objects
 */
function chubes_get_related_documentation($post_id, $limit = 3) {
    $related_posts = [];
    $current_post = get_post($post_id);
    
    if (!$current_post || $current_post->post_type !== 'documentation') {
        return [];
    }
    
    // Get taxonomies from ANY taxonomy assigned to documentation
    $doc_taxonomies = get_object_taxonomies('documentation', 'names');
    $taxonomy_terms = null;
    $taxonomy_name = null;
    
    // Try each taxonomy until we find one with assigned terms
    foreach ($doc_taxonomies as $taxonomy) {
        $terms = get_the_terms($post_id, $taxonomy);
        if ($terms && !is_wp_error($terms)) {
            $taxonomy_terms = $terms;
            $taxonomy_name = $taxonomy;
            break; // Stop after finding first taxonomy with terms
        }
    }
    
    if (!$taxonomy_terms || !$taxonomy_name) {
        return chubes_get_fallback_related_documentation($post_id, $limit);
    }
    
    // Build hierarchy levels from most specific to root
    $hierarchy_levels = chubes_build_documentation_hierarchy_levels($taxonomy_terms, $taxonomy_name);
    
    // Search each hierarchy level until we have enough posts
    foreach ($hierarchy_levels as $level_data) {
        if (count($related_posts) >= $limit) {
            break;
        }
        
        $needed = $limit - count($related_posts);
        $level_posts = chubes_get_posts_by_taxonomy_level(
            $level_data['terms'], 
            $level_data['taxonomy'], 
            $post_id, 
            $current_post->post_parent,
            $needed,
            $level_data['same_parent_only']
        );
        
        $related_posts = array_merge($related_posts, $level_posts);
    }
    
    return array_slice($related_posts, 0, $limit);
}

/**
 * Build hierarchy levels for documentation taxonomy traversal
 * 
 * Creates search levels from most specific to most general for finding
 * related documentation posts within the taxonomy hierarchy.
 * 
 * @param array $terms Current post's taxonomy terms
 * @param string $taxonomy_name Taxonomy name (codebase)
 * @return array Array of hierarchy levels for searching
 */
function chubes_build_documentation_hierarchy_levels($terms, $taxonomy_name) {
    $levels = [];
    
    // Get the most specific term (deepest in hierarchy)
    $deepest_term = null;
    $max_depth = -1;
    
    foreach ($terms as $term) {
        $depth = chubes_get_taxonomy_term_depth($term, $taxonomy_name);
        if ($depth > $max_depth) {
            $max_depth = $depth;
            $deepest_term = $term;
        }
    }
    
    if (!$deepest_term) {
        $deepest_term = $terms[0]; // Fallback to first term
    }
    
    // Level 1: Same leaf category + same parent post (most specific)
    $levels[] = [
        'terms' => [$deepest_term],
        'taxonomy' => $taxonomy_name,
        'same_parent_only' => true
    ];
    
    // Level 2: Same leaf category + any parent post
    $levels[] = [
        'terms' => [$deepest_term],
        'taxonomy' => $taxonomy_name,
        'same_parent_only' => false
    ];
    
    // Level 3+: Walk up the hierarchy
    $current_term = $deepest_term;
    while ($current_term->parent != 0) {
        $parent_term = get_term($current_term->parent, $taxonomy_name);
        if ($parent_term && !is_wp_error($parent_term)) {
            $levels[] = [
                'terms' => [$parent_term],
                'taxonomy' => $taxonomy_name,
                'same_parent_only' => false
            ];
            $current_term = $parent_term;
        } else {
            break;
        }
    }
    
    return $levels;
}

/**
 * Get posts by taxonomy level with hierarchy-aware filtering
 * 
 * Retrieves related posts for a specific taxonomy level in the hierarchy,
 * with options for parent post filtering and exclusion of current post.
 * 
 * @param array $terms Taxonomy terms to search within
 * @param string $taxonomy Taxonomy name
 * @param int $exclude_post_id Post ID to exclude from results
 * @param int $current_parent_id Current post's parent ID (for same-parent filtering)
 * @param int $limit Number of posts to retrieve
 * @param bool $same_parent_only Whether to only include posts with same parent
 * @return array Array of related post objects
 */
function chubes_get_posts_by_taxonomy_level($terms, $taxonomy, $exclude_post_id, $current_parent_id, $limit, $same_parent_only = false) {
    $term_ids = array_map(function($term) {
        return $term->term_id;
    }, $terms);
    
    $args = [
        'post_type' => 'documentation',
        'posts_per_page' => $limit * 2, // Get extra in case of filtering
        'post_status' => 'publish',
        'post__not_in' => [$exclude_post_id],
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'tax_query' => [
            [
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => $term_ids,
                'operator' => 'IN'
            ]
        ]
    ];
    
    // Add parent post filtering if requested
    if ($same_parent_only && $current_parent_id) {
        $args['post_parent'] = $current_parent_id;
    }
    
    $posts = get_posts($args);
    
    // If we need more posts and same_parent_only didn't yield enough, try siblings
    if (count($posts) < $limit && $same_parent_only && $current_parent_id) {
        $args['post_parent'] = $current_parent_id;
        $args['posts_per_page'] = $limit;
        $sibling_posts = get_posts($args);
        $posts = array_merge($posts, $sibling_posts);
    }
    
    return array_slice($posts, 0, $limit);
}

/**
 * Get taxonomy term depth in hierarchy
 * 
 * Calculates how deep a taxonomy term is in its hierarchy by counting
 * parent relationships up to the root level.
 * 
 * @param WP_Term $term Taxonomy term object
 * @param string $taxonomy Taxonomy name
 * @return int Depth level (0 = root, 1 = child, etc.)
 */
function chubes_get_taxonomy_term_depth($term, $taxonomy) {
    $depth = 0;
    $current_term = $term;
    
    while ($current_term->parent != 0) {
        $depth++;
        $parent_term = get_term($current_term->parent, $taxonomy);
        if ($parent_term && !is_wp_error($parent_term)) {
            $current_term = $parent_term;
        } else {
            break;
        }
        
        // Prevent infinite loops
        if ($depth > 10) {
            break;
        }
    }
    
    return $depth;
}

/**
 * Get fallback related documentation when no taxonomy is assigned
 * 
 * Provides fallback related posts for documentation that doesn't have
 * codebase taxonomy assignments, using recent posts instead.
 * 
 * @param int $post_id Current post ID to exclude
 * @param int $limit Number of posts to return
 * @return array Array of recent documentation posts
 */
function chubes_get_fallback_related_documentation($post_id, $limit = 3) {
    return get_posts([
        'post_type' => 'documentation',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'post__not_in' => [$post_id],
        'orderby' => 'date',
        'order' => 'DESC'
    ]);
}

/**
 * Get documentation archive URL for current post's taxonomy
 * 
 * Returns the appropriate archive URL based on the current documentation
 * post's codebase taxonomy assignment.
 * 
 * @param int $post_id Documentation post ID
 * @return array Archive link data with 'url', 'title', and 'taxonomy'
 */
function chubes_get_documentation_archive_link($post_id) {
    // Use the EXACT SAME PATTERN as homepage function (functions.php:147-206)
    // Get parent categories (plugins, themes, apps, tools)
    $parent_categories = get_terms(array(
        'taxonomy'   => 'codebase',
        'hide_empty' => false,
        'parent'     => 0, // Top-level categories
        'orderby'    => 'name',
        'order'      => 'ASC'
    ));

    if ($parent_categories && !is_wp_error($parent_categories)) {
        foreach ($parent_categories as $parent_category) {
            // Get child projects under each parent category
            $child_projects = get_terms(array(
                'taxonomy'   => 'codebase',
                'hide_empty' => false,
                'parent'     => $parent_category->term_id,
                'orderby'    => 'name',
                'order'      => 'ASC'
            ));

            if ($child_projects && !is_wp_error($child_projects)) {
                foreach ($child_projects as $project) {
                    // Check if current post belongs to this project
                    $post_in_project = new WP_Query(array(
                        'post_type' => 'documentation',
                        'p' => $post_id,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'codebase',
                                'field'    => 'term_id',
                                'terms'    => $project->term_id,
                                'include_children' => true,
                            ),
                        ),
                        'posts_per_page' => 1,
                        'post_status' => 'publish'
                    ));

                    if ($post_in_project->found_posts > 0) {
                        // Found the project! Build URL using homepage pattern
                        return [
                            'url' => home_url('/docs/' . strtolower($parent_category->slug) . '/' . $project->slug . '/'),
                            'title' => $project->name . ' Documentation',
                            'taxonomy' => 'codebase'
                        ];
                    }
                    wp_reset_postdata();
                }
            }
        }
    }

    // Fallback to documentation archive
    return [
        'url' => home_url('/docs/'),
        'title' => 'Documentation',
        'taxonomy' => 'codebase'
    ];
}

/**
 * Get related posts for other post types (future expansion)
 * 
 * Placeholder function for implementing related posts for journal,
 * game, and other post types in the future.
 * 
 * @param int $post_id Current post ID
 * @param string $post_type Post type
 * @param int $limit Number of related posts to return
 * @return array Array of related posts (currently returns empty array)
 */
function chubes_get_related_posts_by_type($post_id, $post_type, $limit = 3) {
    // Future implementation for other post types
    // Can use codebase taxonomy, categories, tags, etc.
    
    switch ($post_type) {
        case 'documentation':
            return chubes_get_related_documentation($post_id, $limit);
            
        case 'journal':  
        case 'game':
        default:
            // Placeholder for future implementation
            return [];
    }
}