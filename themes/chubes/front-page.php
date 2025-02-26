<?php get_header(); ?>

<main class="site-main">
    <section class="hero reveal">
        <div class="container">
            <h1>Websites Built for Speed, SEO, & Growth</h1>
            <p>I design and develop high-performance websites for businesses, brands, and creatives—built for speed, SEO, and conversions.</p>
            <p><strong>Hit me up: </strong><a href="mailto:chubes@chubes.net">chubes@chubes.net</a></p>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services reveal">
    <div class="container">
        <h2>What I Do</h2>

        <div class="service-category reveal">
            <h3>Web Development</h3>
            <p>Custom or template-based websites that load fast, rank high, and drive revenue.</p>
        </div>

        <div class="service-category reveal">
            <h3>SEO & Performance</h3>
            <p>Higher rankings, faster load times, and optimized content that gets results.</p>
        </div>

        <div class="service-category reveal">
            <h3>Content & Lead Generation</h3>
            <p>Smart keyword strategy and conversion-driven content to bring in the right audience.</p>
        </div>

        <div class="service-category reveal">
            <h3>Automation & AI</h3>
            <p>Save time with automated workflows, AI tools, and API integrations.</p>
        </div>
        <div class="services-button">
            <a href="/services" class="btn">View Services</a>
        </div>
    </div>
</section>



    <section class="portfolio reveal">
    <div class="container">
        <h2>Featured Work</h2>
        <div class="portfolio-grid">
            <?php
            $portfolio = new WP_Query(array(
                'post_type'      => 'portfolio',
                'posts_per_page' => 6,
                'orderby'        => 'menu_order', 
                'order'          => 'ASC'
            ));

            if ($portfolio->have_posts()) :
                while ($portfolio->have_posts()) : $portfolio->the_post(); ?>
                    <div class="portfolio-item reveal">
                        <a href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                            <div class="portfolio-overlay">
                                <h3><?php the_title(); ?></h3>
                                <p><?php the_excerpt(); ?></p>
                            </div>
                        </a>
                    </div>
            <?php endwhile;
            wp_reset_postdata();
            endif; ?>
        </div>

        <!-- View Full Portfolio Button -->
        <div class="portfolio-button">
            <a href="/portfolio" class="btn">View Full Portfolio</a>
        </div>
    </div>
</section>


    <!-- Tech Stack Section -->
    <section class="tech-stack">
    <div class="container">
        <h2 class="reveal">Under the Hood</h2>
        <p>Whether you need a simple site or complex integrations, I have the technical skills to deliver.</p>
        <div class="reveal">
            <h3>Web Development & CMS</h3>
            <ul>
                <li>WordPress, WooCommerce, BBPress, The Events Calendar</li>
                <li>Shopify, Dropshipping (Printful, POD solutions)</li>
                <li>WordPress REST API & External API Integrations</li>
            </ul>
        </div>

        <div class="reveal">
            <h3>SEO & Performance</h3>
            <ul>
                <li>SEMrush, Ahrefs, LowFruits</li>
                <li>Google Search Console, PageSpeed Insights</li>
                <li>Keyword Research & SERP Analysis</li>
                <li>Technical SEO & Site Audits</li>
            </ul>
        </div>

        <div class="reveal">
            <h3>API Integrations & Social Media</h3>
            <ul>
                <li><strong>Social APIs:</strong> Twitter, Facebook, Instagram, Pinterest, Tumblr, DeviantArt</li>
                <li><strong>Event Platforms:</strong> Ticketmaster, Eventbrite, DICE.FM</li>
                <li><strong>AI APIs:</strong> ChatGPT, Stable Diffusion</li>
                <li><strong>Unique Data:</strong> Custom Web Scraping</li>
            </ul>
        </div>

        <div class="reveal">
            <h3>Email Marketing & Automation</h3>
            <ul>
                <li>Sendy (AWS-powered email marketing)</li>
                <li>Mailchimp & Email List Growth Strategies</li>
                <li>Newsletter Automation</li>
            </ul>
        </div>
    </div>
</section>



</main>

<?php get_footer(); ?>
