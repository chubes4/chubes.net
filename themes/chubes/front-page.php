<?php get_header(); ?>

<main class="site-main">
    <!-- Hero Section -->
    <section class="hero reveal">
        <div class="container">
            <h1>WordPress Developer & Creative Innovator</h1>
            <p>Music journalist & sailboat captain turned creative entrepreneur. Welcome to my digital world, where I showcase the things I build, offer my services, and distribute my tools.</p>
            <div class="contact-button">
                <a href="/contact" class="btn">Let's Build Something Great</a>
            </div>       
        </div>
    </section>

    <!-- Services Section -->
    <section class="services reveal">
    <div class="container">
        <h2>What I Do</h2>

        <a href="/services#web-development" class="service-category-link">
            <div class="service-category reveal">
                <h3>Custom Web Development</h3>
                <p>Hand-coded websites and applications that work exactly how you need them to. No templates, no compromises—just clean, purposeful code.</p>
            </div>
        </a>

        <a href="/services#wordpress-customization" class="service-category-link">
            <div class="service-category reveal">
                <h3>WordPress Plugin Development</h3>
                <p>Custom Gutenberg blocks, automation plugins, and integrations that extend WordPress beyond its limits. Built for creators who need more than what exists.</p>
            </div>
        </a>

        <a href="/services#content-marketing" class="service-category-link">
            <div class="service-category reveal">
                <h3>Content Strategy & Automation</h3>
                <p>Scaled Extra Chill to 300k+ monthly visitors through strategic content and smart automation. Now I help others build sustainable content machines.</p>
            </div>
        </a>

        <a href="/services#ai-automation" class="service-category-link">
            <div class="service-category reveal">
                <h3>AI Integration & Automation</h3>
                <p>From Discord bots that auto-post to Pinterest to automated content systems—I build AI tools that actually enhance your workflow instead of replacing it.</p>
            </div>
        </a>
        <div class="services-button">
            <a href="/services" class="btn">Explore Services</a>
        </div>
    </div>
</section>

<!-- Why Choose Me Section -->
<section class="why-choose-me">
    <div class="container">
        <div class="why-choose-me-inner">
            <div class="why-choose-me-image">
                <img src="<?php echo get_theme_mod('why_choose_me_image', get_template_directory_uri() . '/images/default-image.jpg'); ?>" alt="Chris Huber - Developer & Music Journalist">
            </div>
            <div class="why-choose-me-content">
                <h2>The Journey</h2>
                <p>Former 100-Ton Master Captain who traded the open sea for open source. I've spent years documenting the music scene as a journalist, building Extra Chill into a respected platform with 400+ community members, and creating digital tools that solve problems I actually face.</p>
                <p>Based in Austin, TX, I bring together technical precision, creative thinking, and real-world experience. I've interviewed artists, booked festivals, built Chrome extensions, and automated everything from content publishing to social media management.</p>
                <p>Whether you're a musician needing a custom platform, a business wanting intelligent automation, or a developer looking for specialized WordPress work—I speak your language and build solutions that make sense.</p>
             <a href="/about" class="btn">More About My Story</a>
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
        <h2 class="reveal">Built With</h2>
        <p>Every tool and technology listed here has been battle-tested in real projects—from scaling music platforms to automating creative workflows.</p>
        
        <div class="tech-cloud reveal">
            <!-- Core Development -->
            <div class="tech-item" data-category="web">WordPress Core</div>
            <div class="tech-item" data-category="web">Gutenberg Blocks</div>
            <div class="tech-item" data-category="web">PHP</div>
            <div class="tech-item" data-category="web">JavaScript</div>
            <div class="tech-item" data-category="web">Chrome Extensions</div>
            
            <!-- Platforms & Systems -->
            <div class="tech-item" data-category="platform">WooCommerce</div>
            <div class="tech-item" data-category="platform">bbPress Forums</div>
            <div class="tech-item" data-category="platform">REST APIs</div>
            <div class="tech-item" data-category="platform">Discord API</div>
            
            <!-- AI & Automation -->
            <div class="tech-item" data-category="ai">OpenAI API</div>
            <div class="tech-item" data-category="ai">Midjourney API</div>
            <div class="tech-item" data-category="ai">Pinterest API</div>
            <div class="tech-item" data-category="ai">Email Automation</div>
            <div class="tech-item" data-category="ai">Content Generation</div>
            
            <!-- Music & Creative -->
            <div class="tech-item" data-category="creative">Instagram API</div>
            <div class="tech-item" data-category="creative">Festival Data</div>
            <div class="tech-item" data-category="creative">Music Journalism</div>
            <div class="tech-item" data-category="creative">Event Management</div>
            
            <!-- Performance & SEO -->
            <div class="tech-item" data-category="seo">Technical SEO</div>
            <div class="tech-item" data-category="seo">Analytics</div>
            <div class="tech-item" data-category="seo">Performance Optimization</div>
            <div class="tech-item" data-category="seo">Schema Markup</div>
        </div>
        
        <!-- Trust Logos -->
        <?php
        $wordpress_logo = get_theme_mod('wordpress_logo');
        $chrome_logo = get_theme_mod('chrome_logo');
        $additional_logo_1 = get_theme_mod('additional_logo_1');
        $additional_logo_2 = get_theme_mod('additional_logo_2');
        
        // Only show logos if at least one is set
        if ($wordpress_logo || $chrome_logo || $additional_logo_1 || $additional_logo_2) : ?>
        <div class="trust-logos-container reveal">
            <?php if ($wordpress_logo) : ?>
                <img src="<?php echo wp_get_attachment_image_url($wordpress_logo, 'full'); ?>" alt="WordPress" class="trust-logo">
            <?php endif; ?>
            
            <?php if ($chrome_logo) : ?>
                <img src="<?php echo wp_get_attachment_image_url($chrome_logo, 'full'); ?>" alt="Chrome Web Store" class="trust-logo">
            <?php endif; ?>
            
            <?php if ($additional_logo_1) : ?>
                <img src="<?php echo wp_get_attachment_image_url($additional_logo_1, 'full'); ?>" alt="Partner Logo" class="trust-logo">
            <?php endif; ?>
            
            <?php if ($additional_logo_2) : ?>
                <img src="<?php echo wp_get_attachment_image_url($additional_logo_2, 'full'); ?>" alt="Partner Logo" class="trust-logo">
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>



</main>

<?php get_footer(); ?>
