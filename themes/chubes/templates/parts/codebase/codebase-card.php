<?php 
/**
 * Dynamic Codebase Project Card Template Part
 * 
 * Displays a project card with dynamically generated content type buttons.
 * Shows buttons for ANY post type on the site that has content associated with this project.
 * Used by codebase archive pages (/plugins, /themes, etc.)
 * 
 * Expected variables:
 * @var WP_Term $term The codebase taxonomy term for this project
 * @var array $repo_info Repository information from chubes_get_repository_info()
 * @var string $project_type The project type (plugin, theme, app, tool)
 */

if (!isset($term) || !isset($repo_info)) {
    return;
}

// Get all public post types that support the codebase taxonomy
$all_post_types = get_post_types(['public' => true], 'objects');
$content_buttons = [];
$total_content = 0;

// Check each post type for content associated with this project
foreach ($all_post_types as $post_type_obj) {
    $post_type = $post_type_obj->name;
    
    // Skip attachment and page post types
    if (in_array($post_type, ['attachment', 'page'])) {
        continue;
    }
    
    // Check if this post type supports the codebase taxonomy
    if (!is_object_in_taxonomy($post_type, 'codebase')) {
        continue;
    }
    
    // Query for content of this type associated with this project
    $query = new WP_Query([
        'post_type' => $post_type,
        'tax_query' => [
            [
                'taxonomy' => 'codebase',
                'field' => 'term_id',
                'terms' => $term->term_id,
            ],
        ],
        'posts_per_page' => 1,
        'post_status' => 'publish',
        'fields' => 'ids' // Only get IDs for performance
    ]);
    
    $count = $query->found_posts;
    wp_reset_postdata();
    
    if ($count > 0) {
        $content_buttons[] = [
            'type' => $post_type,
            'label' => $post_type_obj->labels->name,
            'singular' => $post_type_obj->labels->singular_name,
            'count' => $count,
            'url' => chubes_generate_content_type_url($post_type, $term)
        ];
        $total_content += $count;
    }
}

// Project type configuration for external buttons
$type_config = [
    'plugin' => ['type_name' => 'Plugin', 'download_text' => 'Download Plugin'],
    'theme' => ['type_name' => 'Theme', 'download_text' => 'Download Theme'],
    'app' => ['type_name' => 'App', 'download_text' => 'Launch App'],
    'tool' => ['type_name' => 'Tool', 'download_text' => 'Download Tool']
];
$current_config = $type_config[$project_type] ?? $type_config['plugin'];
?>

<div class="codebase-card" data-project-type="<?php echo esc_attr($project_type); ?>">
    <!-- Project Header -->
    <div class="card-header">
        <h3 class="project-title">
            <a href="<?php echo esc_url(get_term_link($term)); ?>"><?php echo esc_html($term->name); ?></a>
        </h3>
        
        <?php if ($term->description): ?>
            <p class="project-description"><?php echo esc_html(wp_trim_words($term->description, 20)); ?></p>
        <?php endif; ?>
        
        <div class="project-stats">
            <?php if ($repo_info['installs'] > 0): ?>
                <span class="stat-item">
                    <span class="stat-number"><?php echo number_format($repo_info['installs']); ?></span>
                    <span class="stat-label">downloads</span>
                </span>
            <?php endif; ?>
            
            <?php if ($total_content > 0): ?>
                <span class="stat-item">
                    <span class="stat-number"><?php echo $total_content; ?></span>
                    <span class="stat-label">items</span>
                </span>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Dynamic Content Type Buttons -->
    <?php if (!empty($content_buttons)): ?>
        <div class="content-buttons">
            <?php foreach ($content_buttons as $button): ?>
                <a href="<?php echo esc_url($button['url']); ?>" class="content-btn" data-type="<?php echo esc_attr($button['type']); ?>">
                    <span class="btn-label">View <?php echo esc_html($button['label']); ?></span>
                    <span class="btn-count"><?php echo $button['count']; ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <!-- External Action Buttons -->
    <div class="external-buttons">
        <?php if ($repo_info['wp_url']): ?>
            <a href="<?php echo esc_url($repo_info['wp_url']); ?>" class="external-btn primary" target="_blank">
                <svg class="btn-icon"><use href="<?php echo get_template_directory_uri(); ?>/assets/fonts/social-icons.svg#icon-wordpress"></use></svg>
                <?php echo esc_html($current_config['download_text']); ?>
            </a>
        <?php endif; ?>
        
        <?php if ($repo_info['github_url']): ?>
            <a href="<?php echo esc_url($repo_info['github_url']); ?>" class="external-btn secondary" target="_blank">
                <svg class="btn-icon"><use href="<?php echo get_template_directory_uri(); ?>/assets/fonts/social-icons.svg#icon-github"></use></svg>
                GitHub
            </a>
        <?php endif; ?>
    </div>
</div>

