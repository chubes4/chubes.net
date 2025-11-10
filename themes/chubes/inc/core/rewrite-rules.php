<?php
/**
 * Custom Rewrite Rules for Documentation Post Type
 *
 * Handles hierarchical URLs based on codebase taxonomy structures:
 * - /docs/{category}/{project}/post-slug/
 * - /docs/{category}/{project}/{subcategory}/post-slug/
 * - /docs/{category}/{project}/{subcategory}/{subsubcategory}/post-slug/
 *
 * Categories: wordpress-plugins, wordpress-themes, discord-bots, php-libraries
 */

/**
 * Add custom rewrite rules for documentation posts with hierarchical codebase taxonomy URLs
 */
function chubes_documentation_rewrite_rules() {
    add_rewrite_rule(
        '^wordpress-plugins/?$',
        'index.php?codebase_archive=wordpress-plugins',
        'top'
    );

    add_rewrite_rule(
        '^wordpress-themes/?$',
        'index.php?codebase_archive=wordpress-themes',
        'top'
    );

    add_rewrite_rule(
        '^discord-bots/?$',
        'index.php?codebase_archive=discord-bots',
        'top'
    );

    add_rewrite_rule(
        '^php-libraries/?$',
        'index.php?codebase_archive=php-libraries',
        'top'
    );

    add_rewrite_rule(
        '^wordpress-plugins/([^/]+)/?$',
        'index.php?codebase_project=wordpress-plugins/$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^wordpress-themes/([^/]+)/?$',
        'index.php?codebase_project=wordpress-themes/$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^discord-bots/([^/]+)/?$',
        'index.php?codebase_project=discord-bots/$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^php-libraries/([^/]+)/?$',
        'index.php?codebase_project=php-libraries/$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^docs/wordpress-plugins/?$',
        'index.php?docs_category_archive=wordpress-plugins',
        'top'
    );

    add_rewrite_rule(
        '^docs/wordpress-themes/?$',
        'index.php?docs_category_archive=wordpress-themes',
        'top'
    );

    add_rewrite_rule(
        '^docs/discord-bots/?$',
        'index.php?docs_category_archive=discord-bots',
        'top'
    );

    add_rewrite_rule(
        '^docs/php-libraries/?$',
        'index.php?docs_category_archive=php-libraries',
        'top'
    );

    add_rewrite_rule(
        '^docs/wordpress-plugins/([^/]+)/?$',
        'index.php?docs_project_archive=wordpress-plugins/$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^docs/wordpress-themes/([^/]+)/?$',
        'index.php?docs_project_archive=wordpress-themes/$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^docs/discord-bots/([^/]+)/?$',
        'index.php?docs_project_archive=discord-bots/$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^docs/php-libraries/([^/]+)/?$',
        'index.php?docs_project_archive=php-libraries/$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^docs/(wordpress-plugins|wordpress-themes|discord-bots|php-libraries)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/?$',
        'index.php?post_type=documentation&name=$matches[6]&plugin_path=$matches[1]/$matches[2]/$matches[3]/$matches[4]/$matches[5]',
        'top'
    );

    add_rewrite_rule(
        '^docs/(wordpress-plugins|wordpress-themes|discord-bots|php-libraries)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/?$',
        'index.php?post_type=documentation&name=$matches[5]&plugin_path=$matches[1]/$matches[2]/$matches[3]/$matches[4]',
        'top'
    );

    add_rewrite_rule(
        '^docs/(wordpress-plugins|wordpress-themes|discord-bots|php-libraries)/([^/]+)/([^/]+)/?$',
        'index.php?post_type=documentation&name=$matches[3]&plugin_path=$matches[1]/$matches[2]',
        'top'
    );

    add_rewrite_rule(
        '^docs/(wordpress-plugins|wordpress-themes|discord-bots|php-libraries)/([^/]+)/([^/]+)/([^/]+)/?$',
        'index.php?post_type=documentation&name=$matches[4]&plugin_path=$matches[1]/$matches[2]/$matches[3]',
        'top'
    );
}
add_action('init', 'chubes_documentation_rewrite_rules');

/**
 * Generate custom permalinks for documentation posts based on any assigned taxonomy hierarchy
 *
 * @param string $permalink The existing permalink URL
 * @param WP_Post $post The post object
 * @return string The custom permalink
 */
function chubes_documentation_permalink($permalink, $post) {
    if ($post->post_type !== 'documentation') {
        return $permalink;
    }

    $doc_taxonomies = get_object_taxonomies('documentation', 'names');

    foreach ($doc_taxonomies as $taxonomy) {
        $terms = get_the_terms($post->ID, $taxonomy);
        if ($terms && !is_wp_error($terms)) {
            $full_hierarchy_terms = [];
            foreach ($terms as $term) {
                $full_hierarchy_terms[] = $term;

                $parent_id = $term->parent;
                while ($parent_id != 0) {
                    $parent_term = get_term($parent_id, $taxonomy);
                    if ($parent_term && !is_wp_error($parent_term)) {
                        array_unshift($full_hierarchy_terms, $parent_term);
                        $parent_id = $parent_term->parent;
                    } else {
                        break;
                    }
                }
            }

            $hierarchy_path = chubes_build_term_hierarchy($full_hierarchy_terms);
            if ($hierarchy_path) {
                return home_url('/docs/' . $hierarchy_path . '/' . $post->post_name . '/');
            }
        }
    }

    return $permalink;
}
add_filter('post_type_link', 'chubes_documentation_permalink', 10, 2);

