<?php get_header(); ?>

<main class="site-main archive-journal">
    <section class="archive-header">
        <div class="container">
            <h1>Journal</h1>
            <p>Personal reflections and raw thoughts about my life.</p>
        </div>
    </section>

    <section class="archive-posts">
        <div class="container">
            <ul class="journal-list">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <li>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <span class="journal-date"> - <?php echo get_the_date(); ?></span>
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

        </div>
    </section>
</main>

<?php get_footer(); ?>
