<?php
/**
 * Journal Archive Content
 * 
 * Provides journal-specific archive rendering via chubes_archive_content hook.
 * Renders a minimal list format instead of the default card grid.
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Render journal archive content
 * 
 * Outputs journal entries in a simple list format with title and date.
 * Hooked to chubes_archive_content, only executes on journal archives.
 */
function chubes_journal_archive_content() {
	if (!is_post_type_archive('journal')) {
		return;
	}
	?>
	<ul class="journal-list enhanced">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<li>
				<a href="<?php the_permalink(); ?>">
					<span class="journal-title"><?php the_title(); ?></span>
					<span class="journal-date"><?php echo get_the_date(); ?></span>
				</a>
			</li>
		<?php endwhile; else : ?>
			<p>No journal entries found.</p>
		<?php endif; ?>
	</ul>

	<div class="pagination">
		<?php
		echo paginate_links(array(
			'prev_text' => '&larr; Previous',
			'next_text' => 'Next &rarr;',
		));
		?>
	</div>
	<?php
}
add_action('chubes_archive_content', 'chubes_journal_archive_content');