/**
 * Build hierarchical URL path from codebase taxonomy terms
 *
 * @param array $terms Array of taxonomy term objects
 * @return string Hierarchical path like 'parent/child/grandchild'
 */
function chubes_build_term_hierarchy($terms) {
    if (empty($terms)) {
        return '';
    }

    $hierarchy = [];
    $term_map = [];

    foreach ($terms as $term) {
        $term_map[$term->term_id] = $term;
    }

    $root_term = null;
    foreach ($terms as $term) {
        if ($term->parent == 0 || !isset($term_map[$term->parent])) {
            $root_term = $term;
            break;
        }
    }

    if (!$root_term) {
        $root_term = reset($terms);
    }

    $current_term = $root_term;
    $hierarchy[] = $current_term->slug;

    while (true) {
        $child_found = false;
        foreach ($terms as $term) {
            if ($term->parent == $current_term->term_id) {
                $hierarchy[] = $term->slug;
                $current_term = $term;
                $child_found = true;
                break;
            }
        }
        if (!$child_found) {
            break;
        }
    }

    return implode('/', $hierarchy);
}

/**
 * Handle query vars for custom codebase path parameters
 *
 * @param array $vars Existing query vars
 * @return array Modified query vars
 */
function chubes_documentation_query_vars($vars) {
    $vars[] = 'plugin_path';
    $vars[] = 'theme_path';
    $vars[] = 'docs_category_archive';
    $vars[] = 'docs_project_archive';
    $vars[] = 'codebase_archive';
    $vars[] = 'codebase_project';
    return $vars;
}
add_filter('query_vars', 'chubes_documentation_query_vars');

/**
 * Handle template include for custom archives using WordPress hierarchy
 */
function chubes_custom_template_include($template) {
    if ($archive_type = get_query_var('codebase_archive')) {
        $parent_term = get_term_by('slug', $archive_type, 'codebase');
        if ($parent_term && !is_wp_error($parent_term)) {
            global $wp_query;
            $wp_query->queried_object = $parent_term;
            $wp_query->queried_object_id = $parent_term->term_id;
            $wp_query->is_tax = true;
            $wp_query->is_archive = true;

            global $chubes_codebase_archive_type;
            $chubes_codebase_archive_type = $archive_type;

            return locate_template('templates/archive/archive-codebase.php');
        }

        global $chubes_codebase_archive_type;
        $chubes_codebase_archive_type = $archive_type;
        return locate_template('templates/archive/archive-codebase.php');
    }

    if ($project_path = get_query_var('codebase_project')) {
        $path_parts = explode('/', $project_path);
        if (count($path_parts) >= 2) {
            $category = $path_parts[0];
            $project_slug = $path_parts[1];

            $parent_term = get_term_by('slug', $category, 'codebase');
            if ($parent_term) {
                $project_terms = get_terms([
                    'taxonomy' => 'codebase',
                    'parent' => $parent_term->term_id,
                    'slug' => $project_slug,
                    'hide_empty' => false
                ]);

                if (!empty($project_terms)) {
                    $project_term = $project_terms[0];

                    global $wp_query;
                    $wp_query->queried_object = $project_term;
                    $wp_query->queried_object_id = $project_term->term_id;
                    $wp_query->is_tax = true;
                    $wp_query->is_archive = true;

                    return locate_template('templates/taxonomy/taxonomy-codebase.php');
                }
            }
        }

        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        return locate_template('templates/404.php');
    }

    if ($category = get_query_var('docs_category_archive')) {
        $parent_term = get_term_by('slug', $category, 'codebase');
        if ($parent_term && !is_wp_error($parent_term)) {
            global $wp_query;
            $wp_query->queried_object = $parent_term;
            $wp_query->queried_object_id = $parent_term->term_id;
            $wp_query->is_tax = true;
            $wp_query->is_archive = true;

            global $chubes_docs_category_type;
            $chubes_docs_category_type = $category;

            return locate_template('templates/archive/archive-docs-category.php');
        }

        global $chubes_docs_category_type;
        $chubes_docs_category_type = $category;
        return locate_template('templates/archive/archive-docs-category.php');
    }

    if ($project_path = get_query_var('docs_project_archive')) {
        $path_parts = explode('/', $project_path);
        if (count($path_parts) >= 2) {
            $category = $path_parts[0];
            $project_slug = $path_parts[1];

            $parent_term = get_term_by('slug', $category, 'codebase');
            if ($parent_term) {
                $project_terms = get_terms([
                    'taxonomy' => 'codebase',
                    'parent' => $parent_term->term_id,
                    'slug' => $project_slug,
                    'hide_empty' => false
                ]);

                if (!empty($project_terms)) {
                    $project_term = $project_terms[0];

                    global $wp_query;
                    $wp_query->queried_object = $project_term;
                    $wp_query->queried_object_id = $project_term->term_id;
                    $wp_query->is_tax = true;
                    $wp_query->is_archive = true;

                    global $chubes_docs_taxonomy, $chubes_docs_term;
                    $chubes_docs_taxonomy = 'codebase';
                    $chubes_docs_term = $project_term;

                    return locate_template('templates/archive/archive-docs-taxonomy.php');
                }
            }
        }

        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        return locate_template('templates/404.php');
    }

    return $template;
}
add_filter('template_include', 'chubes_custom_template_include');