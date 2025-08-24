<?php get_header(); ?>

<main class="site-main">
    <section class="portfolio-header enhanced">
        <div class="container">
            <div class="archive-header-inner">
            <h1>My Developer Portfolio</h1>
            <p>Explore my collection of custom WordPress builds, high-performance solutions, and innovative marketing tools.</p>
                <div class="header-accent"></div>
            </div>
        </div>
    </section>

    <section class="portfolio archive-portfolio">
        <div class="container">
            <div class="portfolio-grid" id="portfolio-container">
                <?php
                $args = array(
                    'post_type'      => 'portfolio',
                    'posts_per_page' => 10,  // Load 10 initially
                    'paged'          => 1,  // AJAX pagination starts at page 1
                    'orderby'        => 'menu_order', // Sort by custom order
                    'order'          => 'ASC' // Ascending order
                );
                $portfolio_query = new WP_Query($args);

                if ($portfolio_query->have_posts()) :
                    while ($portfolio_query->have_posts()) : $portfolio_query->the_post();
                        get_template_part('template-parts/portfolio-item');
                    endwhile;
                    wp_reset_postdata();
                endif; ?>
            </div>

            <?php if ($portfolio_query->max_num_pages > 1) : ?>
                <!-- Load More Button (Only shows if there are more pages) -->
                <div class="load-more-container">
                    <div id="load-more" class="btn">Load More</div>
                </div>
            <?php endif; ?>
            
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
