<?php
/**
 * WP-CLI: Journal maintenance.
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Manage Journal entries.
 */
class Chubes_Journal_CLI {

	/**
	 * Convert legacy `journal` post type entries into posts in the Journal category.
	 *
	 * Creates the Journal category if needed, switches every `journal` entry
	 * (any status) to `post`, and assigns it only the Journal category. Safe to
	 * re-run. Legacy /journal/{slug}/ URLs 301 to the new permalinks.
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : Report what would change without writing.
	 *
	 * ## EXAMPLES
	 *
	 *     wp chubes journal migrate --dry-run
	 *     wp chubes journal migrate
	 *
	 * @param array $args       Positional args.
	 * @param array $assoc_args Associative args.
	 */
	public function migrate($args, $assoc_args) {
		global $wpdb;

		$dry_run = (bool) WP_CLI\Utils\get_flag_value($assoc_args, 'dry-run', false);

		$ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts} WHERE post_type = %s AND post_status <> 'auto-draft' ORDER BY post_date ASC",
				CHUBES_JOURNAL_SLUG
			)
		);

		// Refuse if a slug would collide with an existing post or page.
		foreach ($ids as $id) {
			$name = get_post_field('post_name', $id);
			if ('' === $name) {
				continue;
			}
			$clash = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND ID <> %d AND post_type IN ('post','page') AND post_status <> 'trash' LIMIT 1",
					$name,
					$id
				)
			);
			if ($clash) {
				WP_CLI::error(sprintf('Slug "%s" (journal %d) collides with post %d. Rename one and re-run.', $name, $id, $clash));
			}
		}

		$term = chubes_journal_term();
		if (!$term) {
			if ($dry_run) {
				WP_CLI::log('Would create category "Journal" (journal).');
			} else {
				$created = wp_insert_term('Journal', 'category', array('slug' => CHUBES_JOURNAL_SLUG));
				if (is_wp_error($created)) {
					WP_CLI::error($created);
				}
				$term = get_term($created['term_id'], 'category');
				WP_CLI::log(sprintf('Created category "Journal" (%d).', $term->term_id));
			}
		}

		foreach ($ids as $id) {
			$label = sprintf('%d %s [%s]', $id, get_the_title($id) ?: '(untitled)', get_post_status($id));
			if ($dry_run) {
				WP_CLI::log('Would convert ' . $label);
				continue;
			}
			set_post_type($id, 'post');
			wp_set_post_categories($id, array($term->term_id));
			WP_CLI::log('Converted ' . $label);
		}

		if (!$dry_run) {
			flush_rewrite_rules(false);
		}

		WP_CLI::success(sprintf('%s %d journal entries.', $dry_run ? 'Would convert' : 'Converted', count($ids)));
	}
}
