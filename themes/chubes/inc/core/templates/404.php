<?php
/**
 * Custom 404 Error Page Template
 * 
 * Part of organized template hierarchy system in /templates/ directory.
 * Displays user-friendly error message with navigation options.
 */
get_header();
?>

<main class="site-main error-404">
    <section class="error-content">
        <div class="container">

            <h1 class="error-title">404: Lost at Sea</h1>

            <p class="error-message">The page you are looking for is lost at sea. It may never return again.</p>

            <form class="search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <input type="search" class="search-input" placeholder="Search site..." value="<?php echo get_search_query(); ?>" name="s" />
                <button type="submit" class="search-submit">
                    <span class="search-text">Search</span>
                </button>
            </form>

            <div class="error-actions">
                <a href="mailto:chubes@chubes.net" class="btn secondary">Contact Me</a>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>