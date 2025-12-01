<?php
/**
 * Custom Post Types Registration
 * 
 * Registers Journal custom post type.
 * 
 * Note: Documentation CPT is registered by chubes-docs plugin.
 * Note: Game CPT is registered by chubes-games plugin.
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
        'show_in_rest'  => true,
    );
    register_post_type('journal', $args);
}
add_action('init', 'chubes_register_journal_cpt');
