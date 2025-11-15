<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate and Display Breadcrumb Navigation
 *
 * Creates context-aware breadcrumb navigation with customizable styling and behavior.
 * Automatically detects content type and generates appropriate breadcrumb trails.
 *
 * @param array $args Configuration options for breadcrumb display
 * @return void Outputs breadcrumb HTML directly
 */
function chubes_breadcrumbs($args = []) {
    // Default arguments
    $defaults = [
        'container_class' => 'breadcrumbs',
        'separator'       => '<span class="breadcrumbs-separator">/</span>',
        'home_label'      => 'Home',
        'show_on_home'    => false, // Show breadcrumbs on homepage
        'show_current'    => true,  // Show current page title in breadcrumbs
        'before_current'  => '<span class="breadcrumbs-current">',
        'after_current'   => '</span>',
    ];

    // Parse the args with defaults
    $args = wp_parse_args($args, $defaults);

    global $post, $wp_query;

    // Don't display on the homepage if show_on_home is false
    if (is_front_page() && !$args['show_on_home']) {
        return;
    }

    // Open the breadcrumbs container
    echo '<div class="' . esc_attr($args['container_class']) . '">';
    
    // Home link
    echo '<a href="' . esc_url(home_url('/')) . '">' . esc_html($args['home_label']) . '</a>';
    
    if (is_home()) {
        // Blog page
        if ($args['show_current']) {
            echo $args['separator'];
            echo $args['before_current'] . 'Blog' . $args['after_current'];
        }
    } elseif (is_category()) {
        // Category archive
        $cat = get_category(get_query_var('cat'), false);
        
        echo $args['separator'];
        echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">Blog</a>';
        
        if ($args['show_current']) {
            echo $args['separator'];
            echo $args['before_current'] . esc_html($cat->name) . $args['after_current'];
        }
    } elseif (is_tag()) {
        // Tag archive
        $tag = get_term_by('slug', get_query_var('tag'), 'post_tag');
        
        echo $args['separator'];
        echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">Blog</a>';
        
        if ($args['show_current']) {
            echo $args['separator'];
            echo $args['before_current'] . esc_html($tag->name) . ' Tag' . $args['after_current'];
        }
    } elseif (is_author()) {
        // Author archive
        echo $args['separator'];
        echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">Blog</a>';
        
        if ($args['show_current']) {
            global $author;
            $userdata = get_userdata($author);
            echo $args['separator'];
            echo $args['before_current'] . esc_html($userdata->display_name) . $args['after_current'];
        }
    } elseif (is_search()) {
        // Search results
        if ($args['show_current']) {
            echo $args['separator'];
            echo $args['before_current'] . 'Search Results for: ' . get_search_query() . $args['after_current'];
        }
    } elseif (is_day()) {
        // Day archive
        echo $args['separator'];
        echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">Blog</a>';
        
        echo $args['separator'];
        echo '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . get_the_time('Y') . '</a>';
        
        echo $args['separator'];
        echo '<a href="' . esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))) . '">' . get_the_time('F') . '</a>';
        
        if ($args['show_current']) {
            echo $args['separator'];
            echo $args['before_current'] . get_the_time('d') . $args['after_current'];
        }
    } elseif (is_month()) {
        // Month archive
        echo $args['separator'];
        echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">Blog</a>';
        
        echo $args['separator'];
        echo '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . get_the_time('Y') . '</a>';
        
        if ($args['show_current']) {
            echo $args['separator'];
            echo $args['before_current'] . get_the_time('F') . $args['after_current'];
        }
    } elseif (is_year()) {
        // Year archive
        echo $args['separator'];
        echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">Blog</a>';
        
        if ($args['show_current']) {
            echo $args['separator'];
            echo $args['before_current'] . get_the_time('Y') . $args['after_current'];
        }
    } elseif (is_single() && !is_attachment()) {
        // Single post
        if (get_post_type() === 'post') {
            echo $args['separator'];
            echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">Blog</a>';
            
            $cats = get_the_category();
            if ($cats) {
                echo $args['separator'];
                echo '<a href="' . esc_url(get_category_link($cats[0]->term_id)) . '">' . esc_html($cats[0]->name) . '</a>';
            }
            
            if ($args['show_current']) {
                echo $args['separator'];
                echo $args['before_current'] . get_the_title() . $args['after_current'];
            }
        } elseif (get_post_type() === 'game') {
            // Game single
            echo $args['separator'];
            echo '<a href="' . esc_url(get_post_type_archive_link('game')) . '">Games</a>';
            
            if ($args['show_current']) {
                echo $args['separator'];
                echo $args['before_current'] . get_the_title() . $args['after_current'];
            }
        } elseif (get_post_type() === 'documentation') {
            // Documentation single
            echo $args['separator'];
            echo '<a href="' . esc_url(get_post_type_archive_link('documentation')) . '">Documentation</a>';
            
            $codebase_terms = get_the_terms(get_the_ID(), 'codebase');
            if ($codebase_terms && !is_wp_error($codebase_terms)) {
                $top_level_term = chubes_get_codebase_top_level_term_from_terms($codebase_terms);
                $project_term = chubes_get_codebase_project_term_from_terms($codebase_terms);

                if ($top_level_term) {
                    echo $args['separator'];
                    $docs_category_url = home_url('/docs/' . $top_level_term->slug . '/');
                    echo '<a href="' . esc_url($docs_category_url) . '">' . esc_html($top_level_term->name) . '</a>';
                }

                if ($project_term && (!$top_level_term || $project_term->term_id !== $top_level_term->term_id)) {
                    echo $args['separator'];
                    if ($top_level_term) {
                        $project_url = home_url('/docs/' . $top_level_term->slug . '/' . $project_term->slug . '/');
                    } else {
                        $project_url = get_term_link($project_term);
                    }
                    echo '<a href="' . esc_url($project_url) . '">' . esc_html($project_term->name) . '</a>';
                }
            }
            
            if ($args['show_current']) {
                echo $args['separator'];
                echo $args['before_current'] . get_the_title() . $args['after_current'];
            }
        } else {
            // Other custom post types
            $post_type = get_post_type_object(get_post_type());
            $archive_link = get_post_type_archive_link(get_post_type());
            
            echo $args['separator'];
            if ($archive_link) {
                echo '<a href="' . esc_url($archive_link) . '">' . esc_html($post_type->labels->name) . '</a>';
            } else {
                echo esc_html($post_type->labels->name);
            }
            
            if ($args['show_current']) {
                echo $args['separator'];
                echo $args['before_current'] . get_the_title() . $args['after_current'];
            }
        }
    } elseif (is_post_type_archive()) {
        // Post type archive
        $post_type = get_post_type_object(get_post_type());
        
        if ($post_type) {
            if ($args['show_current']) {
                echo $args['separator'];
                echo $args['before_current'] . esc_html($post_type->labels->name) . $args['after_current'];
            }
        }
    } elseif (is_tax('codebase')) {
        // Codebase taxonomy archive
        echo $args['separator'];
        echo '<a href="' . esc_url(get_post_type_archive_link('documentation')) . '">Documentation</a>';
        
        if ($args['show_current']) {
            $term = get_queried_object();
            echo $args['separator'];
            echo $args['before_current'] . esc_html($term->name) . $args['after_current'];
        }
    } elseif (is_page() && !$post->post_parent) {
        // Page with no parent
        if ($args['show_current']) {
            echo $args['separator'];
            echo $args['before_current'] . get_the_title() . $args['after_current'];
        }
    } elseif (is_page() && $post->post_parent) {
        // Child page
        $parent_id = $post->post_parent;
        $breadcrumbs = [];
        
        // Build array of parent pages
        while ($parent_id) {
            $page = get_post($parent_id);
            $breadcrumbs[] = '<a href="' . esc_url(get_permalink($page->ID)) . '">' . get_the_title($page->ID) . '</a>';
            $parent_id = $page->post_parent;
        }
        
        $breadcrumbs = array_reverse($breadcrumbs);
        foreach ($breadcrumbs as $crumb) {
            echo $args['separator'];
            echo $crumb;
        }
        
        // Current page
        if ($args['show_current']) {
            echo $args['separator'];
            echo $args['before_current'] . get_the_title() . $args['after_current'];
        }
    } elseif (is_attachment()) {
        // Attachment page
        $parent = get_post($post->post_parent);
        
        echo $args['separator'];
        echo '<a href="' . esc_url(get_permalink($parent)) . '">' . get_the_title($parent) . '</a>';
        
        if ($args['show_current']) {
            echo $args['separator'];
            echo $args['before_current'] . get_the_title() . $args['after_current'];
        }
    } elseif (is_404()) {
        // 404 page
        if ($args['show_current']) {
            echo $args['separator'];
            echo $args['before_current'] . 'Error 404' . $args['after_current'];
        }
    }
    
    // Close the breadcrumbs container
    echo '</div>';
}

/**
 * Hook function to automatically add breadcrumbs before content
 * 
 * @return void
 */
function chubes_add_breadcrumbs() {
    // Skip on homepage, 404 page, and specific templates if needed
    if (is_front_page() || is_404()) {
        return;
    }
    
    chubes_breadcrumbs();
}

// Hook breadcrumbs to display before the main content
// Depending on your theme, you may need to adjust this hook
add_action('chubes_before_main_content', 'chubes_add_breadcrumbs');

 