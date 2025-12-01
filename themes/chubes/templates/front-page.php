<?php 
/**
 * Homepage Template - Hero and Latest Content
 * 
 * Part of organized template hierarchy in /templates/ directory.
 * Displays hero section and flexible column grid via chubes_homepage_columns hook.
 * Loads home.css via conditional asset loading in functions.php.
 */
get_header(); ?>

<main class="site-main">
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Independent Developer & Music Journalist</h1>
            <p>I build Extra Chill, a music blog I started in college that has grown into a respected journalistic platform. I also develop WordPress plugins, web applications, and tools that integrate AI across different systems.</p>
        </div>
    </section>

    <!-- Homepage Columns Section -->
    <section class="homepage-columns">
        <div class="container">
            <h2>Explore the World of Chubes</h2>
            <div class="content-grid">
                <?php do_action('chubes_homepage_columns'); ?>
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
