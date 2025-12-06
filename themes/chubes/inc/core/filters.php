<?php
/**
 * Template Hierarchy Filters
 * 
 * WordPress core template hierarchy filters for organized template loading.
 * Redirects template lookups to /inc/core/templates/.
 */

/**
 * Archive template hierarchy filter
 * Redirects archive template lookups to /inc/core/templates/
 */
function chubes_archive_template_hierarchy($templates) {
    return array_map(function($template) {
        return 'inc/core/templates/' . $template;
    }, $templates);
}
add_filter('archive_template_hierarchy', 'chubes_archive_template_hierarchy');

/**
 * Single template hierarchy filter
 * Redirects single post template lookups to /inc/core/templates/
 */
function chubes_single_template_hierarchy($templates) {
    return array_map(function($template) {
        return 'inc/core/templates/' . $template;
    }, $templates);
}
add_filter('single_template_hierarchy', 'chubes_single_template_hierarchy');

/**
 * Page template hierarchy filter
 * Redirects page template lookups to /inc/core/templates/
 */
function chubes_page_template_hierarchy($templates) {
    return array_map(function($template) {
        return 'inc/core/templates/' . $template;
    }, $templates);
}
add_filter('page_template_hierarchy', 'chubes_page_template_hierarchy');

/**
 * Front page template hierarchy filter
 * Redirects front page template lookups to /inc/core/templates/
 */
function chubes_frontpage_template_hierarchy($templates) {
    return array_map(function($template) {
        return 'inc/core/templates/' . $template;
    }, $templates);
}
add_filter('frontpage_template_hierarchy', 'chubes_frontpage_template_hierarchy');


/**
 * 404 template hierarchy filter
 * Redirects 404 template lookups to /inc/core/templates/
 */
function chubes_404_template_hierarchy($templates) {
    return array_map(function($template) {
        return 'inc/core/templates/' . $template;
    }, $templates);
}
add_filter('404_template_hierarchy', 'chubes_404_template_hierarchy');

/**
 * Home template filter
 * Forces the blog Posts page to use the archive template directly
 */
add_filter('home_template', function() {
    return get_template_directory() . '/inc/core/templates/archive.php';
});

/**
 * Search template hierarchy filter
 * Redirects search template lookups to /inc/core/templates/
 */
function chubes_search_template_hierarchy($templates) {
    return array_map(function($template) {
        return 'inc/core/templates/' . $template;
    }, $templates);
}
add_filter('search_template_hierarchy', 'chubes_search_template_hierarchy');

/**
 * Customize archive titles
 * 
 * Formats archive titles with span wrapper for styling and handles
 * special cases like blog index. Removes default "Category:" prefixes
 * and replaces with styled markup.
 *
 * @param string $title Default archive title
 * @return string Formatted archive title
 */
function chubes_archive_title($title) {
	if (is_home()) {
		return 'Blog';
	}

	if (is_category()) {
		return '<span class="archive-type">Category</span>' . single_cat_title('', false);
	}

	if (is_tag()) {
		return '<span class="archive-type">Tag</span>' . single_tag_title('', false);
	}

	if (is_author()) {
		return '<span class="archive-type">Author</span>' . get_the_author();
	}

	if (is_day()) {
		return '<span class="archive-type">Daily Archives</span>' . get_the_date();
	}

	if (is_month()) {
		return '<span class="archive-type">Monthly Archives</span>' . get_the_date('F Y');
	}

	if (is_year()) {
		return '<span class="archive-type">Yearly Archives</span>' . get_the_date('Y');
	}

	if (is_post_type_archive()) {
		return post_type_archive_title('', false);
	}

	return $title;
}
add_filter('get_the_archive_title', 'chubes_archive_title');