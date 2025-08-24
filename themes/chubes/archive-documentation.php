<?php get_header(); ?>

<main class="site-main">
    <section class="documentation-header enhanced">
        <div class="container">
            <div class="archive-header-inner">
                <h1>Documentation</h1>
                <p>Comprehensive guides and documentation for all my WordPress plugins and tools.</p>
                <div class="header-accent"></div>
            </div>
        </div>
    </section>

    <section class="documentation archive-documentation">
        <div class="container">
            <?php
            // Get plugin taxonomy terms for filtering
            $plugin_terms = get_terms(array(
                'taxonomy' => 'plugin',
                'hide_empty' => true,
            ));
            
            if (!empty($plugin_terms) && !is_wp_error($plugin_terms)) : ?>
                <div class="documentation-filter">
                    <label for="plugin-filter"><?php esc_html_e('Filter by Plugin:', 'chubes'); ?></label>
                    <select id="plugin-filter" onchange="window.location.href=this.value">
                        <option value="<?php echo esc_url(get_post_type_archive_link('documentation')); ?>"><?php esc_html_e('All Documentation', 'chubes'); ?></option>
                        <?php foreach ($plugin_terms as $term) : ?>
                            <option value="<?php echo esc_url(get_term_link($term)); ?>" <?php selected(is_tax('plugin', $term->slug)); ?>>
                                <?php echo esc_html($term->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="documentation-grid">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="documentation-item">
                            <article class="documentation-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="documentation-thumbnail">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="documentation-content">
                                    <h2 class="documentation-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    
                                    <?php
                                    // Display plugin taxonomy terms
                                    $plugin_terms = get_the_terms(get_the_ID(), 'plugin');
                                    if ($plugin_terms && !is_wp_error($plugin_terms)) : ?>
                                        <div class="documentation-meta">
                                            <span class="plugin-label"><?php esc_html_e('Plugin:', 'chubes'); ?></span>
                                            <?php foreach ($plugin_terms as $term) : ?>
                                                <a href="<?php echo esc_url(get_term_link($term)); ?>" class="plugin-tag">
                                                    <?php echo esc_html($term->name); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="documentation-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    
                                    <a href="<?php the_permalink(); ?>" class="read-more-link">
                                        <?php esc_html_e('Read Documentation', 'chubes'); ?> →
                                    </a>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="no-documentation">
                        <h3><?php esc_html_e('No documentation found', 'chubes'); ?></h3>
                        <p><?php esc_html_e('Documentation will be available soon.', 'chubes'); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <?php
            // Pagination
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('← Previous', 'chubes'),
                'next_text' => __('Next →', 'chubes'),
            )); ?>
            
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