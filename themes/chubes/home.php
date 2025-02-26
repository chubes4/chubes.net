<?php get_header(); ?>

<main class="site-main archive-blog">
    <section class="archive-header">
        <div class="container">
            <h1>Blog</h1>
            <p>Digital entrepreneurship, WordPress development, AI tools, automation. No courses. No fluff. Just straight facts.</p>
        </div>
    </section>

    <section class="archive-posts">
        <div class="container">
            <div class="post-grid">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="post-item">
                        <a href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                            <h3><?php the_title(); ?></h3>
                            <p class="post-date"><?php echo get_the_date(); ?></p> <!-- Added date here -->
                            <p><?php echo get_the_excerpt(); ?></p>
                        </a>
                    </div>
                <?php endwhile; else : ?>
                    <p>No posts found.</p>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php 
                echo paginate_links(array(
                    'prev_text' => '← Previous',
                    'next_text' => 'Next →',
                )); 
                ?>
            </div>
                        <!-- Back to Homepage -->
            <div class="post-navigation">
                <a href="<?php echo home_url(); ?>" class="btn secondary">
                    ← Back to Chubes.net
                </a>
            </div>

        </div>
    </section>
</main>

<?php get_footer(); ?>
