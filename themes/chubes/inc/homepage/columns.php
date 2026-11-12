<?php
/**
 * Homepage Column Components
 * 
 * Provides Blog and Journal columns for the homepage grid.
 * Hooks into chubes_homepage_columns action.
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Render Blog column on homepage
 */
function chubes_homepage_blog_column() {
	$blog_query = new WP_Query(chubes_blog_query_args([
		'posts_per_page' => 3,
	]));
	?>
	<div class="homepage-column">
		<h3>Blog</h3>
		<ul class="item-list">
			<?php if ($blog_query->have_posts()) : ?>
				<?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
					<li>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						<div class="meta"><small><?php echo get_the_date(); ?></small></div>
					</li>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<li>No blog posts yet.</li>
			<?php endif; ?>
		</ul>
		<div class="list-cta">
			<?php $blog_link = get_permalink(get_option('page_for_posts')); ?>
			<a class="btn secondary" href="<?php echo esc_url($blog_link ? $blog_link : home_url('/blog')); ?>">View all Posts</a>
		</div>
	</div>
	<?php
}
add_action('chubes_homepage_columns', 'chubes_homepage_blog_column', 10);

/**
 * Render Journal column on homepage
 */
function chubes_homepage_journal_column() {
	$journal_query = new WP_Query(chubes_journal_query_args([
		'posts_per_page' => 3,
	]));
	?>
	<div class="homepage-column">
		<h3>Journal</h3>
		<ul class="item-list">
			<?php if ($journal_query->have_posts()) : ?>
				<?php while ($journal_query->have_posts()) : $journal_query->the_post(); ?>
					<li>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						<div class="meta"><small><?php echo get_the_date(); ?></small></div>
					</li>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<li>No journal entries yet.</li>
			<?php endif; ?>
		</ul>
		<div class="list-cta">
			<a class="btn secondary" href="<?php echo esc_url(chubes_journal_url()); ?>">View all Journal</a>
		</div>
	</div>
	<?php
}
add_action('chubes_homepage_columns', 'chubes_homepage_journal_column', 20);
