<?php
function chubes_add_image_overlay_to_portfolio($content) {
    // Apply only to the 'portfolio' post type
    if (is_singular('portfolio')) {
        // Find images that are wrapped in links
        $content = preg_replace_callback('/<a href="([^"]+)"[^>]*>(<img[^>]+>)<\/a>/i', function($matches) {
            return '<div class="image-overlay-wrapper">
                        <a href="' . esc_url($matches[1]) . '" target="_blank" class="image-overlay-link">
                            ' . $matches[2] . '
                            <div class="image-overlay">
                                <span class="overlay-btn">Visit Live Site</span>
                            </div>
                        </a>
                    </div>';
        }, $content);
    }
    
    return $content;
}
add_filter('the_content', 'chubes_add_image_overlay_to_portfolio');
