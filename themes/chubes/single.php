<?php get_header(); ?>

<main class="site-main single-post">
    <section class="post-content">
        <div class="container">
            <!-- Post Title -->
            <h1><?php the_title(); ?></h1>

            <!-- Post Meta -->
            <div class="post-meta">
                <p>Published on <?php echo get_the_date(); ?></p>
            </div>

            <!-- Featured Image -->
            <?php if (has_post_thumbnail()) : ?>
                <div class="post-image">
                    <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title(); ?>">
                </div>
            <?php endif; ?>

            <!-- Post Content -->
            <div class="post-body">
                <?php the_content(); ?>
            </div>

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
