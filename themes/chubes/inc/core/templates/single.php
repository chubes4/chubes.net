<?php 
/**
 * Universal Single Post Template
 * 
 * Part of organized template hierarchy in /templates/single/ directory.
 * Displays all single posts with filterable meta and action hooks for
 * plugin extensibility. Supports blog posts, documentation, journal, etc.
 */

$post_type = get_post_type();
$meta_label = apply_filters('chubes_single_meta_label', 'Published on', $post_type);
$meta_date = apply_filters('chubes_single_meta_date', get_the_date(), get_the_ID(), $post_type);

get_header(); ?>

<main class="site-main single-<?php echo esc_attr($post_type); ?>">
    <section class="post-content">
        <div class="container">
            <!-- Post Title -->
            <h1><?php the_title(); ?></h1>

            <!-- Post Meta -->
            <div class="post-meta">
                <p class="post-date"><?php echo esc_html($meta_label); ?> <?php echo esc_html($meta_date); ?></p>
            </div>

            <!-- Featured Image -->
            <?php if (has_post_thumbnail()) : ?>
                <div class="post-image">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>

            <!-- Post Content -->
            <div class="post-body">
                <?php the_content(); ?>
            </div>

            <?php do_action('chubes_single_after_content', get_the_ID(), $post_type); ?>


        </div>
    </section>
</main>

<?php get_footer(); ?>
