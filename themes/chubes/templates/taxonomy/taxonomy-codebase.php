<?php 
/**
 * Individual Codebase Project Taxonomy Page Template
 * 
 * Unified taxonomy template for all codebase projects (plugins, themes, apps, tools).
 * Replaces the previous separate taxonomy-plugin.php and taxonomy-theme.php templates.
 * Displays individual project pages with download statistics, GitHub/WordPress.org links, 
 * and associated documentation. Uses automatic project type detection.
 */
get_header(); 

$queried_object = get_queried_object();

// Get repository information for this project
$repo_info = chubes_get_repository_info($queried_object);
$project_type = $repo_info['project_type'] ?? 'project';

// Configure display based on project type
$type_config = [
    'plugin' => [
        'type_name' => 'Plugin',
        'download_text' => 'Download Plugin'
    ],
    'theme' => [
        'type_name' => 'Theme', 
        'download_text' => 'Download Theme'
    ],
    'app' => [
        'type_name' => 'App',
        'download_text' => 'Launch App'
    ],
    'tool' => [
        'type_name' => 'Tool',
        'download_text' => 'Download Tool'
    ]
];

$current_config = $type_config[$project_type] ?? $type_config['plugin'];
?>

<main class="site-main">
    <!-- Project Header Section -->
    <section class="project-header">
        <div class="container">
            <h1><?php echo esc_html($queried_object->name); ?></h1>
            
            <?php if ($queried_object->description): ?>
                <div class="project-description">
                    <p><?php echo wp_kses_post(nl2br($queried_object->description)); ?></p>
                </div>
            <?php endif; ?>
            
            <div class="project-stats">
                <?php if ($repo_info['installs'] > 0): ?>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo number_format($repo_info['installs']); ?></span>
                        <span class="stat-label">Downloads</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="project-actions">
                <?php if ($repo_info['wp_url']): ?>
                    <a href="<?php echo esc_url($repo_info['wp_url']); ?>" class="btn primary" target="_blank">
                        <svg class="btn-icon"><use href="<?php echo get_template_directory_uri(); ?>/assets/fonts/social-icons.svg#icon-wordpress"></use></svg>
                        <?php echo esc_html($current_config['download_text']); ?>
                    </a>
                <?php endif; ?>
                
                <?php if ($repo_info['github_url']): ?>
                    <a href="<?php echo esc_url($repo_info['github_url']); ?>" class="btn secondary" target="_blank">
                        <svg class="btn-icon"><use href="<?php echo get_template_directory_uri(); ?>/assets/fonts/social-icons.svg#icon-github"></use></svg>
                        View on GitHub
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Project Documentation Section -->
    <section class="project-documentation">
        <div class="container">
            <h2>Documentation</h2>
            
            <?php
            $docs_query = new WP_Query(array(
                'post_type' => 'documentation',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'codebase',
                        'field'    => 'term_id',
                        'terms'    => $queried_object->term_id,
                    ),
                ),
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'menu_order',
                'order'          => 'ASC'
            ));
            
            if ($docs_query->have_posts()) : ?>
                <div class="documentation-cards">
                    <?php while ($docs_query->have_posts()) : $docs_query->the_post(); ?>
                        <a href="<?php the_permalink(); ?>" class="documentation-card">
                            <div class="card-header">
                                <h3><?php the_title(); ?></h3>
                                <?php if (has_excerpt()) : ?>
                                    <p class="card-description"><?php the_excerpt(); ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="card-stats">
                                <span class="stat-item">Last updated: <?php echo get_the_modified_date(); ?></span>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="no-docs">
                    <p>Documentation for this <?php echo strtolower($current_config['type_name']); ?> is coming soon.</p>
                    <?php if ($repo_info['github_url']): ?>
                        <p>In the meantime, check out the <a href="<?php echo esc_url($repo_info['github_url']); ?>" target="_blank">GitHub repository</a> for technical details.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>


    <!-- Journal Section (if any journal entries exist) -->
    <?php if ($repo_info['content_counts']['journal'] > 0) : 
        $journal_query = new WP_Query(array(
            'post_type' => 'journal',
            'tax_query' => array(
                array(
                    'taxonomy' => 'codebase',
                    'field'    => 'term_id',
                    'terms'    => $queried_object->term_id,
                ),
            ),
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC'
        ));
        
        if ($journal_query->have_posts()) : ?>
            <section class="project-journal">
                <div class="container">
                    <h2>Development Journal</h2>
                    <div class="journal-list">
                        <?php while ($journal_query->have_posts()) : $journal_query->the_post(); ?>
                            <article class="journal-item">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <div class="journal-meta">
                                    <span class="journal-date"><?php echo get_the_date(); ?></span>
                                </div>
                                <?php if (has_excerpt()) : ?>
                                    <p class="journal-excerpt"><?php the_excerpt(); ?></p>
                                <?php endif; ?>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
            <?php wp_reset_postdata(); ?>
        <?php endif;
    endif; ?>

</main>


<?php get_footer(); ?>