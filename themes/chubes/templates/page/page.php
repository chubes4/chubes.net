<?php 
/**
 * Default Page Template
 * 
 * Part of organized template hierarchy in /templates/page/ directory.
 * Displays static pages with optional featured image and context-aware
 * navigation using chubes_get_parent_page() from functions.php.
 */
get_header(); ?>

<main class="site-main single-post">
    <section class="post-content">
        <div class="container">
            <!-- Post Title -->
            <h1><?php the_title(); ?></h1>

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
