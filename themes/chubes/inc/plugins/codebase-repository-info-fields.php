<?php
/**
 * Codebase Taxonomy Repository Info Fields
 * 
 * Unified custom fields system for codebase taxonomy terms (plugins, themes, apps, tools).
 * Adds GitHub URL and WordPress.org URL fields with install statistics display.
 * Replaces separate plugin-taxonomy-fields.php and theme-taxonomy-fields.php files.
 * Integrates with unified install tracking system in track-codebase-installs.php.
 */

// Add custom fields to codebase taxonomy add form
function chubes_codebase_taxonomy_add_fields() {
    ?>
    <div class="form-field term-github-url-wrap">
        <label for="github-url">GitHub URL</label>
        <input type="url" name="github_url" id="github-url" value="" size="40" />
        <p>The GitHub repository URL for this codebase project.</p>
    </div>

    <div class="form-field term-wp-url-wrap">
        <label for="wp-url">WordPress.org URL</label>
        <input type="url" name="wp_url" id="wp-url" value="" size="40" />
        <p>The WordPress.org directory URL (plugin or theme).</p>
    </div>

    <?php
}
add_action('codebase_add_form_fields', 'chubes_codebase_taxonomy_add_fields');

// Add custom fields to codebase taxonomy edit form
function chubes_codebase_taxonomy_edit_fields($term) {
    $github_url = get_term_meta($term->term_id, 'codebase_github_url', true);
    $wp_url = get_term_meta($term->term_id, 'codebase_wp_url', true);
    ?>
    <tr class="form-field term-github-url-wrap">
        <th scope="row"><label for="github-url">GitHub URL</label></th>
        <td>
            <input type="url" name="github_url" id="github-url" value="<?php echo esc_attr($github_url); ?>" size="40" />
            <p class="description">The GitHub repository URL for this codebase project.</p>
        </td>
    </tr>

    <tr class="form-field term-wp-url-wrap">
        <th scope="row"><label for="wp-url">WordPress.org URL</label></th>
        <td>
            <input type="url" name="wp_url" id="wp-url" value="<?php echo esc_attr($wp_url); ?>" size="40" />
            <p class="description">The WordPress.org directory URL (plugin or theme).</p>
        </td>
    </tr>

    <tr class="form-field term-install-stats-wrap">
        <th scope="row">Install Statistics</th>
        <td>
            <?php 
            $installs = get_term_meta($term->term_id, 'codebase_install_count', true);
            $last_updated = get_term_meta($term->term_id, 'codebase_last_install_update', true);
            ?>
            <p><strong>Downloads:</strong> <?php echo $installs ? number_format($installs) : 'Not tracked'; ?></p>
            <?php if ($last_updated): ?>
                <p><strong>Last Updated:</strong> <?php echo date('M j, Y g:i A', strtotime($last_updated)); ?></p>
            <?php endif; ?>
            <p class="description">Install statistics are automatically updated from WordPress.org</p>
        </td>
    </tr>
    <?php
}
add_action('codebase_edit_form_fields', 'chubes_codebase_taxonomy_edit_fields');

// Save custom fields
function chubes_save_codebase_taxonomy_fields($term_id) {
    if (isset($_POST['github_url'])) {
        update_term_meta($term_id, 'codebase_github_url', esc_url($_POST['github_url']));
    }
    if (isset($_POST['wp_url'])) {
        update_term_meta($term_id, 'codebase_wp_url', esc_url($_POST['wp_url']));
    }
}
add_action('created_codebase', 'chubes_save_codebase_taxonomy_fields');
add_action('edited_codebase', 'chubes_save_codebase_taxonomy_fields');


/**
 * Helper functions for accessing codebase taxonomy field data
 * Used by templates and repository info functions
 */
function chubes_get_codebase_github_url($term_id) {
    return get_term_meta($term_id, 'codebase_github_url', true);
}

function chubes_get_codebase_wp_url($term_id) {
    return get_term_meta($term_id, 'codebase_wp_url', true);
}

function chubes_get_codebase_installs($term_id) {
    return get_term_meta($term_id, 'codebase_install_count', true);
}