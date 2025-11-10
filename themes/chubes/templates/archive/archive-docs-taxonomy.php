<?php 
/**
 * Dynamic Documentation Taxonomy Archive Template
 * 
 * Part of organized template hierarchy in /templates/archive/ directory.
 * Displays documentation posts filtered by ANY taxonomy for URLs like
 * /docs/{taxonomy-term-slug}/ - works for plugin, theme, or any future taxonomy.
 * Dynamically detects which taxonomy and term via global vars set in rewrite-rules.php.
 */
get_header(); 

// Get the taxonomy and term from global vars set by template redirect
global $chubes_docs_taxonomy, $chubes_docs_term;

if (!$chubes_docs_term || !$chubes_docs_taxonomy) {
    // 404 if term or taxonomy not found
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    get_template_part('templates/404');
    get_footer();
    return;
}

$term = $chubes_docs_term;
$taxonomy = $chubes_docs_taxonomy;
?>

<main class="site-main">
    <!-- Documentation Archive Header -->
    <section class="docs-archive-header">
        <div class="container">
            <h1><?php echo esc_html($term->name); ?> Documentation</h1>
            
            <?php if ($term->description): ?>
                <div class="archive-description">
                    <p><?php echo wp_kses_post(nl2br($term->description)); ?></p>
                </div>
            <?php endif; ?>
            
            <?php 
            // Get repository information using unified codebase system
            $repo_info = chubes_get_repository_info($term);
            $project_type = $repo_info['project_type'] ?? 'project';
            
            if (!empty($repo_info)): ?>
                <div class="archive-stats">
                    <?php if (isset($repo_info['installs']) && $repo_info['installs'] > 0): ?>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo number_format($repo_info['installs']); ?></span>
                            <span class="stat-label">Downloads</span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($repo_info['total_content']) && $repo_info['total_content'] > 0): ?>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $repo_info['total_content']; ?></span>
                            <span class="stat-label">Items</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Dynamic Content Type Buttons (like codebase cards) -->
                <?php if ($taxonomy === 'codebase'): 
                    // Get all public post types that support the codebase taxonomy
                    $all_post_types = get_post_types(['public' => true], 'objects');
                    $content_buttons = [];
                    
                    foreach ($all_post_types as $post_type_obj) {
                        $post_type = $post_type_obj->name;
                        
                        // Skip attachment and page post types
                        if (in_array($post_type, ['attachment', 'page', 'documentation'])) {
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
                                'count' => $count,
                                'url' => chubes_generate_content_type_url($post_type, $term)
                            ];
                        }
                    }
                    
                    if (!empty($content_buttons)): ?>
                        <div class="content-buttons">
                            <?php foreach ($content_buttons as $button): ?>
                                <a href="<?php echo esc_url($button['url']); ?>" class="content-btn" data-type="<?php echo esc_attr($button['type']); ?>">
                                    <span class="btn-label">View <?php echo esc_html($button['label']); ?></span>
                                    <span class="btn-count"><?php echo $button['count']; ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <div class="archive-actions">
                    <?php 
                    // Project type configuration for external buttons
                    $type_config = [
                        'wordpress-plugin' => ['download_text' => 'Download Plugin'],
                        'wordpress-theme' => ['download_text' => 'Download Theme'],
                        'discord-bot' => ['download_text' => 'View Bot'],
                        'php-library' => ['download_text' => 'View Library']
                    ];
                    $current_config = $type_config[$project_type] ?? ['download_text' => 'Download'];
                    
                    if (isset($repo_info['wp_url']) && $repo_info['wp_url']): ?>
                        <a href="<?php echo esc_url($repo_info['wp_url']); ?>" class="btn primary" target="_blank">
                            <svg class="btn-icon"><use href="<?php echo get_template_directory_uri(); ?>/assets/fonts/social-icons.svg#icon-wordpress"></use></svg>
                            <?php echo esc_html($current_config['download_text']); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (isset($repo_info['github_url']) && $repo_info['github_url']): ?>
                        <a href="<?php echo esc_url($repo_info['github_url']); ?>" class="btn secondary" target="_blank">
                            <svg class="btn-icon"><use href="<?php echo get_template_directory_uri(); ?>/assets/fonts/social-icons.svg#icon-github"></use></svg>
                            View on GitHub
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Documentation Posts -->
    <section class="docs-archive-content">
        <div class="container">
            <?php
            $docs_query = new WP_Query(array(
                'post_type' => 'documentation',
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field'    => 'term_id',
                        'terms'    => $term->term_id,
                        'include_children' => true,
                    ),
                ),
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'menu_order',
                'order'          => 'ASC'
            ));
            
            if ($docs_query->have_posts()) : ?>
                <div class="documentation-list">
                    <?php while ($docs_query->have_posts()) : $docs_query->the_post(); ?>
                        <article class="doc-item">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <div class="doc-meta">
                                <small>Last updated: <?php echo get_the_modified_date(); ?></small>
                            </div>
                            <?php if (has_excerpt()) : ?>
                                <p class="doc-excerpt"><?php the_excerpt(); ?></p>
                            <?php endif; ?>
                        </article>
                    <?php endwhile; ?>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="no-docs">
                    <p>Documentation for <?php echo esc_html($term->name); ?> is coming soon.</p>
                    <?php if (!empty($repo_info['github_url'])): ?>
                        <p>In the meantime, check out the <a href="<?php echo esc_url($repo_info['github_url']); ?>" target="_blank">GitHub repository</a> for technical details.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

</main>


<?php get_footer(); ?>