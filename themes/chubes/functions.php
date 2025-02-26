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
}
add_action('after_setup_theme', 'chubes_theme_setup');

// Enqueue styles and scripts
function chubes_enqueue_scripts() {
    // Enqueue main stylesheet with dynamic versioning
    wp_enqueue_style('chubes-style', get_stylesheet_uri(), array(), filemtime(get_template_directory() . '/style.css'));
    
    // Enqueue page-specific assets
    if (is_page('services')) {
        // Enqueue Services page CSS
        wp_enqueue_style('services-style', get_template_directory_uri() . '/css/page-services.css', array(), filemtime(get_template_directory() . '/css/page-services.css'));
        
        // Enqueue Services page JavaScript and localize the AJAX URL
        wp_enqueue_script('services-script', get_template_directory_uri() . '/js/services.js', array('jquery'), filemtime(get_template_directory() . '/js/services.js'), true);
        wp_localize_script('services-script', 'chubes_vars', array(
            'ajaxUrl' => admin_url('admin-post.php')
        ));
    }
    
    if (is_post_type_archive('portfolio')) {
        // Enqueue load-more.js only on portfolio archive pages
        wp_enqueue_script('load-more', get_template_directory_uri() . '/js/load-more.js', array('jquery'), filemtime(get_template_directory() . '/js/load-more.js'), true);
        
        global $wp_query;
        wp_localize_script('load-more', 'loadmore_params', array(
            'ajaxurl'      => admin_url('admin-ajax.php'),
            'current_page' => max(1, get_query_var('paged')),
            'max_page'     => $wp_query->max_num_pages
        ));
    }
    
    if (is_front_page()) {
        // Enqueue reveal.js only on the homepage
        wp_enqueue_script('reveal', get_template_directory_uri() . '/js/reveal.js', array('jquery'), filemtime(get_template_directory() . '/js/reveal.js'), true);
    }
}
add_action('wp_enqueue_scripts', 'chubes_enqueue_scripts');






// Automatically include all PHP files in the /php/ folder
function chubes_include_php_files() {
    $php_dir = get_template_directory() . '/php/';

    if (file_exists($php_dir)) {
        foreach (glob($php_dir . '*.php') as $file) {
            require_once $file;
        }
    }
}
chubes_include_php_files();


// Remove WordPress version for security
function chubes_remove_wp_version() {
    return '';
}
add_filter('the_generator', 'chubes_remove_wp_version');

// Disable WordPress emoji scripts for performance
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
