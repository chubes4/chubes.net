<?php
function chubes_add_portfolio_meta_box() {
    add_meta_box(
        'chubes_portfolio_meta',
        'Project Details',
        'chubes_portfolio_meta_callback',
        'portfolio', // Only show on Portfolio CPT
        'side', // Place it in the right-hand sidebar
        'default'
    );
}
add_action('add_meta_boxes', 'chubes_add_portfolio_meta_box');

function chubes_portfolio_meta_callback($post) {
    // Get existing values
    $project_url = get_post_meta($post->ID, 'project_url', true);
    $tech_stack = get_post_meta($post->ID, 'tech_stack', true);

    // Security nonce field
    wp_nonce_field('chubes_save_portfolio_meta', 'chubes_portfolio_meta_nonce');
    
    // Project URL Field
    echo '<label for="project_url"><strong>Project URL:</strong></label>';
    echo '<input type="url" id="project_url" name="project_url" value="' . esc_attr($project_url) . '" style="width:100%; margin-bottom: 10px;"/>';

    // Tech Stack Field
    echo '<label for="tech_stack"><strong>Tech Stack (comma-separated):</strong></label>';
    echo '<textarea id="tech_stack" name="tech_stack" rows="2" style="width:100%;">' . esc_textarea($tech_stack) . '</textarea>';
}

function chubes_save_portfolio_meta($post_id) {
    // Security check
    if (!isset($_POST['chubes_portfolio_meta_nonce']) || 
        !wp_verify_nonce($_POST['chubes_portfolio_meta_nonce'], 'chubes_save_portfolio_meta')) {
        return;
    }

    // Prevent autosave issues
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Ensure user has permission
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save Project URL
    if (isset($_POST['project_url'])) {
        update_post_meta($post_id, 'project_url', esc_url($_POST['project_url']));
    }

    // Save Tech Stack
    if (isset($_POST['tech_stack'])) {
        update_post_meta($post_id, 'tech_stack', sanitize_textarea_field($_POST['tech_stack']));
    }
}
add_action('save_post', 'chubes_save_portfolio_meta');
