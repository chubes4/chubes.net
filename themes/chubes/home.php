<?php get_header(); ?>

<main class="site-main archive-blog">
    <section class="archive-header enhanced">
        <div class="container">
            <div class="archive-header-inner">
                <?php
                // Check if this is the blog index or another specific view
                if (is_home() && !is_front_page()) {
                    // This is the blog posts index
                    echo '<h1>Blog</h1>';
                    echo '<p>Digital entrepreneurship, WordPress development, SEO, AI tools, automation.</p>';
                } elseif (is_search()) {
                    echo '<h1><span class="archive-type">Search Results</span>' . get_search_query() . '</h1>';
                    echo '<p>Showing results for your search query</p>';
                } else {
                    // Fallback for any other archive-like pages
                    the_archive_title('<h1>', '</h1>');
                    the_archive_description('<p>', '</p>');
                }
                ?>
                <div class="header-accent"></div>
            </div>
        </div>
    </section>

    <section class="archive-posts">
        <div class="container">
            <div class="post-grid enhanced">
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

            <!-- Pagination -->
            <div class="pagination">
                <?php 
                echo paginate_links(array(
                    'prev_text' => '← Previous',
                    'next_text' => 'Next →',
                )); 
                ?>
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
