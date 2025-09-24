<?php
/**
 * Custom Post Types Registration
 * 
 * Registers Journal, Game, and Documentation custom post types.
 * All CPTs support Gutenberg editor and have public archives.
 */

/**
 * Register Journal Custom Post Type
 * 
 * Blog-style content separate from WordPress default posts.
 * Enables Gutenberg editor and public archives at /journal.
 */
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
 * Register Game Post Type
 * 
 * Interactive content hosting for games and interactive experiences.
 * Public archives available at /game with Gutenberg support.
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
 * Register Documentation Post Type
 * 
 * Plugin documentation, guides, and tutorials organized by plugin taxonomy.
 * Hierarchical structure supports parent/child documentation pages.
 * Public archives available at /docs with plugin taxonomy integration.
 */
function chubes_register_documentation_post_type() {
    $labels = array(
        'name'                  => _x( 'Documentation', 'Post Type General Name', 'chubes' ),
        'singular_name'         => _x( 'Document', 'Post Type Singular Name', 'chubes' ),
        'menu_name'             => __( 'Documentation', 'chubes' ),
        'name_admin_bar'        => __( 'Document', 'chubes' ),
        'archives'              => __( 'Documentation Archives', 'chubes' ),
        'attributes'            => __( 'Document Attributes', 'chubes' ),
        'parent_item_colon'     => __( 'Parent Document:', 'chubes' ),
        'all_items'             => __( 'All Documentation', 'chubes' ),
        'add_new_item'          => __( 'Add New Document', 'chubes' ),
        'add_new'               => __( 'Add New', 'chubes' ),
        'new_item'              => __( 'New Document', 'chubes' ),
        'edit_item'             => __( 'Edit Document', 'chubes' ),
        'update_item'           => __( 'Update Document', 'chubes' ),
        'view_item'             => __( 'View Document', 'chubes' ),
        'view_items'            => __( 'View Documentation', 'chubes' ),
        'search_items'          => __( 'Search Documentation', 'chubes' ),
        'not_found'             => __( 'Not found', 'chubes' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'chubes' ),
        'featured_image'        => __( 'Featured Image', 'chubes' ),
        'set_featured_image'    => __( 'Set featured image', 'chubes' ),
        'remove_featured_image' => __( 'Remove featured image', 'chubes' ),
        'use_featured_image'    => __( 'Use as featured image', 'chubes' ),
        'insert_into_item'      => __( 'Insert into document', 'chubes' ),
        'uploaded_to_this_item' => __( 'Uploaded to this document', 'chubes' ),
        'items_list'            => __( 'Documentation list', 'chubes' ),
        'items_list_navigation' => __( 'Documentation list navigation', 'chubes' ),
        'filter_items_list'     => __( 'Filter documentation list', 'chubes' ),
    );
    $args = array(
        'label'                 => __( 'Documentation', 'chubes' ),
        'description'           => __( 'Post type for plugin documentation, guides, and tutorials.', 'chubes' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'page-attributes' ),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 8,
        'menu_icon'             => 'dashicons-media-document',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => 'docs',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array('slug' => 'docs'),
    );
    register_post_type( 'documentation', $args );
}
add_action( 'init', 'chubes_register_documentation_post_type', 0 ); 