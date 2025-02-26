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

