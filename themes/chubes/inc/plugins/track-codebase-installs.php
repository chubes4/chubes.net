<?php
/**
 * Codebase Install Tracking System
 * 
 * Unified WordPress.org API integration for tracking downloads and statistics
 * across all codebase projects (plugins, themes, apps, tools).
 * Replaces separate track-plugin-installs.php and track-theme-installs.php files.
 * Includes automated cron updates and admin interface for manual control.
 */

/**
 * Fetch codebase project data from WordPress.org API
 * 
 * @param string $slug The project slug from WordPress.org
 * @param string $project_type The project type ('plugin' or 'theme')
 * @return array|false Project data array or false on failure
 */
function chubes_fetch_codebase_data($slug, $project_type) {
    $api_url = ($project_type === 'theme') 
        ? "https://api.wordpress.org/themes/info/1.0/{$slug}.json"
        : "https://api.wordpress.org/plugins/info/1.0/{$slug}.json";
    
    $response = wp_remote_get($api_url, array(
        'timeout' => 15,
        'user-agent' => 'Chubes Codebase Tracker/1.0'
    ));
    
    if (is_wp_error($response)) {
        return false;
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    if (!$data || isset($data['error'])) {
        return false;
    }
    
    return $data;
}

/**
 * Update codebase install data for a specific project term
 * 
 * @param int $term_id Codebase taxonomy term ID
 * @param string $wp_url WordPress.org project URL
 * @return bool True on success, false on failure
 */
function chubes_update_codebase_installs($term_id, $wp_url) {
    $slug = chubes_extract_codebase_slug($wp_url);
    if (!$slug) return false;
    
    // Determine project type from URL
    $project_type = (strpos($wp_url, '/themes/') !== false) ? 'theme' : 'plugin';
    
    $project_data = chubes_fetch_codebase_data($slug, $project_type);
    if (!$project_data) return false;
    
    $installs = intval($project_data['downloaded'] ?? 0);
    
    // Update term meta with unified keys
    update_term_meta($term_id, 'codebase_install_count', $installs);
    update_term_meta($term_id, 'codebase_last_install_update', current_time('mysql'));
    
    return true;
}

/**
 * Extract project slug from WordPress.org URL
 * 
 * @param string $wp_url WordPress.org plugin or theme URL
 * @return string|false Project slug or false on failure
 */
function chubes_extract_codebase_slug($wp_url) {
    if (empty($wp_url)) return false;
    
    // Handle plugin URLs: https://wordpress.org/plugins/plugin-name/
    if (preg_match('#wordpress\.org/plugins/([^/]+)/?#', $wp_url, $matches)) {
        return $matches[1];
    }
    
    // Handle theme URLs: https://wordpress.org/themes/theme-name/
    if (preg_match('#wordpress\.org/themes/([^/]+)/?#', $wp_url, $matches)) {
        return $matches[1];
    }
    
    return false;
}

/**
 * Update all codebase projects with WordPress.org install data
 */
function chubes_update_all_codebase_installs() {
    $terms = get_terms(array(
        'taxonomy' => 'codebase',
        'hide_empty' => false,
        'meta_query' => array(
            array(
                'key' => 'codebase_wp_url',
                'compare' => 'EXISTS'
            )
        )
    ));
    
    if (empty($terms) || is_wp_error($terms)) {
        return;
    }
    
    foreach ($terms as $term) {
        $wp_url = get_term_meta($term->term_id, 'codebase_wp_url', true);
        if ($wp_url) {
            chubes_update_codebase_installs($term->term_id, $wp_url);
            sleep(1); // Rate limiting
        }
    }
}

/**
 * Get total install count across all codebase projects
 */
function chubes_get_total_codebase_installs() {
    global $wpdb;
    
    $total = $wpdb->get_var($wpdb->prepare(
        "SELECT SUM(meta_value) FROM {$wpdb->termmeta} 
         WHERE meta_key = %s AND meta_value != ''",
        'codebase_install_count'
    ));
    
    return intval($total);
}

/**
 * Schedule automatic updates via WordPress cron
 */
function chubes_schedule_codebase_installs_update() {
    if (!wp_next_scheduled('chubes_update_codebase_installs_cron')) {
        wp_schedule_event(time(), 'daily', 'chubes_update_codebase_installs_cron');
    }
}
add_action('wp', 'chubes_schedule_codebase_installs_update');

/**
 * Cron callback to update all codebase installs
 */
function chubes_cron_update_codebase_installs() {
    chubes_update_all_codebase_installs();
}
add_action('chubes_update_codebase_installs_cron', 'chubes_cron_update_codebase_installs');

/**
 * Auto-update installs when codebase terms are saved
 */
function chubes_auto_update_codebase_installs($term_id) {
    $wp_url = get_term_meta($term_id, 'codebase_wp_url', true);
    if ($wp_url) {
        chubes_update_codebase_installs($term_id, $wp_url);
    }
}
add_action('edited_codebase', 'chubes_auto_update_codebase_installs');
add_action('created_codebase', 'chubes_auto_update_codebase_installs');

/**
 * Admin interface for manual install tracking management
 */
function chubes_codebase_installs_admin_menu() {
    add_management_page(
        'Codebase Install Tracking',
        'Codebase Installs',
        'manage_options',
        'codebase-installs',
        'chubes_codebase_installs_admin_page'
    );
}
add_action('admin_menu', 'chubes_codebase_installs_admin_menu');

/**
 * Admin page for codebase install tracking
 */
function chubes_codebase_installs_admin_page() {
    if (isset($_POST['update_installs']) && wp_verify_nonce($_POST['_wpnonce'], 'update_codebase_installs')) {
        chubes_update_all_codebase_installs();
        echo '<div class="notice notice-success"><p>Codebase install counts updated successfully!</p></div>';
    }
    
    $total_installs = chubes_get_total_codebase_installs();
    $next_update = wp_next_scheduled('chubes_update_codebase_installs_cron');
    ?>
    <div class="wrap">
        <h1>Codebase Install Tracking</h1>
        
        <div class="card">
            <h2>Statistics</h2>
            <p><strong>Total Downloads Across All Projects:</strong> <?php echo number_format($total_installs); ?></p>
            <?php if ($next_update): ?>
                <p><strong>Next Automatic Update:</strong> <?php echo date('M j, Y g:i A', $next_update); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h2>Manual Update</h2>
            <p>Update install counts for all codebase projects with WordPress.org URLs.</p>
            <form method="post">
                <?php wp_nonce_field('update_codebase_installs'); ?>
                <input type="submit" name="update_installs" class="button button-primary" value="Update All Install Counts">
            </form>
        </div>
        
        <div class="card">
            <h2>Tracked Projects</h2>
            <?php
            $terms = get_terms(array(
                'taxonomy' => 'codebase',
                'hide_empty' => false,
                'meta_query' => array(
                    array(
                        'key' => 'codebase_wp_url',
                        'compare' => 'EXISTS'
                    )
                )
            ));
            
            if (!empty($terms) && !is_wp_error($terms)): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Type</th>
                            <th>Downloads</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($terms as $term): 
                            $wp_url = get_term_meta($term->term_id, 'codebase_wp_url', true);
                            $installs = get_term_meta($term->term_id, 'codebase_install_count', true);
                            $last_updated = get_term_meta($term->term_id, 'codebase_last_install_update', true);
                            $project_type = (strpos($wp_url, '/themes/') !== false) ? 'Theme' : 'Plugin';
                            ?>
                            <tr>
                                <td><strong><?php echo esc_html($term->name); ?></strong></td>
                                <td><?php echo esc_html($project_type); ?></td>
                                <td><?php echo $installs ? number_format($installs) : 'N/A'; ?></td>
                                <td><?php echo $last_updated ? date('M j, Y g:i A', strtotime($last_updated)) : 'Never'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No tracked projects found. Add WordPress.org URLs to codebase taxonomy terms to begin tracking.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
}