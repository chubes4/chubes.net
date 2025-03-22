<?php get_header(); ?>

<main class="site-main">
    <!-- Hero Section -->
    <section class="hero reveal">
        <div class="container">
            <h1>One-Man Digital Powerhouse for Small Business Growth</h1>
            <p>I build custom websites and digital solutions that work as hard as you do—no fluff, just results-driven features that help your business thrive online.</p>
            <div class="contact-button">
                <a href="/contact" class="btn">Let's Build Something Great</a>
            </div>        
        </div>
    </section>

    <!-- Services Section -->
    <section class="services reveal">
    <div class="container">
        <h2>My Services</h2>

        <a href="/services#web-development" class="service-category-link">
            <div class="service-category reveal">
                <h3>Small Business Web Development</h3>
                <p>Custom websites built with grit and purpose. Engineered with precision to drive real results for your small business.</p>
            </div>
        </a>

        <a href="/services#local-seo" class="service-category-link">
            <div class="service-category reveal">
                <h3>Local SEO & Performance</h3>
                <p>Turn your website into a local powerhouse. Data-driven optimization that brings customers to your door, proven by my own success stories.</p>
            </div>
        </a>

        <a href="/services#content-marketing" class="service-category-link">
            <div class="service-category reveal">
                <h3>Content & Marketing</h3>
                <p>Scale your content like I scaled Extra Chill to 300k monthly visitors. Strategic automation that works while you sleep.</p>
            </div>
        </a>

        <a href="/services#ai-automation" class="service-category-link">
            <div class="service-category reveal">
                <h3>AI & Automation</h3>
                <p>Transform into a digital powerhouse. Custom AI tools and workflows that multiply your productivity and capabilities.</p>
            </div>
        </a>
        <div class="services-button">
            <a href="/services" class="btn">View Services & Pricing</a>
        </div>
    </div>
</section>

<!-- Why Choose Me Section -->
<section class="why-choose-me">
    <div class="container">
        <div class="why-choose-me-inner">
            <div class="why-choose-me-image">
                <img src="<?php echo get_theme_mod('why_choose_me_image', get_template_directory_uri() . '/images/default-image.jpg'); ?>" alt="Chris Huber - Digital Entrepreneur">
            </div>
            <div class="why-choose-me-content">
                <h2>Meet Chris Huber</h2>
                <p>From captaining boats to building digital empires, I’ve built a career that fuses creativity, technology, and entrepreneurship.</p>
                <p>Based in Austin, TX, I bring a unique perspective shaped by my experience as a 100-Ton Master Captain, music journalist, and digital entrepreneur. I’ve spent years documenting the DIY music scene, growing platforms like Extra Chill, and mastering the art of SEO, automation, and AI-driven web development.</p>
                <p>Today, I help small businesses and creatives turn their digital presence into a powerhouse—leveraging cutting-edge tech, custom-built tools, and a relentless drive for results.</p>
             <a href="/about" class="btn">Learn More About Me</a>
            </div>
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
        <p>From basic websites to complex automations, I've mastered the tools that drive digital success. Every technology I use has been battle-tested in my own projects.</p>
        
        <div class="tech-cloud reveal">
            <!-- Core Development -->
            <div class="tech-item" data-category="web">WordPress</div>
            <div class="tech-item" data-category="web">Custom Plugins</div>
            <div class="tech-item" data-category="web">PHP</div>
            <div class="tech-item" data-category="web">JavaScript</div>
            <div class="tech-item" data-category="web">HTML/CSS</div>
            
            <!-- SEO Tools -->
            <div class="tech-item" data-category="seo">Google Analytics</div>
            <div class="tech-item" data-category="seo">Search Console</div>
            <div class="tech-item" data-category="seo">Local SEO</div>
            <div class="tech-item" data-category="seo">Schema Markup</div>
            
            <!-- API Integrations -->
            <div class="tech-item" data-category="api">REST APIs</div>
            <div class="tech-item" data-category="api">WooCommerce</div>
            <div class="tech-item" data-category="api">Payment Gateways</div>
            <div class="tech-item" data-category="api">3rd Party APIs</div>
            
            <!-- AI & Automation -->
            <div class="tech-item" data-category="ai">ChatGPT API</div>
            <div class="tech-item" data-category="ai">AI Content Tools</div>
            <div class="tech-item" data-category="ai">Automation Scripts</div>
            <div class="tech-item" data-category="ai">Custom AI Solutions</div>
            
            <!-- Marketing Tools -->
            <div class="tech-item" data-category="marketing">Email Marketing</div>
            <div class="tech-item" data-category="marketing">Social Media</div>
            <div class="tech-item" data-category="marketing">Content Strategy</div>
            <div class="tech-item" data-category="marketing">Analytics</div>
        </div>
    </div>
</section>



</main>

<?php get_footer(); ?>
