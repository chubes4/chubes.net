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
    add_editor_style('assets/css/root.css');


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
 * Load theme functionality modules
 * 
 * Modular architecture with single responsibility principle:
 * - Core: Assets, CPTs, template filters
 * - Contact: REST API for form processing with spam protection
 * 
 * Documentation CPT, codebase taxonomy, rewrite rules, related posts,
 * and sync fields are registered by the chubes-docs plugin which must
 * be active for documentation features.
 */
require_once get_template_directory() . '/inc/core/breadcrumbs.php';
require_once get_template_directory() . '/inc/core/back-navigation.php';
require_once get_template_directory() . '/inc/core/assets.php';
require_once get_template_directory() . '/inc/journal/journal-post-type.php';
require_once get_template_directory() . '/inc/core/filters.php';
require_once get_template_directory() . '/inc/journal/archive.php';
require_once get_template_directory() . '/inc/homepage/columns.php';

/**
 * Extend Search Results to Include Custom Post Types
 * 
 * Includes Journal post type in search results.
 * Game is added by chubes-games plugin when active.
 * Documentation is added by chubes-docs plugin when active.
 * 
 * @param WP_Query $query The WordPress query object
 */
function chubes_extend_search_post_types($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $post_types = array('post', 'journal');
        $query->set('post_type', apply_filters('chubes_search_post_types', $post_types));
    }
}
add_action('pre_get_posts', 'chubes_extend_search_post_types');


