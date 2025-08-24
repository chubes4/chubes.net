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