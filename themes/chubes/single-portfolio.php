<?php get_header(); ?>

<main class="site-main single-portfolio">
    <section class="portfolio-single">
        <div class="container">
            <!-- Portfolio Title -->
            <h1 class="single-portfolio-title"><?php the_title(); ?></h1>

            <!-- Project Overview -->
            <div class="portfolio-details">
                <p><?php echo get_the_excerpt(); ?></p> <!-- No "Project Overview:" label -->
            </div>

            <!-- Visit Website Button -->
            <?php 
            $project_url = get_post_meta(get_the_ID(), 'project_url', true); 
            if ($project_url) : ?>
                <div class="portfolio-button">
                    <a href="<?php echo esc_url($project_url); ?>" target="_blank" class="btn primary">Visit Website</a>
                </div>
            <?php endif; ?>

            <!-- Tech Stack -->
            <?php 
            $tech_stack = get_post_meta(get_the_ID(), 'tech_stack', true);
            if (!empty($tech_stack)) : ?>
                <h2>Under the Hood</h2>
                <p class="single-portfolio-tech-stack"><?php echo esc_html($tech_stack); ?></p>
            <?php endif; ?>

            <!-- Featured Image with Overlay -->
            <?php if (has_post_thumbnail()) : ?>
                <?php $project_url = get_post_meta(get_the_ID(), 'project_url', true); ?>
                <div class="portfolio-image image-overlay-wrapper">
                    <a href="<?php echo esc_url($project_url); ?>" target="_blank" class="image-overlay-link">
                        <img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php the_title(); ?>">
                        <div class="image-overlay">
                            <span class="overlay-btn">Visit Live Site</span>
                        </div>
                    </a>
                </div>
            <?php endif; ?>


            <!-- Project Description -->
            <div class="portfolio-content">
                <?php the_content(); ?>
            </div>

            <!-- Back to Portfolio -->
            <div class="portfolio-navigation">
                <a href="<?php echo get_post_type_archive_link('portfolio'); ?>" class="btn secondary">← Back to Portfolio</a>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
