<?php
/**
 * Back Navigation Module
 * 
 * Consolidates all "back to" navigation functionality into a single module
 * that hooks into the chubes_above_footer action.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Renders the back navigation button above the footer.
 * Uses the existing chubes_get_parent_page() function to determine
 * the appropriate parent page for all page types including 404.
 */
function chubes_render_back_navigation() {
    // Do not show back navigation on the homepage
    if (is_front_page()) {
        return;
    }

    $parent = chubes_get_parent_page();
    ?>
    <div class="post-navigation">
        <a href="<?php echo esc_url($parent['url']); ?>" class="btn secondary">
            ← Back to <?php echo esc_html($parent['title']); ?>
        </a>
    </div>
    <?php
}
add_action('chubes_above_footer', 'chubes_render_back_navigation');