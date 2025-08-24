<?php

function chubes_fetch_plugin_data($plugin_slug) {
    $api_url = "https://api.wordpress.org/plugins/info/1.0/{$plugin_slug}.json";
    
    $response = wp_remote_get($api_url, array(
        'timeout' => 15,
        'user-agent' => 'Chubes Plugin Tracker/1.0'
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

function chubes_update_plugin_installs($post_id, $wp_url) {
    $slug = chubes_extract_plugin_slug($wp_url);
    $plugin_data = $slug ? chubes_fetch_plugin_data($slug) : false;
    
    if (!$plugin_data) return false;
    
    $installs = intval($plugin_data['downloaded'] ?? 0);
    
    update_post_meta($post_id, 'wp_installs', $installs);
    update_post_meta($post_id, 'wp_last_updated', current_time('mysql'));
    update_post_meta($post_id, 'wp_plugin_slug', $slug);
    update_post_meta($post_id, 'wp_plugin_data', $plugin_data);
    
    return true;
}

function chubes_extract_plugin_slug($wp_url) {
    return preg_match('/(?:wordpress\.org|plugins\.wordpress\.org)\/plugins\/([^\/\?]+)/', $wp_url, $matches) 
        ? $matches[1] 
        : false;
}

function chubes_get_plugin_installs($post_id) {
    $installs = get_post_meta($post_id, 'wp_installs', true);
    return intval($installs);
}

function chubes_get_total_plugin_installs() {
    $args = array(
        'post_type' => 'plugin',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'wp_installs',
                'compare' => 'EXISTS'
            )
        )
    );
    
    $plugins = get_posts($args);
    $total = 0;
    
    foreach ($plugins as $plugin) {
        $total += chubes_get_plugin_installs($plugin->ID);
    }
    
    return $total;
}

/**
 * Update all plugin install counts
 * 
 * @return array Results of the update process
 */
function chubes_update_all_plugin_installs() {
    $args = array(
        'post_type' => 'plugin',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'wp_url',
                'compare' => 'EXISTS'
            )
        )
    );
    
    $plugins = get_posts($args);
    $results = array(
        'success' => 0,
        'failed' => 0,
        'skipped' => 0
    );
    
    foreach ($plugins as $plugin) {
        $wp_url = get_post_meta($plugin->ID, 'wp_url', true);
        
        if (empty($wp_url)) {
            $results['skipped']++;
            continue;
        }
        
        if (chubes_update_plugin_installs($plugin->ID, $wp_url)) {
            $results['success']++;
        } else {
            $results['failed']++;
        }
        
        // Add a small delay to be respectful to the API
        usleep(500000); // 0.5 seconds
    }
    
    return $results;
}

/**
 * Schedule automatic updates of plugin install data
 */
function chubes_schedule_plugin_install_updates() {
    if (!wp_next_scheduled('chubes_update_plugin_installs')) {
        wp_schedule_event(time(), 'daily', 'chubes_update_plugin_installs');
    }
}
add_action('wp', 'chubes_schedule_plugin_install_updates');

function chubes_update_plugin_installs_on_save($post_id) {
    if (get_post_type($post_id) !== 'plugin' || 
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        !current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $wp_url = get_post_meta($post_id, 'wp_url', true);
    if ($wp_url) {
        chubes_update_plugin_installs($post_id, $wp_url);
    }
}
add_action('save_post', 'chubes_update_plugin_installs_on_save');

function chubes_update_plugin_installs_cron() {
    chubes_update_all_plugin_installs();
}
add_action('chubes_update_plugin_installs', 'chubes_update_plugin_installs_cron');
function chubes_add_plugin_installs_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=plugin',
        'Update Install Counts',
        'Update Install Counts',
        'manage_options',
        'plugin-installs',
        'chubes_plugin_installs_admin_page'
    );
}
add_action('admin_menu', 'chubes_add_plugin_installs_admin_menu');

/**
 * Admin page for manual plugin install updates
 */
function chubes_plugin_installs_admin_page() {
    if (isset($_POST['update_installs']) && wp_verify_nonce($_POST['_wpnonce'], 'update_plugin_installs')) {
        $results = chubes_update_all_plugin_installs();
        echo '<div class="notice notice-success"><p>Update completed: ' . $results['success'] . ' successful, ' . $results['failed'] . ' failed, ' . $results['skipped'] . ' skipped.</p></div>';
    }
    
    $total_installs = chubes_get_total_plugin_installs();
    ?>
    <div class="wrap">
        <h1>Plugin Install Tracking</h1>
        
        <div class="card">
            <h2>Current Stats</h2>
            <p><strong>Total Installs:</strong> <?php echo number_format($total_installs); ?></p>
        </div>
        
        <div class="card">
            <h2>Manual Update</h2>
            <p>Click the button below to manually update all plugin install counts from WordPress.org.</p>
            <form method="post">
                <?php wp_nonce_field('update_plugin_installs'); ?>
                <input type="submit" name="update_installs" class="button button-primary" value="Update Install Counts">
            </form>
        </div>
        
        <div class="card">
            <h2>Individual Plugins</h2>
            <?php
            $plugins = get_posts(array(
                'post_type' => 'plugin',
                'post_status' => 'publish',
                'posts_per_page' => -1
            ));
            
            if ($plugins): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Plugin</th>
                            <th>WordPress.org URL</th>
                            <th>Installs</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($plugins as $plugin): 
                            $wp_url = get_post_meta($plugin->ID, 'wp_url', true);
                            $installs = chubes_get_plugin_installs($plugin->ID);
                            $last_updated = get_post_meta($plugin->ID, 'wp_last_updated', true);
                        ?>
                        <tr>
                            <td><?php echo esc_html($plugin->post_title); ?></td>
                            <td><?php echo esc_url($wp_url); ?></td>
                            <td><?php echo number_format($installs); ?></td>
                            <td><?php echo $last_updated ? esc_html($last_updated) : 'Never'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No plugins found.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
} 