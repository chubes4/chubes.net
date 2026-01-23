<?php 
/**
 * Generic Archive Template
 * 
 * Single source of truth for all archive pages. Uses WordPress native
 * the_archive_title() and the_archive_description() with filter customization.
 * CPT-specific content can override default loop via chubes_archive_content filter.
 */
get_header(); ?>

<main class="site-main archive-<?php echo get_post_type() ?: 'blog'; ?>">
    <section class="archive-header">
        <div class="container">
            <div class="archive-header-inner">
                <?php the_archive_title('<h1>', '</h1>'); ?>
                <?php if ( apply_filters( 'chubes_show_archive_description', true ) ) : ?>
                    <?php the_archive_description('<p>', '</p>'); ?>
                <?php endif; ?>
                <?php do_action('chubes_archive_header_after'); ?>
                <div class="header-accent"></div>
            </div>
        </div>
    </section>

    <section class="archive-posts">
        <div class="container">
            <?php 
            $custom_content = apply_filters( 'chubes_archive_content', '', get_queried_object() );
            
            if ( $custom_content ) : 
                echo $custom_content;
            else : 
            ?>
                <div class="post-grid">

                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                        <div class="post-item">
                            <a href="<?php the_permalink(); ?>">
                                <div class="post-content-preview">
                                    <h3><?php the_title(); ?></h3>
                                    <p><?php echo get_the_excerpt(); ?></p>
                                    <div class="post-meta">
                                        <span class="post-date"><?php echo get_the_date(); ?></span>
                                        <span class="read-more">Read More</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; else : ?>
                        <p>No posts found.</p>
                    <?php endif; ?>
                </div>

                <div class="pagination">
                    <?php
                    echo paginate_links(array(
                        'prev_text' => '&larr; Previous',
                        'next_text' => 'Next &rarr;',
                    ));
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?> 