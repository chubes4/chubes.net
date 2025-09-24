<?php 
/**
 * Journal Archive Template
 * 
 * Part of organized template hierarchy in /templates/archive/ directory.
 * Displays journal custom post type entries in a simple list format
 * with context-aware navigation using chubes_get_parent_page().
 */
get_header(); ?>

<main class="site-main archive-journal">
    <section class="archive-header enhanced">
        <div class="container">
            <div class="archive-header-inner">
                <h1>Journal</h1>
                <p>Personal reflections and raw thoughts about my journey as an entrepreneur.</p>
                <div class="header-accent"></div>
            </div>
        </div>
    </section>

    <section class="archive-posts">
        <div class="container">
            <ul class="journal-list enhanced">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <li>
                        <a href="<?php the_permalink(); ?>">
                            <span class="journal-title"><?php the_title(); ?></span>
                            <span class="journal-date"><?php echo get_the_date(); ?></span>
                        </a>
                    </li>
                <?php endwhile; else : ?>
                    <p>No journal entries found.</p>
                <?php endif; ?>
            </ul>

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
