<?php get_header(); ?>

<main class="site-main">
    <section class="portfolio-header enhanced">
        <div class="container">
            <div class="archive-header-inner">
            <h1>My Digital Craftsmanship Portfolio</h1>
            <p>Explore custom WordPress builds, high-performance solutions, and innovative tools—crafted with focus and grit to deliver real results for small businesses.</p>
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
                    while ($portfolio_query->have_posts()) : $portfolio_query->the_post(); ?>
                        <div class="portfolio-item">
                            <a href="<?php the_permalink(); ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title(); ?>">
                                <?php endif; ?>
                                <div class="portfolio-overlay">
                                    <h3><?php the_title(); ?></h3>
                                    <p><?php the_excerpt(); ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endwhile;
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
