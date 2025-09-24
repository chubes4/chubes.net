<?php 
/**
 * Codebase Archive Template with Install Tracking
 * 
 * Unified archive template for all codebase taxonomy categories (plugins, themes, apps, tools).
 * Replaces the previous separate archive-plugin.php and archive-theme.php templates.
 * Displays projects with download counts, documentation links, and repository integration.
 * Uses conditional logic based on category type (plugins, themes, etc.).
 */
get_header(); 

// Determine the archive type from the global set by rewrite rules
global $chubes_codebase_archive_type;
$archive_type = $chubes_codebase_archive_type ?? 'plugins'; // Default fallback

// Get the appropriate parent term
$parent_term = get_term_by('slug', $archive_type, 'codebase');

// Configure display based on archive type
$config = [
    'plugins' => [
        'title' => 'WordPress Plugins',
        'description' => 'Automation tools designed to streamline workflows and unlock creative possibilities within WordPress.',
        'singular' => 'Plugin'
    ],
    'themes' => [
        'title' => 'WordPress Themes', 
        'description' => 'Beautiful, functional themes designed to elevate WordPress websites with elegant design and powerful features.',
        'singular' => 'Theme'
    ],
    'apps' => [
        'title' => 'Web Applications',
        'description' => 'Full-featured web applications and software tools for various purposes.',
        'singular' => 'App'
    ],
    'tools' => [
        'title' => 'Development Tools',
        'description' => 'Command-line tools, utilities, and scripts for developers and automation.',
        'singular' => 'Tool'
    ]
];

$current_config = $config[$archive_type] ?? $config['plugins'];
?>

<main class="site-main">
    <!-- Archive Header -->
    <section class="archive-header">
        <div class="container">
            <h1><?php echo esc_html($current_config['title']); ?></h1>
            <p><?php echo esc_html($current_config['description']); ?></p>
        </div>
    </section>

    <!-- Project Cards Grid -->
    <?php
    // Get child terms (projects) under this category
    $project_terms = [];
    if ($parent_term && !is_wp_error($parent_term)) {
        $project_terms = get_terms(array(
            'taxonomy'   => 'codebase',
            'hide_empty' => false,
            'parent'     => $parent_term->term_id, // Get child projects only
            'orderby'    => 'name',
            'order'      => 'ASC'
        ));
    }
    
    if (!empty($project_terms) && !is_wp_error($project_terms)) : ?>
        <section class="codebase-cards-section">
            <div class="container">
                <div class="codebase-cards-grid">
                    <?php 
                    foreach ($project_terms as $term) : 
                        // Get repository information for this project
                        $repo_info = chubes_get_repository_info($term);
                        $project_type = $repo_info['project_type'] ?? 'project';
                        
                        if ($repo_info['has_content']) : 
                            // Load the codebase card template part
                            get_template_part('templates/parts/codebase/codebase-card', null, [
                                'term' => $term,
                                'repo_info' => $repo_info,
                                'project_type' => $project_type
                            ]);
                        endif;
                    endforeach; ?>
                </div>
            </div>
        </section>
    <?php else : ?>
        <section class="no-projects">
            <div class="container">
                <div class="no-content">
                    <p><?php echo esc_html($current_config['title']); ?> will be listed here soon. Check back for new releases and updates.</p>
                </div>
            </div>
        </section>
    <?php endif; ?>

</main>


<?php get_footer(); ?>