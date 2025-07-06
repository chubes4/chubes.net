<?php
/**
 * Adds custom meta fields for the Plugin post type: GitHub URL and WordPress URL.
 *
 * @author Chris Huber
 * @link https://chubes.net
 */

// Add meta box to Plugin post type
function chubes_add_plugin_meta_box() {
    add_meta_box(
        'chubes_plugin_meta',
        'Plugin URLs',
        'chubes_plugin_meta_callback',
        'plugin', // Only show on Plugin CPT
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'chubes_add_plugin_meta_box');

// Meta box display callback
function chubes_plugin_meta_callback($post) {
    // Get existing values
    $github_url = get_post_meta($post->ID, 'github_url', true);
    $wp_url = get_post_meta($post->ID, 'wp_url', true);

    // Security nonce field
    wp_nonce_field('chubes_save_plugin_meta', 'chubes_plugin_meta_nonce');
    ?>
    <label for="github_url"><strong>GitHub URL:</strong></label>
    <input type="url" id="github_url" name="github_url" value="<?php echo esc_attr($github_url); ?>" style="width:100%; margin-bottom: 10px;" />

    <label for="wp_url"><strong>WordPress.org URL:</strong></label>
    <input type="url" id="wp_url" name="wp_url" value="<?php echo esc_attr($wp_url); ?>" style="width:100%;" />
    <?php
}

// Save meta box content
function chubes_save_plugin_meta($post_id) {
    // Security check
    if (!isset($_POST['chubes_plugin_meta_nonce']) ||
        !wp_verify_nonce($_POST['chubes_plugin_meta_nonce'], 'chubes_save_plugin_meta')) {
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

    // Save GitHub URL
    if (isset($_POST['github_url'])) {
        update_post_meta($post_id, 'github_url', esc_url($_POST['github_url']));
    }

    // Save WordPress.org URL
    if (isset($_POST['wp_url'])) {
        update_post_meta($post_id, 'wp_url', esc_url($_POST['wp_url']));
    }
}
add_action('save_post', 'chubes_save_plugin_meta'); 