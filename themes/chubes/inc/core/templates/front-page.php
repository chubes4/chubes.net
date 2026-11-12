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
    <section class="hero">
        <div class="container">
            <?php 
                $default_heading = 'Home of Chris Huber, a.k.a. Chubes';
                $heading = get_theme_mod('chubes_hero_heading', $default_heading);
            ?>
            <h1><?php echo esc_html($heading); ?></h1>
            <div class="hero-content">
                <div class="hero-image">
                    <?php 
                    $hero_image_id = get_theme_mod('chubes_hero_image');
                    if ($hero_image_id) :
                        echo wp_get_attachment_image($hero_image_id, 'medium', false, array('alt' => 'Chris Huber'));
                    endif;
                    ?>
                </div>
                <div class="hero-bio">
                    <?php 
                    $default_bio = "WordPress developer & music journalist based in Austin, TX.\n\n";
                    $default_bio .= "Founder of <a href=\"https://extrachill.com\">Extra Chill</a>, an independent music platform. Creator of <a href=\"https://github.com/chubes4/datamachine\">Data Machine</a>, an AI-powered automation plugin for WordPress.\n\n";
                    $default_bio .= "I love to experiment and learn new things.\n\n";
                    $default_bio .= "This website is filled with information about my projects and my life.\n\n";
                    $default_bio .= "Perpetually under construction.";
                    
                    $bio = get_theme_mod('chubes_hero_bio', $default_bio);
                    echo wp_kses_post(wpautop($bio));
                    ?>
                </div>
            </div>
        </div>
    </section>

    <section class="homepage-columns">
        <div class="container">
            <h2>My Stuff</h2>
            <div class="content-grid">
                <?php do_action('chubes_homepage_columns'); ?>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
