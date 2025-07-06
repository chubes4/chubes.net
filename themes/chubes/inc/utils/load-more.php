<?php
function load_more_portfolio() {
    $paged = isset($_GET['page']) ? $_GET['page'] : 1;

    $args = array(
        'post_type'      => 'portfolio',
        'posts_per_page' => 10,  // Load 10 more each time
        'paged'          => $paged,
        'orderby'        => 'menu_order',
        'order'          => 'ASC'
    );

    $portfolio_query = new WP_Query($args);
    $total_pages = $portfolio_query->max_num_pages;

    if ($portfolio_query->have_posts()) :
        while ($portfolio_query->have_posts()) : $portfolio_query->the_post(); ?>
            <div class="portfolio-item">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>">
                    <?php endif; ?>
                    <div class="portfolio-overlay">
                        <h3><?php the_title(); ?></h3>
                        <p><?php the_excerpt(); ?></p>
                    </div>
                </a>
            </div>
        <?php endwhile;
        wp_reset_postdata();
    endif;

    if ($paged >= $total_pages) {
        echo '<script>document.getElementById("load-more").style.display = "none";</script>';
    }

    die();
}

add_action('wp_ajax_load_more_portfolio', 'load_more_portfolio');
add_action('wp_ajax_nopriv_load_more_portfolio', 'load_more_portfolio');

