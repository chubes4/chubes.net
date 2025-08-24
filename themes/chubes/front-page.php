<?php get_header(); ?>

<main class="site-main">
    <!-- Hero Section -->
    <section class="hero reveal">
        <div class="container">
            <h1>WordPress Developer & Music Journalist</h1>
            <p>Former sailboat captain turned creative entrepreneur. I build WordPress automation systems, scale content workflows, and merge technology with music journalism through Extra Chill.</p>
            <div class="contact-button">
                <a href="/portfolio" class="btn">Explore My Work</a>
            </div>       
        </div>
    </section>

    <!-- Featured Projects Section -->
    <section class="featured-projects reveal">
    <div class="container">
        <h2>What I Create</h2>

        <div class="project-category reveal">
            <h3>WordPress Automation Systems</h3>
            <p>My plugins are designed to make automation easy. I specialize in building tools that empower editors, publishers, and small business owners to automate repetitive tasks, streamline workflows, and unlock new creative possibilities within WordPress.</p>
            <a href="/plugins" class="btn secondary">Browse Plugins</a>
        </div>

        <div class="project-category reveal">
            <h3>Extra Chill Music Platform</h3>
            <p>Founded in 2011, Extra Chill is my passion project that has grown into a respected journalistic platform. Through Extra Chill, I've not only taught myself WordPress development, but also scaled the platform to 300k+ monthly visitors through strategic SEO and smart automation.</p>
            <a href="https://extrachill.com" class="btn secondary" target="_blank">Visit Extra Chill</a>
        </div>

        <div class="project-category reveal">
            <h3>Websites, Bots, and Social Media</h3>
            <p>I create unique websites with innovative features for both clients and myself. From experimental content automation, to Discord bots and Chrome Extensions. If I have an idea, I'll build it. Check out my portfolio for a curated selection of my work.</p>
            <a href="/portfolio" class="btn secondary">View Portfolio</a>
        </div>
    </div>
</section>

    <!-- Docs + Blog Two-Column Section -->
    <section class="docs-blog reveal">
        <div class="container">
            <h2>Latest Docs & Articles</h2>
            <div class="docs-blog-grid">
                <!-- Plugin Documentation List -->
                <div class="docs-list">
                    <h3>Plugin Documentation</h3>
                    <ul class="item-list">
                    <?php
                    $docs_query = new WP_Query(array(
                        'post_type'      => 'documentation',
                        'posts_per_page' => 5,
                        'post_status'    => 'publish'
                    ));
                    if ($docs_query->have_posts()) :
                        while ($docs_query->have_posts()) : $docs_query->the_post(); ?>
                            <li>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <div class="meta"><small><?php echo get_the_date(); ?></small></div>
                            </li>
                        <?php endwhile; wp_reset_postdata();
                    else : ?>
                        <li>No documentation yet.</li>
                    <?php endif; ?>
                    </ul>
                    <div class="list-cta">
                        <a class="btn secondary" href="<?php echo esc_url( get_post_type_archive_link('documentation') ); ?>">View all Docs</a>
                    </div>
                </div>

                <!-- Blog Posts List -->
                <div class="blog-list">
                    <h3>From the Blog</h3>
                    <ul class="item-list">
                    <?php
                    $blog_query = new WP_Query(array(
                        'post_type'      => 'post',
                        'posts_per_page' => 5,
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
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
