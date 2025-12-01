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
            <h1>Enter the World of Chubes</h1>
            <p>My name is Chris Huber. I'm a WordPress Developer & music journalist based in Austin, TX.</p>
            <p>Founder of <a href="https://extrachill.com">Extra Chill</a>, an independent music platform.</p> 
            <p>This website is filled with information about my projects and my life.</p>
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
