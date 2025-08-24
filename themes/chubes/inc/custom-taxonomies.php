<?php
/**
 * Register Plugin Taxonomy.
 * Used for organizing documentation by plugin.
 */
function chubes_register_plugin_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Plugins', 'Taxonomy General Name', 'chubes' ),
        'singular_name'              => _x( 'Plugin', 'Taxonomy Singular Name', 'chubes' ),
        'menu_name'                  => __( 'Plugins', 'chubes' ),
        'all_items'                  => __( 'All Plugins', 'chubes' ),
        'parent_item'                => __( 'Parent Plugin', 'chubes' ),
        'parent_item_colon'          => __( 'Parent Plugin:', 'chubes' ),
        'new_item_name'              => __( 'New Plugin Name', 'chubes' ),
        'add_new_item'               => __( 'Add New Plugin', 'chubes' ),
        'edit_item'                  => __( 'Edit Plugin', 'chubes' ),
        'update_item'                => __( 'Update Plugin', 'chubes' ),
        'view_item'                  => __( 'View Plugin', 'chubes' ),
        'separate_items_with_commas' => __( 'Separate plugins with commas', 'chubes' ),
        'add_or_remove_items'        => __( 'Add or remove plugins', 'chubes' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'chubes' ),
        'popular_items'              => __( 'Popular Plugins', 'chubes' ),
        'search_items'               => __( 'Search Plugins', 'chubes' ),
        'not_found'                  => __( 'Not Found', 'chubes' ),
        'no_terms'                   => __( 'No plugins', 'chubes' ),
        'items_list'                 => __( 'Plugins list', 'chubes' ),
        'items_list_navigation'      => __( 'Plugins list navigation', 'chubes' ),
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
        'rewrite'                    => array( 'slug' => 'docs/plugin' ),
    );
    register_taxonomy( 'plugin', array( 'documentation' ), $args );
}
add_action( 'init', 'chubes_register_plugin_taxonomy', 0 );