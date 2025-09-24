<?php 
/**
 * Documentation Archive Template with Plugin Taxonomy Filtering
 * 
 * Part of organized template hierarchy in /templates/archive/ directory.
 * Displays documentation custom post type with plugin taxonomy filtering.
 * Features dropdown filter for plugin categories and context-aware navigation.
 */
get_header(); ?>

<main class="site-main">
    <!-- Documentation Archive Header -->
    <section class="archive-header">
        <div class="container">
            <h1>Documentation</h1>
            <p>Technical documentation and guides for all projects.</p>
        </div>
    </section>

    <!-- Documentation Grid -->
    <section class="documentation-archive">
        <div class="container">
            <?php
            // Check if we're on a filtered taxonomy page
            $is_filtered = is_tax('codebase');
            
            if (!$is_filtered) :
                // Dynamic documentation categories - automatically includes any codebase category with documentation
                
                // No hardcoded category config - use dynamic taxonomy term data
                
                // Get all parent categories in codebase taxonomy
                $parent_categories = get_terms(array(
                    'taxonomy' => 'codebase',
                    'hide_empty' => true,
                    'parent' => 0, // Top-level categories only
                    'orderby' => 'name',
                    'order' => 'ASC'
                ));
                
                if ($parent_categories && !is_wp_error($parent_categories)) :
                    foreach ($parent_categories as $parent_category) :
                        // Get project terms under this category
                        $project_terms = get_terms(array(
                            'taxonomy' => 'codebase',
                            'hide_empty' => true,
                            'parent' => $parent_category->term_id,
                            'orderby' => 'name',
                            'order' => 'ASC'
                        ));
                        
                        // Check if any projects in this category have documentation
                        $has_documentation = false;
                        $projects_with_docs = [];
                        
                        if ($project_terms && !is_wp_error($project_terms)) {
                            foreach ($project_terms as $term) {
                                $repo_info = chubes_get_repository_info($term);
                                $doc_count = $repo_info['content_counts']['documentation'] ?? 0;
                                
                                if ($doc_count > 0) {
                                    $has_documentation = true;
                                    $projects_with_docs[] = [
                                        'term' => $term,
                                        'repo_info' => $repo_info,
                                        'doc_count' => $doc_count
                                    ];
                                }
                            }
                        }
                        
                        // Only display this category if it has projects with documentation
                        if ($has_documentation) : ?>
                            <!-- <?php echo ucfirst($parent_category->slug); ?> Documentation Section -->
                            <section class="documentation-category-section">
                                <div class="category-header">
                                    <h2><?php echo esc_html(ucfirst($parent_category->name)); ?> Documentation</h2>
                                    <?php if ($parent_category->description): ?>
                                        <p><?php echo esc_html($parent_category->description); ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="documentation-cards">
                                    <?php foreach ($projects_with_docs as $project) : ?>
                                        <div class="documentation-card">
                                            <div class="card-header">
                                                <h3><?php echo esc_html($project['term']->name); ?></h3>
                                                <?php if ($project['term']->description): ?>
                                                    <p class="card-description"><?php echo esc_html(wp_trim_words($project['term']->description, 20)); ?></p>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="card-stats">
                                                <span class="stat-item"><?php echo $project['doc_count']; ?> guide<?php echo $project['doc_count'] !== 1 ? 's' : ''; ?></span>
                                                <?php if ($project['repo_info']['installs'] > 0): ?>
                                                    <span class="stat-item"><?php echo number_format($project['repo_info']['installs']); ?> downloads</span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="card-actions">
                                                <a href="<?php echo esc_url(home_url('/docs/' . $parent_category->slug . '/' . $project['term']->slug . '/')); ?>" class="btn primary">
                                                    View Documentation →
                                                </a>
                                                
                                                <div class="external-links">
                                                    <?php if ($project['repo_info']['wp_url']): ?>
                                                        <a href="<?php echo esc_url($project['repo_info']['wp_url']); ?>" class="external-link" target="_blank" title="Download from WordPress.org">
                                                            <svg><use href="<?php echo get_template_directory_uri(); ?>/assets/fonts/social-icons.svg#icon-wordpress"></use></svg>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($project['repo_info']['github_url']): ?>
                                                        <a href="<?php echo esc_url($project['repo_info']['github_url']); ?>" class="external-link" target="_blank" title="View on GitHub">
                                                            <svg><use href="<?php echo get_template_directory_uri(); ?>/assets/fonts/social-icons.svg#icon-github"></use></svg>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php 
                        endif;
                    endforeach;
                endif;
            endif; ?>

            
            <!-- Dynamic Back To Navigation -->
            <div class="post-navigation">
                <?php 
                $parent = chubes_get_parent_page();
                ?>
                <a href="<?php echo esc_url($parent['url']); ?>" class="btn secondary">
                    ← Back to <?php echo esc_html($parent['title']); ?>
                </a>
            </div>
        </div>
    </section>
</main>


<?php get_footer(); ?>