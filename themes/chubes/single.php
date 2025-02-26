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

            <!-- Back to Blog (Dynamic for Journal or Blog) -->
            <div class="post-navigation">
                <a href="<?php echo get_post_type_archive_link(get_post_type()); ?>" class="btn secondary">
                    ← Back to <?php echo get_post_type() == 'journal' ? 'Journal' : 'Blog'; ?>
                </a>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
