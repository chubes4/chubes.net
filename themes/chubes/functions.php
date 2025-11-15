<?php
/**
 * Chubes Theme Functions
 * 
 * Core theme functionality including asset loading, custom post types,
 * navigation system, and performance optimizations.
 */

/**
 * Theme Setup and Feature Registration
 *
 * Registers theme support for core WordPress features and sets up navigation menus.
 * Enables Gutenberg editor support, custom logo, featured images, and editor styles.
 */
function chubes_theme_setup() {
    // Add support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('menus');
    add_theme_support('editor-styles');
    add_editor_style('style.css');

    // Register navigation menu location
    register_nav_menus(array(
        'primary' => __('Primary Navigation', 'chubes-theme'),
    ));
}
add_action('after_setup_theme', 'chubes_theme_setup');

/**
 * Security: Remove WordPress version from meta generator
 * 
 * @return string Empty string to hide WordPress version
 */
function chubes_remove_wp_version() {
    return '';
}
add_filter('the_generator', 'chubes_remove_wp_version');

/**
 * Performance: Disable WordPress emoji scripts
 * Removes emoji detection script and styles to reduce HTTP requests
 */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

/**
 * Get parent page information for context-aware navigation
 * 
 * Provides intelligent back navigation based on content type:
 * - Blog posts → Blog page
 * - Custom post types → Archive page  
 * - Pages with parents → Immediate parent page
 * - Archive pages → Blog or homepage
 * 
 * @return array Parent page data with 'url' and 'title' keys
 */
function chubes_get_parent_page() {
    global $post;
    
    // Default is homepage
    $parent = array(
        'url' => home_url('/'),
        'title' => 'Chubes.net'
    );
    
    // For single post - always go back to Blog
    if (is_single() && get_post_type() === 'post') {
        $parent = array(
            'url' => get_permalink(get_option('page_for_posts')),
            'title' => 'Blog'
        );
    } 
    // For custom post types
    elseif (is_single() && get_post_type() !== 'post') {
        $post_type = get_post_type_object(get_post_type());
        $archive_link = get_post_type_archive_link(get_post_type());
        
        if ($archive_link) {
            $parent = array(
                'url' => $archive_link,
                'title' => $post_type->labels->name
            );
        }
    }
    // For pages with ancestors
    elseif (is_page()) {
        if ($post->post_parent) {
            // Get ancestors array (parent, grandparent, etc.)
            $ancestors = get_post_ancestors($post->ID);
            
            // The first item in the array is the immediate parent
            $parent_id = $ancestors[0];
            $parent = array(
                'url' => get_permalink($parent_id),
                'title' => get_the_title($parent_id)
            );
        }
    }
    // For category archives
    elseif (is_category() || is_tag() || is_date() || is_author()) {
        $parent = array(
            'url' => get_permalink(get_option('page_for_posts')),
            'title' => 'Blog'
        );
    }
    // For post type archives (like Journal or Game)
    elseif (is_post_type_archive()) {
        // These should go back to the homepage
        $parent = array(
            'url' => home_url('/'),
            'title' => 'Chubes.net'
        );
    }
    
    return $parent;
}

/**
 * Get documentation items for homepage display
 * 
 * Retrieves projects from codebase taxonomy that have documentation posts
 * and formats them for display on the homepage.
 * 
 * @return array Array of documentation items with name, type, count, and URL
 */
function chubes_get_homepage_documentation_items() {
    $doc_items = array();
    
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
                    // Check if this project has documentation
                    $docs_count = new WP_Query(array(
                        'post_type' => 'documentation',
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
                    
                    if ($docs_count->found_posts > 0) {
                        $doc_items[] = array(
                            'name' => $project->name,
                            'type' => rtrim($parent_category->name, 's'), // "Plugins" -> "Plugin"
                            'count' => $docs_count->found_posts,
                            'url' => home_url('/docs/' . strtolower($parent_category->slug) . '/' . $project->slug . '/')
                        );
                    }
                    wp_reset_postdata();
                }
            }
        }
        
        // Sort by project name for consistent display
        usort($doc_items, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        
        // Limit to 3 items to match other homepage sections
        $doc_items = array_slice($doc_items, 0, 3);
    }
    
    return $doc_items;
}

/**
 * Generate URL for viewing content of specific type for a codebase project
 * 
 * @param string $post_type The post type
 * @param WP_Term $term The project term
 * @return string The URL to view this content type for this project
 */
