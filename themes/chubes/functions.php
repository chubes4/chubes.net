<?php
// Theme setup function
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

// Enqueue styles and scripts
function chubes_enqueue_scripts() {
    // Enqueue main stylesheet with dynamic versioning
    wp_enqueue_style('chubes-style', get_stylesheet_uri(), array(), filemtime(get_template_directory() . '/style.css'));
    
    // Enqueue home page CSS on front page
    if (is_front_page()) {
        wp_enqueue_style('home-style', get_template_directory_uri() . '/assets/css/home.css', array(), filemtime(get_template_directory() . '/assets/css/home.css'));
    }
    
    // Enqueue page-specific assets
    
    if (is_post_type_archive('portfolio')) {
        // Enqueue load-more.js only on portfolio archive pages
        wp_enqueue_script('load-more', get_template_directory_uri() . '/assets/js/load-more.js', array('jquery'), filemtime(get_template_directory() . '/assets/js/load-more.js'), true);
        
        global $wp_query;
        wp_localize_script('load-more', 'chubes_params', array(
            'ajaxurl'      => admin_url('admin-ajax.php'),
            'current_page' => max(1, get_query_var('paged')),
            'max_page'     => $wp_query->max_num_pages
        ));
    }
    
        wp_enqueue_script('reveal', get_template_directory_uri() . '/assets/js/reveal.js', array('jquery'), filemtime(get_template_directory() . '/assets/js/reveal.js'), true);
}
add_action('wp_enqueue_scripts', 'chubes_enqueue_scripts');

// Remove WordPress version for security
function chubes_remove_wp_version() {
    return '';
}
add_filter('the_generator', 'chubes_remove_wp_version');

// Disable WordPress emoji scripts for performance
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

/**
 * Get parent page information for navigation
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
    // For post type archives (like Portfolio or Journal)
    elseif (is_post_type_archive()) {
        // These should go back to the homepage
        $parent = array(
            'url' => home_url('/'),
            'title' => 'Chubes.net'
        );
    }
    
    return $parent;
}



// Include theme functionality
require_once get_template_directory() . '/inc/custom-post-types.php';
require_once get_template_directory() . '/inc/custom-taxonomies.php';
require_once get_template_directory() . '/inc/breadcrumbs.php';
require_once get_template_directory() . '/inc/contact-ajax.php';
require_once get_template_directory() . '/inc/portfolio/portfolio-custom-fields.php';
require_once get_template_directory() . '/inc/portfolio/portfolio-image-overlay.php';
require_once get_template_directory() . '/inc/utils/load-more.php';
require_once get_template_directory() . '/inc/utils/instagram-embeds.php';
require_once get_template_directory() . '/inc/plugins/plugin-custom-fields.php';
require_once get_template_directory() . '/inc/plugins/track-plugin-installs.php';
