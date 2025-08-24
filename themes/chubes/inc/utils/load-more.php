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
        while ($portfolio_query->have_posts()) : $portfolio_query->the_post();
            get_template_part('template-parts/portfolio-item');
        endwhile;
        wp_reset_postdata();
    endif;

    // Send flag to hide button via data attribute
    if ($paged >= $total_pages) {
        echo '<div data-hide-load-more="true" style="display:none;"></div>';
    }

    die();
}

add_action('wp_ajax_load_more_portfolio', 'load_more_portfolio');
add_action('wp_ajax_nopriv_load_more_portfolio', 'load_more_portfolio');

