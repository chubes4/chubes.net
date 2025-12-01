<?php 
/**
 * Search Results Template
 * 
 * Part of organized template hierarchy in /templates/archive/ directory.
 * Displays search results with context-specific headers and descriptions.
 * Uses chubes_get_parent_page() for navigation and reuses existing archive styling.
 */
get_header(); ?>

<main class="site-main archive-blog">
    <section class="archive-header enhanced">
        <div class="container">
            <div class="archive-header-inner">
                <?php
                $search_query = get_search_query();
                if ($search_query) {
                    echo '<h1><span class="archive-type">Search Results for</span>' . esc_html($search_query) . '</h1>';
                    
                    global $wp_query;
                    $results_count = $wp_query->found_posts;
                    
                    if ($results_count > 0) {
                        echo '<p>Found ' . $results_count . ' result' . ($results_count !== 1 ? 's' : '') . ' for "' . esc_html($search_query) . '"</p>';
                    } else {
                        echo '<p>No results found for "' . esc_html($search_query) . '". Try searching for something else.</p>';
                    }
                } else {
                    echo '<h1>Search</h1>';
                    echo '<p>Enter a search term to find content on the site</p>';
                }
                ?>
                <div class="header-accent"></div>
            </div>
        </div>
    </section>

    <section class="archive-posts">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="post-grid enhanced">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="post-item">
                            <a href="<?php the_permalink(); ?>">
                                <div class="post-content-preview">
                                    <h3><?php the_title(); ?></h3>
                                    <?php
                                    // Show post type for better context in search results
                                    $post_type_obj = get_post_type_object(get_post_type());
                                    if ($post_type_obj && $post_type_obj->public) {
                                        echo '<div class="post-type-label">' . esc_html($post_type_obj->labels->singular_name) . '</div>';
                                    }
                                    ?>
                                    <p><?php echo get_the_excerpt(); ?></p>
                                    <div class="post-meta">
                                        <span class="post-date"><?php echo get_the_date(); ?></span>
                                        <span class="read-more">Read More</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
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
            <?php else : ?>
                <div class="no-results">
                    <?php if ($search_query) : ?>
                        <h2>No Results Found</h2>
                        <p>Sorry, no content matched your search criteria. Here are some suggestions:</p>
                        <ul>
                            <li>Try different keywords or phrases</li>
                            <li>Check your spelling</li>
                            <li>Use more general terms</li>
                            <li>Search for individual words rather than phrases</li>
                        </ul>
                        
                        <!-- Search again form -->
                        <div class="search-again">
                            <h3>Search Again</h3>
                            <form class="search-form-inline" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                                <input type="search" class="search-input-inline" placeholder="Try another search..." value="" name="s" />
                                <button type="submit" class="search-submit-inline">Search</button>
                            </form>
                        </div>
                    <?php else : ?>
                        <h2>Search</h2>
                        <p>Use the form below to search for content on the site:</p>
                        <form class="search-form-inline" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                            <input type="search" class="search-input-inline" placeholder="Search..." value="" name="s" />
                            <button type="submit" class="search-submit-inline">Search</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Dynamic Back To Navigation -->

        </div>
    </section>
</main>

<?php get_footer(); ?>