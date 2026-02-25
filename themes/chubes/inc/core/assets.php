<?php
/**
 * Centralized Asset Management
 * 
 * Manages all theme asset enqueuing (CSS and JavaScript) in a single location.
 * Uses filemtime() for cache-busting on file changes.
 * Conditionally loads assets based on template type and post type.
 */

/**
 * Enqueue Theme Assets
 *
 * Centralizes all CSS and JavaScript asset loading with conditional logic
 * based on current page context. Uses filemtime() for cache-busting.
 */
function chubes_enqueue_assets() {
    $theme_dir  = get_template_directory_uri();
    $theme_path = get_template_directory();
    
    // Main stylesheet - always enqueued
    wp_enqueue_style(
        'chubes-style',
        get_stylesheet_uri(),
        array(),
        filemtime( $theme_path . '/style.css' )
    );
    
    // Root CSS variables and common styles - always enqueued
    wp_enqueue_style(
        'root-style',
        $theme_dir . '/assets/css/root.css',
        array('chubes-style'),
        filemtime( $theme_path . '/assets/css/root.css' )
    );
    
    // Home page CSS
    if ( is_front_page() ) {
        wp_enqueue_style(
            'home-style',
            $theme_dir . '/assets/css/home.css',
            array(),
            filemtime( $theme_path . '/assets/css/home.css' )
        );
    }
    
    // Single post CSS - on all single post views
    if ( is_singular() && !is_page() ) {
        wp_enqueue_style(
            'single-style',
            $theme_dir . '/assets/css/single.css',
            array(),
            filemtime( $theme_path . '/assets/css/single.css' )
        );
    }
    
    // DocSync bridge — maps docsync tokens to chubes theme variables
    if ( is_singular( 'documentation' ) || is_post_type_archive( 'documentation' ) || is_tax( 'project' ) ) {
        wp_enqueue_style(
            'docsync-bridge',
            $theme_dir . '/assets/css/docsync-bridge.css',
            array( 'root-style' ),
            filemtime( $theme_path . '/assets/css/docsync-bridge.css' )
        );
    }

    // Archives CSS - on archives, taxonomy, categories, tags
    if ( is_post_type_archive() || is_category() || is_tag() || is_tax() ) {
        wp_enqueue_style(
            'archives-style',
            $theme_dir . '/assets/css/archives.css',
            array(),
            filemtime( $theme_path . '/assets/css/archives.css' )
        );
    }
    
    // Mobile navigation functionality - always enqueued
    wp_enqueue_script(
        'navigation',
        $theme_dir . '/assets/js/navigation.js',
        array(),
        filemtime( $theme_path . '/assets/js/navigation.js' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'chubes_enqueue_assets' );
