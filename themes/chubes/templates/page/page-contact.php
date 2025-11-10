<?php
/**
 * Contact Page Template with REST API Form
 * 
 * Part of organized template hierarchy in /templates/page/ directory.
 * Features contact form with honeypot and timestamp spam protection.
 * Form submits via REST API to /wp-json/chubes/v1/contact
 * REST endpoint handler in /inc/contact-rest-api.php
 * Contact assets (CSS/JS) loaded via /inc/core/assets.php
 */
get_header(); ?>

<main class="site-main contact-page">
    <section class="post-content">
        <div class="container">
            <h1><?php the_title(); ?></h1>
            <div class="post-body">
                <?php the_content(); ?>
            </div>
        </div>
    </section>
    <section class="contact-form-section">
        <div class="container">
            <form id="contactForm" class="contact-form" method="post">
                <h2>Supercharge Your Project</h2>
                <!-- Honeypot Field for Spam Protection -->
                <div class="contact-honeypot">
                    <label for="contact_honeypot">Leave this field empty</label>
                    <input type="text" id="contact_honeypot" name="contact_honeypot" value="">
                </div>
                
                <!-- Timestamp Field for Bot Check -->
                <input type="hidden" name="contact_timestamp" id="contact_timestamp" value="<?php echo time(); ?>">
                
                <label for="contactName">What's Your Name?</label>
                <input type="text" id="contactName" name="contactName" required>
                
                <label for="contactEmail">What's Your Email?</label>
                <input type="email" id="contactEmail" name="contactEmail" required>
                
                <label for="contactWebsite">Your Current Website URL (if any)</label>
                <input type="text" id="contactWebsite" name="contactWebsite" placeholder="https://example.com">
                
                <label for="contactMessage">Tell Me About Your Project</label>
                <small class="contact-help-text">
                Share your ideas, goals, or questions—I'll get back to you soon!
                </small>

                <textarea id="contactMessage" name="contactMessage" required></textarea>
                
                <button type="submit" class="btn">Get My Expertise Today</button>
                
                <!-- Error & Success Message Containers --> 
                <p class="contact-error"></p>
                <p class="contact-success"></p>
            </form>
        </div>
    </section>
            
            <!-- Dynamic Back To Navigation -->
            <div class="post-navigation">
                <?php 
                $parent = chubes_get_parent_page();
                ?>
                <a href="<?php echo esc_url($parent['url']); ?>" class="btn secondary">
                    ← Back to <?php echo esc_html($parent['title']); ?>
                </a>
            </div>
</main>

<?php get_footer(); ?>
