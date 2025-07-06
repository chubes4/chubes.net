<?php
// Register Portfolio Custom Post Type
function chubes_register_portfolio_cpt() {
    $args = array(
        'label'         => __('Portfolio', 'chubes-theme'),
        'public'        => true,
        'show_ui'       => true,
        'menu_position' => 5,
        'menu_icon'     => 'dashicons-portfolio',
        'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions', 'page-attributes'),
        'has_archive'   => true,
        'rewrite'       => array('slug' => 'portfolio'),
        'show_in_rest'  => true, // Enables Gutenberg support
    );
    register_post_type('portfolio', $args);
}
add_action('init', 'chubes_register_portfolio_cpt');



// Register Journal Custom Post Type
function chubes_register_journal_cpt() {
    $args = array(
        'label'         => __('Journal', 'chubes-theme'),
        'public'        => true,
        'show_ui'       => true,
        'menu_position' => 6,
        'menu_icon'     => 'dashicons-edit',
        'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        'has_archive'   => true,
        'rewrite'       => array('slug' => 'journal'),
        'show_in_rest'  => true, // Enables Gutenberg support
    );
    register_post_type('journal', $args);
}
add_action('init', 'chubes_register_journal_cpt');

/**
 * Register Game Post Type.
 */
function chubes_register_game_post_type() {
    $labels = array(
        'name'                  => _x( 'Games', 'Post Type General Name', 'chubes' ),
        'singular_name'         => _x( 'Game', 'Post Type Singular Name', 'chubes' ),
        'menu_name'             => __( 'Games', 'chubes' ),
        'name_admin_bar'        => __( 'Game', 'chubes' ),
        'archives'              => __( 'Game Archives', 'chubes' ),
        'attributes'            => __( 'Game Attributes', 'chubes' ),
        'parent_item_colon'     => __( 'Parent Game:', 'chubes' ),
        'all_items'             => __( 'All Games', 'chubes' ),
        'add_new_item'          => __( 'Add New Game', 'chubes' ),
        'add_new'               => __( 'Add New', 'chubes' ),
        'new_item'              => __( 'New Game', 'chubes' ),
        'edit_item'             => __( 'Edit Game', 'chubes' ),
        'update_item'           => __( 'Update Game', 'chubes' ),
        'view_item'             => __( 'View Game', 'chubes' ),
        'view_items'            => __( 'View Games', 'chubes' ),
        'search_items'          => __( 'Search Game', 'chubes' ),
        'not_found'             => __( 'Not found', 'chubes' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'chubes' ),
        'featured_image'        => __( 'Featured Image', 'chubes' ),
        'set_featured_image'    => __( 'Set featured image', 'chubes' ),
        'remove_featured_image' => __( 'Remove featured image', 'chubes' ),
        'use_featured_image'    => __( 'Use as featured image', 'chubes' ),
        'insert_into_item'      => __( 'Insert into game', 'chubes' ),
        'uploaded_to_this_item' => __( 'Uploaded to this game', 'chubes' ),
        'items_list'            => __( 'Games list', 'chubes' ),
        'items_list_navigation' => __( 'Games list navigation', 'chubes' ),
        'filter_items_list'     => __( 'Filter games list', 'chubes' ),
    );
    $args = array(
        'label'                 => __( 'Game', 'chubes' ),
        'description'           => __( 'Post type for hosting games.', 'chubes' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
        'taxonomies'            => array(),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-games',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => 'game',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array('slug' => 'game'),
    );
    register_post_type( 'game', $args );
}
add_action( 'init', 'chubes_register_game_post_type', 0 );

/**
 * Register Plugin Post Type.
 * Note: This is for showcasing/distributing Chris Huber's plugins, not the WordPress core Plugins menu.
 * Consider integrating checkout/licensing flows here in the future.
 */
function chubes_register_plugin_post_type() {
    $labels = array(
        'name'                  => _x( 'Plugins', 'Post Type General Name', 'chubes' ),
        'singular_name'         => _x( 'Plugin', 'Post Type Singular Name', 'chubes' ),
        'menu_name'             => __( 'My Plugins', 'chubes' ), // Use 'My Plugins' to distinguish from WP Plugins
        'name_admin_bar'        => __( 'Plugin', 'chubes' ),
        'archives'              => __( 'Plugin Archives', 'chubes' ),
        'attributes'            => __( 'Plugin Attributes', 'chubes' ),
        'parent_item_colon'     => __( 'Parent Plugin:', 'chubes' ),
        'all_items'             => __( 'All Plugins', 'chubes' ),
        'add_new_item'          => __( 'Add New Plugin', 'chubes' ),
        'add_new'               => __( 'Add New', 'chubes' ),
        'new_item'              => __( 'New Plugin', 'chubes' ),
        'edit_item'             => __( 'Edit Plugin', 'chubes' ),
        'update_item'           => __( 'Update Plugin', 'chubes' ),
        'view_item'             => __( 'View Plugin', 'chubes' ),
        'view_items'            => __( 'View Plugins', 'chubes' ),
        'search_items'          => __( 'Search Plugin', 'chubes' ),
        'not_found'             => __( 'Not found', 'chubes' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'chubes' ),
        'featured_image'        => __( 'Featured Image', 'chubes' ),
        'set_featured_image'    => __( 'Set featured image', 'chubes' ),
        'remove_featured_image' => __( 'Remove featured image', 'chubes' ),
        'use_featured_image'    => __( 'Use as featured image', 'chubes' ),
        'insert_into_item'      => __( 'Insert into plugin', 'chubes' ),
        'uploaded_to_this_item' => __( 'Uploaded to this plugin', 'chubes' ),
        'items_list'            => __( 'Plugins list', 'chubes' ),
        'items_list_navigation' => __( 'Plugins list navigation', 'chubes' ),
        'filter_items_list'     => __( 'Filter plugins list', 'chubes' ),
    );
    $args = array(
        'label'                 => __( 'Plugin', 'chubes' ),
        'description'           => __( 'Post type for distributing and documenting Chris Huber\'s plugins.', 'chubes' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
        'taxonomies'            => array(),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 7, // Below Journal, above default WP Plugins
        'menu_icon'             => 'dashicons-admin-plugins', // WP plugin icon, but menu label is 'My Plugins'
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => 'plugin',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array('slug' => 'plugin'),
    );
    register_post_type( 'plugin', $args );
}
add_action( 'init', 'chubes_register_plugin_post_type', 0 ); 