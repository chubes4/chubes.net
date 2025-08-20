<?php get_header(); ?>

<main class="site-main">
    <!-- Hero Section -->
    <section class="hero reveal">
        <div class="container">
            <h1>AI-First WordPress Developer & Music Journalist</h1>
            <p>Former sailboat captain turned creative entrepreneur. I build AI-powered WordPress systems, automate content workflows, and cover the music scene through Extra Chill—bridging technical precision with creative expertise.</p>
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
            <h3>Production AI Content Systems</h3>
            <p>AI-powered WordPress systems proven at commercial scale. My Data Machine plugin currently powers Festival Wire's automated content processing across 48+ festivals with daily updates—demonstrating AI workflows that enhance editorial productivity.</p>
            <a href="/plugins" class="btn secondary">Browse Plugins</a>
        </div>

        <div class="project-category reveal">
            <h3>Extra Chill Music Platform</h3>
            <p>Scaled Extra Chill to 300k+ monthly visitors through strategic content and smart automation. A music journalism platform covering festivals, interviews, and industry insights.</p>
            <a href="https://extrachill.com" class="btn secondary" target="_blank">Visit Extra Chill</a>
        </div>

        <div class="project-category reveal">
            <h3>Content Automation Systems</h3>
            <p>Cross-platform publishing workflows, social media automation, and browser extensions that streamline content creation. Built from real-world experience scaling creative platforms.</p>
            <a href="/portfolio" class="btn secondary">View Portfolio</a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <div class="about-inner">
            <div class="about-image">
                <img src="<?php echo get_theme_mod('why_choose_me_image', get_template_directory_uri() . '/images/default-image.jpg'); ?>" alt="Chris Huber - Developer & Music Journalist">
            </div>
            <div class="about-content">
                <h2>The Journey</h2>
                <p>Former 100-Ton Master Captain who traded the open sea for open source. Over 13 years, I've built Extra Chill into a comprehensive media platform with 444+ community members, established industry relationships, and multi-domain technical architecture spanning content, commerce, and community.</p>
                <p>Based in Austin, TX, I combine technical precision with creative insight gained from operating AI content systems in production. My Data Machine plugin currently powers Festival Wire's automated processing of 48+ festivals—proving that AI-first WordPress development can enhance editorial workflows at commercial scale.</p>
                <p>This real-world operational experience drives my development approach: I build AI systems that solve actual problems because I've lived those problems while scaling content platforms and managing editorial workflows.</p>
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





</main>

<?php get_footer(); ?>
