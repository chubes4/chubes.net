<?php 
/**
 * Homepage Template - Hero and Latest Content
 * 
 * Part of organized template hierarchy in /templates/ directory.
 * Displays hero section and three-column latest content (docs/blog/journal).
 * Loads home.css via conditional asset loading in functions.php.
 */
get_header(); ?>

<main class="site-main">
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Independent Developer & Music Journalist</h1>
            <p>I build Extra Chill, a music blog I started in college that has grown into a respected journalistic platform. I also develop WordPress plugins, web applications, and tools that integrate AI across different systems.</p>
            <a href="/contact" class="btn">Get In Touch</a>
        </div>
    </section>

    <!-- Three-Column Content Section -->
    <section class="docs-blog-journal">
        <div class="container">
            <h2>Explore the World of Chubes</h2>
            <div class="content-grid">
                <!-- Codebase Documentation List -->
                <div class="docs-list">
                    <h3>Codebase Documentation</h3>
                    <ul class="item-list">
                    <?php $doc_items = chubes_get_homepage_documentation_items(); ?>
                    <?php if (!empty($doc_items)) : ?>
                        <?php foreach ($doc_items as $item) : ?>
                            <li>
                                <a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['name']); ?></a>
                                <div class="meta">
                                    <small><?php echo esc_html($item['type']); ?> &bull; <?php echo (int) $item['count']; ?> doc<?php echo ((int) $item['count'] > 1) ? 's' : ''; ?></small>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <li>Documentation will be available soon.</li>
                    <?php endif; ?>
                    </ul>
                    <div class="list-cta">
                        <a class="btn secondary" href="<?php echo esc_url( get_post_type_archive_link('documentation') ); ?>">View all Docs</a>
                    </div>
                </div>

                <!-- Blog Posts List -->
                <div class="blog-list">
                    <h3>Blog</h3>
                    <ul class="item-list">
                    <?php
                    $blog_query = new WP_Query(array(
                        'post_type'      => 'post',
                        'posts_per_page' => 3,
                        'post_status'    => 'publish'
                    ));
                    if ($blog_query->have_posts()) :
                        while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
                            <li>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <div class="meta"><small><?php echo get_the_date(); ?></small></div>
                            </li>
                        <?php endwhile; wp_reset_postdata();
                    else : ?>
                        <li>No blog posts yet.</li>
                    <?php endif; ?>
                    </ul>
                    <div class="list-cta">
                        <?php $blog_link = get_permalink( get_option('page_for_posts') ); ?>
                        <a class="btn secondary" href="<?php echo esc_url( $blog_link ? $blog_link : home_url('/blog') ); ?>">View all Posts</a>
                    </div>
                </div>

                <!-- Journal Entries List -->
                <div class="journal-list">
                    <h3>Journal</h3>
                    <ul class="item-list">
                    <?php
                    $journal_query = new WP_Query(array(
                        'post_type'      => 'journal',
                        'posts_per_page' => 3,
                        'post_status'    => 'publish'
                    ));
                    if ($journal_query->have_posts()) :
                        while ($journal_query->have_posts()) : $journal_query->the_post(); ?>
                            <li>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <div class="meta"><small><?php echo get_the_date(); ?></small></div>
                            </li>
                        <?php endwhile; wp_reset_postdata();
                    else : ?>
                        <li>No journal entries yet.</li>
                    <?php endif; ?>
                    </ul>
                    <div class="list-cta">
                        <a class="btn secondary" href="<?php echo esc_url( get_post_type_archive_link('journal') ); ?>">View all Journal</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php 
    // Display custom content section if enabled
    $custom_content_enabled = get_theme_mod('chubes_homepage_custom_content_enabled', false);
    $custom_content = get_theme_mod('chubes_homepage_custom_content', '');
    
    if ($custom_content_enabled && !empty($custom_content)) : ?>
    <!-- Custom Content Section -->
    <section class="custom-content-section">
        <div class="container">
            <?php echo do_blocks(wp_kses_post($custom_content)); ?>
        </div>
    </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
