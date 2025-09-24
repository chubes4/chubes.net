<?php
/**
 * Template Hierarchy Filters
 * 
 * WordPress core template hierarchy filters for organized template loading.
 * Redirects template lookups to logical subdirectories within /templates/.
 */

/**
 * Archive template hierarchy filter
 * Redirects archive template lookups to /templates/archive/ subdirectory
 */
function chubes_archive_template_hierarchy($templates) {
    return array_map(function($template) {
        return 'templates/archive/' . $template;
    }, $templates);
}
add_filter('archive_template_hierarchy', 'chubes_archive_template_hierarchy');

/**
 * Single template hierarchy filter
 * Redirects single post template lookups to /templates/single/ subdirectory
 */
function chubes_single_template_hierarchy($templates) {
    return array_map(function($template) {
        return 'templates/single/' . $template;
    }, $templates);
}
add_filter('single_template_hierarchy', 'chubes_single_template_hierarchy');

/**
 * Page template hierarchy filter
 * Redirects page template lookups to /templates/page/ subdirectory
 */
function chubes_page_template_hierarchy($templates) {
    return array_map(function($template) {
        return 'templates/page/' . $template;
    }, $templates);
}
add_filter('page_template_hierarchy', 'chubes_page_template_hierarchy');

/**
 * Taxonomy template hierarchy filter
 * Redirects taxonomy template lookups to /templates/taxonomy/ subdirectory
 */
function chubes_taxonomy_template_hierarchy($templates) {
    return array_map(function($template) {
        return 'templates/taxonomy/' . $template;
    }, $templates);
}
add_filter('taxonomy_template_hierarchy', 'chubes_taxonomy_template_hierarchy');

/**
 * Front page template hierarchy filter
 * Redirects front page template lookups to /templates/ root
 */
function chubes_frontpage_template_hierarchy($templates) {
    return array_map(function($template) {
        return 'templates/' . $template;
    }, $templates);
}
add_filter('frontpage_template_hierarchy', 'chubes_frontpage_template_hierarchy');


/**
 * 404 template hierarchy filter
 * Redirects 404 template lookups to /templates/ root
 */
function chubes_404_template_hierarchy($templates) {
    return array_map(function($template) {
        return 'templates/' . $template;
    }, $templates);
}
add_filter('404_template_hierarchy', 'chubes_404_template_hierarchy');

/**
 * Home template hierarchy filter
 * Redirects home template lookups to /templates/ root
 */
function chubes_home_template_hierarchy($templates) {
    return array_map(function($template) {
        return 'templates/' . $template;
    }, $templates);
}
add_filter('home_template_hierarchy', 'chubes_home_template_hierarchy');

/**
 * Search template hierarchy filter
 * Redirects search template lookups to /templates/archive/ subdirectory
 */
function chubes_search_template_hierarchy($templates) {
    return array_map(function($template) {
        return 'templates/archive/' . $template;
    }, $templates);
}
add_filter('search_template_hierarchy', 'chubes_search_template_hierarchy');