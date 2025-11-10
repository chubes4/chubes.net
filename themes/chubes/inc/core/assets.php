<?php
/**
 * Centralized Asset Management
 * 
 * Manages all theme asset enqueuing (CSS and JavaScript) in a single location.
 * Uses filemtime() for cache-busting on file changes.
 * Conditionally loads assets based on template type and post type.
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
    
    // Home page CSS
    if ( is_front_page() ) {
        wp_enqueue_style(
            'home-style',
            $theme_dir . '/assets/css/home.css',
            array(),
            filemtime( $theme_path . '/assets/css/home.css' )
        );
    }
    
    // Documentation CSS
    if ( is_singular( 'documentation' ) ) {
        wp_enqueue_style(
            'documentation-style',
            $theme_dir . '/assets/css/documentation.css',
            array(),
            filemtime( $theme_path . '/assets/css/documentation.css' )
        );
    }
    
    // Archives CSS - on archives, taxonomy, categories, tags, and custom archive routes
    $has_custom_archives = (
        get_query_var( 'docs_category_archive' ) ||
        get_query_var( 'docs_project_archive' ) ||
        get_query_var( 'codebase_archive' ) ||
        get_query_var( 'codebase_project' )
    );
    if (
        is_post_type_archive() ||
        is_tax( 'codebase' ) ||
        is_post_type_archive( 'documentation' ) ||
        is_category() ||
        is_tag() ||
        is_tax() ||
        $has_custom_archives
    ) {
        wp_enqueue_style(
            'archives-style',
            $theme_dir . '/assets/css/archives.css',
            array(),
            filemtime( $theme_path . '/assets/css/archives.css' )
        );
    }
    
    // Contact form CSS and JavaScript - on contact page
    if ( is_page( 'contact' ) || is_page_template( 'page-contact.php' ) ) {
        wp_enqueue_style(
            'contact-css',
            $theme_dir . '/assets/css/contact.css',
            array(),
            filemtime( $theme_path . '/assets/css/contact.css' )
        );
        wp_enqueue_script(
            'contact-js',
            $theme_dir . '/assets/js/contact.js',
            array(),
            filemtime( $theme_path . '/assets/js/contact.js' ),
            true
        );
        
        // Localize the contact script with REST API URL and nonce
        wp_localize_script( 'contact-js', 'chubes_contact_params', array(
            'rest_url' => rest_url( 'chubes/v1/contact' ),
            'nonce'    => wp_create_nonce( 'contact_nonce' ),
        ) );
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
