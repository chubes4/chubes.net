<?php 
/**
 * Single Documentation Template with Related Posts
 * 
 * Part of organized template hierarchy in /templates/single/ directory.
 * Displays documentation posts with hierarchical related posts system,
 * taxonomy-aware archive links, and context-aware navigation.
 */
get_header(); ?>

<main class="site-main single-documentation">
    <section class="documentation-content">
        <div class="container">
            <!-- Documentation Title -->
            <h1><?php the_title(); ?></h1>

            <!-- Documentation Meta -->
            <div class="documentation-meta">
                <?php
                // Display codebase project taxonomy
                $codebase_terms = get_the_terms(get_the_ID(), 'codebase');
                
                if ($codebase_terms && !is_wp_error($codebase_terms)) : ?>
                    <p class="doc-taxonomy">
                        <?php foreach ($codebase_terms as $term) : 
                            // Get project type and display appropriate project term
                            $project_type = chubes_get_codebase_project_type($term);
                            
                            // Find the actual project term (not the parent category)
                            $project_term = $term;
                            if (in_array($term->slug, ['plugins', 'themes', 'apps', 'tools'])) {
                                // This is a parent category, skip it
                                continue;
                            }
                            
                            // If this is a subcategory, find the project root
                            while ($project_term->parent != 0) {
                                $parent = get_term($project_term->parent, 'codebase');
                                // Stop if we reach a top-level category (plugins, themes, etc.)
                                if ($parent && !in_array($parent->slug, ['plugins', 'themes', 'apps', 'tools'])) {
                                    $project_term = $parent;
                                } else {
                                    break;
                                }
                            }
                            ?>
                            <a href="<?php echo esc_url(get_term_link($project_term)); ?>" class="taxonomy-link">
                                <?php echo esc_html($project_term->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </p>
                <?php endif; ?>
                
                <p class="doc-date">Last updated: <?php echo get_the_modified_date(); ?></p>
            </div>

            <!-- Featured Image -->
            <?php if (has_post_thumbnail()) : ?>
                <div class="documentation-image">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>

            <!-- Documentation Content -->
            <div class="documentation-body">
                <?php the_content(); ?>
            </div>

            <!-- Related Documentation Section -->
            <div class="related-documentation">
                <?php
                $related_posts = chubes_get_related_documentation(get_the_ID(), 3);
                $archive_link = chubes_get_documentation_archive_link(get_the_ID());
                ?>
                
                <h3>Related Documentation</h3>
                
                <!-- Archive Link -->
                <div class="docs-archive-link">
                    <a href="<?php echo esc_url($archive_link['url']); ?>" class="archive-btn">
                        ← Browse all <?php echo esc_html($archive_link['title']); ?>
                    </a>
                </div>
                
                <!-- Related Posts List -->
                <?php if (!empty($related_posts)) : ?>
                    <div class="related-posts-list">
                        <?php foreach ($related_posts as $related_post) : ?>
                            <article class="related-post-item">
                                <h4>
                                    <a href="<?php echo esc_url(get_permalink($related_post->ID)); ?>">
                                        <?php echo esc_html($related_post->post_title); ?>
                                    </a>
                                </h4>
                                <?php if ($related_post->post_excerpt) : ?>
                                    <p class="related-excerpt">
                                        <?php echo wp_trim_words($related_post->post_excerpt, 15); ?>
                                    </p>
                                <?php endif; ?>
                                <div class="related-meta">
                                    <?php
                                    // Show taxonomy context for related post
                                    $related_codebase_terms = get_the_terms($related_post->ID, 'codebase');
                                    
                                    if ($related_codebase_terms && !is_wp_error($related_codebase_terms)) {
                                        foreach ($related_codebase_terms as $related_term) {
                                            // Skip parent categories (plugins, themes, etc.)
                                            if (in_array($related_term->slug, ['plugins', 'themes', 'apps', 'tools'])) {
                                                continue;
                                            }
                                            
                                            // Find the project root term
                                            $related_project_term = $related_term;
                                            while ($related_project_term->parent != 0) {
                                                $parent = get_term($related_project_term->parent, 'codebase');
                                                if ($parent && !in_array($parent->slug, ['plugins', 'themes', 'apps', 'tools'])) {
                                                    $related_project_term = $parent;
                                                } else {
                                                    break;
                                                }
                                            }
                                            
                                            echo '<small>' . esc_html($related_project_term->name) . '</small>';
                                            break; // Only show first project
                                        }
                                    }
                                    ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="no-related-posts">
                        <p>No related documentation found. Browse the archive above to explore more content.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Dynamic Back To Navigation -->
            <div class="documentation-navigation">
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