<?php 
/**
 * Documentation Category Archive Template
 * 
 * Displays documentation cards for all projects within a category (plugins, themes, apps, tools).
 * Handles URLs like /docs/plugins/, /docs/themes/, etc.
 * Shows cards linking to individual project documentation archives.
 */
get_header(); 

// Get the category type from global set by rewrite rules
global $chubes_docs_category_type;
$category_type = $chubes_docs_category_type ?? 'plugins'; // Default fallback

// Configure display based on category type
$config = [
    'plugins' => [
        'title' => 'Plugin Documentation',
        'description' => 'Comprehensive guides and documentation for WordPress plugins that streamline workflows and unlock creative possibilities.',
        'singular' => 'Plugin'
    ],
    'themes' => [
        'title' => 'Theme Documentation', 
        'description' => 'Complete guides and customization documentation for WordPress themes and design frameworks.',
        'singular' => 'Theme'
    ],
    'apps' => [
        'title' => 'Application Documentation',
        'description' => 'User guides and technical documentation for web applications and software tools.',
        'singular' => 'App'
    ],
    'tools' => [
        'title' => 'Tool Documentation',
        'description' => 'Documentation for command-line tools, utilities, and development scripts.',
        'singular' => 'Tool'
    ]
];

$current_config = $config[$category_type] ?? $config['plugins'];
?>

<main class="site-main">
    <!-- Documentation Category Header -->
    <section class="docs-category-header">
        <div class="container">
            <h1><?php echo esc_html($current_config['title']); ?></h1>
            <p><?php echo esc_html($current_config['description']); ?></p>
        </div>
    </section>

    <!-- Documentation Project Cards Grid -->
    <?php
    // Get the parent term for this category
    $parent_term = get_term_by('slug', $category_type, 'codebase');
    $project_terms = [];
    
    if ($parent_term && !is_wp_error($parent_term)) {
        $project_terms = get_terms(array(
            'taxonomy'   => 'codebase',
            'hide_empty' => false,
            'parent'     => $parent_term->term_id, // Get child projects only
            'orderby'    => 'name',
            'order'      => 'ASC'
        ));
    }
    
    if (!empty($project_terms) && !is_wp_error($project_terms)) : ?>
        <section class="docs-category-cards-section">
            <div class="container">
                <div class="docs-category-cards-grid">
                    <?php 
                    foreach ($project_terms as $term) : 
                        // Get repository information for this project
                        $repo_info = chubes_get_repository_info($term);
                        $doc_count = $repo_info['content_counts']['documentation'] ?? 0;
                        
                        // Only show projects that have documentation
                        if ($doc_count > 0) : ?>
                            <div class="docs-category-card">
                                <div class="card-header">
                                    <h3><?php echo esc_html($term->name); ?></h3>
                                    <?php if ($term->description): ?>
                                        <p class="card-description"><?php echo esc_html(wp_trim_words($term->description, 20)); ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-stats">
                                    <span class="stat-item"><?php echo $doc_count; ?> guide<?php echo $doc_count !== 1 ? 's' : ''; ?></span>
                                    <?php if ($repo_info['installs'] > 0): ?>
                                        <span class="stat-item"><?php echo number_format($repo_info['installs']); ?> downloads</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-actions">
                                    <a href="<?php echo esc_url(home_url('/docs/' . $category_type . '/' . $term->slug . '/')); ?>" class="btn primary">
                                        View Documentation →
                                    </a>
                                    
                                    <div class="external-links">
                                        <?php if ($repo_info['wp_url']): ?>
                                            <a href="<?php echo esc_url($repo_info['wp_url']); ?>" class="external-link" target="_blank" title="Download from WordPress.org">
                                                <svg><use href="<?php echo get_template_directory_uri(); ?>/assets/fonts/social-icons.svg#icon-wordpress"></use></svg>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($repo_info['github_url']): ?>
                                            <a href="<?php echo esc_url($repo_info['github_url']); ?>" class="external-link" target="_blank" title="View on GitHub">
                                                <svg><use href="<?php echo get_template_directory_uri(); ?>/assets/fonts/social-icons.svg#icon-github"></use></svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php else : ?>
        <section class="no-docs-category">
            <div class="container">
                <div class="no-content">
                    <p><?php echo esc_html($current_config['title']); ?> will be available here soon. Check back for new guides and documentation.</p>
                </div>
            </div>
        </section>
    <?php endif; ?>

</main>


<?php get_footer(); ?>