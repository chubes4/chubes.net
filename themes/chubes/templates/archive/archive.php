<?php 
/**
 * Generic Archive Template with Dynamic Headers
 * 
 * Part of organized template hierarchy in /templates/archive/ directory.
 * Handles category, tag, author, and date archives with context-specific 
 * headers and descriptions. Uses chubes_get_parent_page() for navigation.
 */
get_header(); ?>

<main class="site-main archive-blog">
    <section class="archive-header enhanced">
        <div class="container">
            <div class="archive-header-inner">
                <?php
                // Dynamic archive titles based on context
                if (is_category()) {
                    echo '<h1><span class="archive-type">Category</span>' . single_cat_title('', false) . '</h1>';
                    // Show category description if it exists
                    $category_description = category_description();
                    if (!empty($category_description)) {
                        echo '<p>' . $category_description . '</p>';
                    } else {
                        echo '<p>Posts in the ' . single_cat_title('', false) . ' category</p>';
                    }
                } elseif (is_tag()) {
                    echo '<h1><span class="archive-type">Tag</span>' . single_tag_title('', false) . '</h1>';
                    // Show tag description if it exists
                    $tag_description = tag_description();
                    if (!empty($tag_description)) {
                        echo '<p>' . $tag_description . '</p>';
                    } else {
                        echo '<p>Posts tagged with ' . single_tag_title('', false) . '</p>';
                    }
                } elseif (is_author()) {
                    the_post();
                    echo '<h1><span class="archive-type">Author</span>' . get_the_author() . '</h1>';
                    echo '<p>Articles written by ' . get_the_author() . '</p>';
                    rewind_posts();
                } elseif (is_day()) {
                    echo '<h1><span class="archive-type">Daily Archives</span>' . get_the_date() . '</h1>';
                    echo '<p>All posts from ' . get_the_date() . '</p>';
                } elseif (is_month()) {
                    echo '<h1><span class="archive-type">Monthly Archives</span>' . get_the_date('F Y') . '</h1>';
                    echo '<p>All posts from ' . get_the_date('F Y') . '</p>';
                } elseif (is_year()) {
                    echo '<h1><span class="archive-type">Yearly Archives</span>' . get_the_date('Y') . '</h1>';
                    echo '<p>All posts from ' . get_the_date('Y') . '</p>';
                } else {
                    echo '<h1>Blog Archives</h1>';
                    echo '<p>Browse all archived articles</p>';
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