function chubes_generate_content_type_url($post_type, $term) {
    // For documentation, use docs archive with hierarchical project structure
    if ($post_type === 'documentation') {
        // Find the parent category (plugins, themes, apps, tools)
        $parent_category = null;
        if ($term->parent != 0) {
            // This is a project term, find its parent category
            $parent_term = get_term($term->parent, 'codebase');
            if ($parent_term && !is_wp_error($parent_term) && in_array($parent_term->slug, ['plugins', 'themes', 'apps', 'tools'])) {
                $parent_category = $parent_term;
            }
        } else if (in_array($term->slug, ['plugins', 'themes', 'apps', 'tools'])) {
            // This is already a parent category, not a project
            return home_url('/docs/' . $term->slug . '/');
        }
        
        if ($parent_category) {
            return home_url('/docs/' . $parent_category->slug . '/' . $term->slug . '/');
        }
    }
    
    // For other post types, use post type archive with taxonomy filter
    $archive_url = get_post_type_archive_link($post_type);
    if ($archive_url) {
        return add_query_arg('codebase', $term->slug, $archive_url);
    }
    
    // Fallback to project taxonomy page
    return get_term_link($term);
}

/**
 * Load theme functionality modules
 * 
 * Modular architecture with single responsibility principle:
 * - Core: Assets, CPTs, taxonomies, URL rewrite rules, related posts system, template filters
 * - Contact: REST API for form processing with spam protection
 * - Plugins: Codebase taxonomy fields with repository tracking and install counts
 */
require_once get_template_directory() . '/inc/core/assets.php';
require_once get_template_directory() . '/inc/core/custom-post-types.php';
require_once get_template_directory() . '/inc/core/custom-taxonomies.php';
require_once get_template_directory() . '/inc/core/rewrite-rules.php';
require_once get_template_directory() . '/inc/core/filters.php';
require_once get_template_directory() . '/inc/core/related-posts.php';
require_once get_template_directory() . '/inc/contact-rest-api.php';
require_once get_template_directory() . '/inc/plugins/codebase-repository-info-fields.php';
require_once get_template_directory() . '/inc/plugins/track-codebase-installs.php';

/**
 * Extend WordPress search to include custom post types
 * 
 * Includes Journal, Documentation, and Game post types in search results
 * for more comprehensive site-wide search functionality.
 * 
 * @param WP_Query $query The WordPress query object
 */
function chubes_extend_search_post_types($query) {
    // Only modify search queries on the frontend
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $query->set('post_type', array(
            'post',         // Default posts
            'journal',      // Journal entries
            'documentation', // Documentation posts
            'game'          // Games
        ));
    }
}
add_action('pre_get_posts', 'chubes_extend_search_post_types');

/**
 * Customizer Settings for Homepage
 * 
 * Adds customizer section for managing content below the latest content section
 * on the homepage, supporting Gutenberg blocks.
 * 
 * @param WP_Customize_Manager $wp_customize
 */
function chubes_customize_register($wp_customize) {
    // Add Homepage section
    $wp_customize->add_section('chubes_homepage', array(
        'title'    => __('Homepage Settings', 'chubes-theme'),
        'priority' => 30,
    ));

    // Add setting for custom content section
    $wp_customize->add_setting('chubes_homepage_custom_content', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post', // Allow HTML but sanitize
        'transport'         => 'refresh',
    ));

    // Add control for custom content section
    $wp_customize->add_control('chubes_homepage_custom_content', array(
        'label'    => __('Custom Content Section', 'chubes-theme'),
        'description' => __('Add content below the latest content section. You can use Gutenberg blocks by copying them from the block editor.', 'chubes-theme'),
        'section'  => 'chubes_homepage',
        'type'     => 'textarea',
        'input_attrs' => array(
            'placeholder' => __('Enter HTML or Gutenberg block markup here...', 'chubes-theme'),
            'rows' => 8,
        ),
    ));

    // Add setting for section visibility
    $wp_customize->add_setting('chubes_homepage_custom_content_enabled', array(
        'default'   => false,
        'transport' => 'refresh',
    ));

    // Add control for section visibility
    $wp_customize->add_control('chubes_homepage_custom_content_enabled', array(
        'label'    => __('Enable Custom Content Section', 'chubes-theme'),
        'section'  => 'chubes_homepage',
        'type'     => 'checkbox',
    ));
}
add_action('customize_register', 'chubes_customize_register');
