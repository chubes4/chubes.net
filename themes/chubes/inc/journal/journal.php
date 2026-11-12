<?php
/**
 * Journal
 *
 * Journal entries are ordinary posts in the `journal` category. This module
 * gives that category its own presence on the site:
 *
 * - Archive at /journal/ (plus /journal/page/N/ and /journal/feed/) instead
 *   of /category/journal/.
 * - Kept out of the main blog index and the main /feed/, so the blog stays
 *   the professional stream. Journal has its own feed.
 * - Minimal title/date list archive and Journal-rooted breadcrumbs.
 * - 301s from legacy /journal/{slug}/ URLs (from when Journal was a custom
 *   post type) to the post's current permalink.
 * - `wp chubes journal migrate` converts leftover `journal` CPT entries.
 */

if (!defined('ABSPATH')) {
	exit;
}

const CHUBES_JOURNAL_SLUG = 'journal';

/**
 * Bump when the rewrite rules below change so they are flushed once.
 */
const CHUBES_JOURNAL_REWRITE_VERSION = '1';

/**
 * Get the Journal category term, if it exists.
 *
 * @return WP_Term|null
 */
function chubes_journal_term() {
	$term = get_category_by_slug(CHUBES_JOURNAL_SLUG);
	return $term instanceof WP_Term ? $term : null;
}

/**
 * Journal archive URL, in the site's trailing-slash style.
 *
 * @return string
 */
function chubes_journal_url() {
	return user_trailingslashit(home_url('/' . CHUBES_JOURNAL_SLUG), 'category');
}

/**
 * Whether a post is a journal entry.
 *
 * @param int|WP_Post|null $post Post to check. Defaults to the current post.
 * @return bool
 */
function chubes_is_journal_post($post = null) {
	$post = get_post($post);
	return $post && 'post' === $post->post_type && has_category(CHUBES_JOURNAL_SLUG, $post);
}

/**
 * Whether the current request is the Journal archive.
 *
 * @return bool
 */
function chubes_is_journal_archive() {
	return is_category(CHUBES_JOURNAL_SLUG);
}

/**
 * Register /journal/ rewrites.
 */
function chubes_journal_rewrites() {
	$slug = CHUBES_JOURNAL_SLUG;

	add_rewrite_rule("^{$slug}/?$", 'index.php?category_name=' . $slug, 'top');
	add_rewrite_rule("^{$slug}/page/?([0-9]{1,})/?$", 'index.php?category_name=' . $slug . '&paged=$matches[1]', 'top');
	add_rewrite_rule("^{$slug}/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$", 'index.php?category_name=' . $slug . '&feed=$matches[1]', 'top');

	// Legacy single entry URLs from the old journal post type.
	add_rewrite_rule("^{$slug}/([^/]+)/?$", 'index.php?name=$matches[1]&chubes_journal_legacy=1', 'top');

	if (get_option('chubes_journal_rewrite_version') !== CHUBES_JOURNAL_REWRITE_VERSION) {
		flush_rewrite_rules(false);
		update_option('chubes_journal_rewrite_version', CHUBES_JOURNAL_REWRITE_VERSION);
	}
}
add_action('init', 'chubes_journal_rewrites');

/**
 * Register the legacy-URL query var.
 *
 * @param string[] $vars Public query vars.
 * @return string[]
 */
function chubes_journal_query_vars($vars) {
	$vars[] = 'chubes_journal_legacy';
	return $vars;
}
add_filter('query_vars', 'chubes_journal_query_vars');

/**
 * 301 legacy and duplicate Journal URLs to their canonical form.
 *
 * - /journal/{slug}/ (old post type permalinks) -> the post's permalink.
 * - /category/journal/... -> /journal/...
 */
function chubes_journal_legacy_redirect() {
	if (get_query_var('chubes_journal_legacy') && is_singular()) {
		wp_safe_redirect(get_permalink(get_queried_object_id()), 301);
		exit;
	}

	if (chubes_is_journal_archive() && !is_feed()) {
		$path = (string) wp_parse_url(add_query_arg(array()), PHP_URL_PATH);
		if (0 === strpos($path, '/category/' . CHUBES_JOURNAL_SLUG)) {
			$paged = (int) get_query_var('paged');
			$url   = get_term_link(chubes_journal_term());
			if ($paged > 1) {
				$url = user_trailingslashit(trailingslashit($url) . 'page/' . $paged, 'paged');
			}
			wp_safe_redirect($url, 301);
			exit;
		}
	}
}
add_action('template_redirect', 'chubes_journal_legacy_redirect', 1);

/**
 * Point the Journal category link at /journal/.
 *
 * @param string  $url      Term link.
 * @param WP_Term $term     Term.
 * @param string  $taxonomy Taxonomy.
 * @return string
 */
function chubes_journal_term_link($url, $term, $taxonomy) {
	if ('category' === $taxonomy && CHUBES_JOURNAL_SLUG === $term->slug && get_option('permalink_structure')) {
		return chubes_journal_url();
	}
	return $url;
}
add_filter('term_link', 'chubes_journal_term_link', 10, 3);

/**
 * Whether a query is the main blog stream: the posts page or the main /feed/.
 *
 * WP_Query does not set is_home() on feeds, so the site-wide post feed is
 * detected as a feed that is not an archive, single, search or comment feed.
 *
 * @param WP_Query $query Query.
 * @return bool
 */
function chubes_is_blog_stream($query) {
	if ($query->is_home()) {
		return true;
	}

	return $query->is_feed()
		&& !$query->is_archive()
		&& !$query->is_singular()
		&& !$query->is_search()
		&& !$query->is_comment_feed();
}

/**
 * Keep journal entries out of the main blog index and main feed.
 *
 * @param WP_Query $query Query.
 */
function chubes_journal_exclude_from_blog($query) {
	if (is_admin() || !$query->is_main_query() || !chubes_is_blog_stream($query)) {
		return;
	}

	$term = chubes_journal_term();
	if ($term) {
		$excluded   = (array) $query->get('category__not_in');
		$excluded[] = $term->term_id;
		$query->set('category__not_in', array_values(array_filter($excluded)));
	}
}
add_action('pre_get_posts', 'chubes_journal_exclude_from_blog');

/**
 * WP_Query args for blog posts that are not journal entries.
 *
 * @param array $args Additional WP_Query args.
 * @return array
 */
function chubes_blog_query_args($args = array()) {
	$base = array(
		'post_type'   => 'post',
		'post_status' => 'publish',
	);

	$term = chubes_journal_term();
	if ($term) {
		$base['category__not_in'] = array($term->term_id);
	}

	return array_merge($base, $args);
}

/**
 * WP_Query args for journal entries.
 *
 * @param array $args Additional WP_Query args.
 * @return array
 */
function chubes_journal_query_args($args = array()) {
	return array_merge(
		array(
			'post_type'     => 'post',
			'post_status'   => 'publish',
			'category_name' => CHUBES_JOURNAL_SLUG,
		),
		$args
	);
}

/**
 * Archive title: plain "Journal" instead of "Category Journal".
 *
 * @param string $title Archive title.
 * @return string
 */
function chubes_journal_archive_title($title) {
	return chubes_is_journal_archive() ? single_cat_title('', false) : $title;
}
add_filter('get_the_archive_title', 'chubes_journal_archive_title', 20);

/**
 * Archive wrapper class: keep `archive-journal` for existing styles.
 *
 * @param string $type Archive type.
 * @return string
 */
function chubes_journal_archive_type($type) {
	return chubes_is_journal_archive() ? CHUBES_JOURNAL_SLUG : $type;
}
add_filter('chubes_archive_type', 'chubes_journal_archive_type');

/**
 * Journal archive: minimal title/date list instead of the card grid.
 *
 * @param string $content        Current archive content.
 * @param mixed  $queried_object Queried object.
 * @return string
 */
function chubes_journal_archive_content($content, $queried_object) {
	if (!chubes_is_journal_archive()) {
		return $content;
	}

	ob_start();
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
	return ob_get_clean();
}
add_filter('chubes_archive_content', 'chubes_journal_archive_content', 10, 2);

/**
 * Breadcrumbs: Home / Journal [/ Entry] instead of Home / Blog / Journal.
 *
 * @param string|null $breadcrumbs Breadcrumb HTML from an earlier handler.
 * @param array       $args        Breadcrumb args.
 * @return string|null
 */
function chubes_journal_breadcrumbs($breadcrumbs, $args) {
	if (null !== $breadcrumbs) {
		return $breadcrumbs;
	}

	$is_archive = chubes_is_journal_archive();
	$is_entry   = is_single() && chubes_is_journal_post(get_queried_object_id());
	if (!$is_archive && !$is_entry) {
		return null;
	}

	$label = esc_html__('Journal', 'chubes-theme');

	if ($is_archive) {
		return $args['show_current'] ? $args['separator'] . $args['before_current'] . $label . $args['after_current'] : '';
	}

	$out = $args['separator'] . '<a href="' . esc_url(chubes_journal_url()) . '">' . $label . '</a>';
	if ($args['show_current']) {
		$out .= $args['separator'] . $args['before_current'] . get_the_title(get_queried_object_id()) . $args['after_current'];
	}
	return $out;
}
add_filter('chubes_breadcrumb_custom_archive', 'chubes_journal_breadcrumbs', 20, 2);

/**
 * Back navigation: journal entries go back to Journal, the archive to home.
 *
 * @param array $parent Parent page with 'url' and 'title'.
 * @return array
 */
function chubes_journal_parent_page($parent) {
	if (is_single() && chubes_is_journal_post(get_queried_object_id())) {
		return array(
			'url'   => chubes_journal_url(),
			'title' => __('Journal', 'chubes-theme'),
		);
	}

	if (chubes_is_journal_archive()) {
		return array(
			'url'   => home_url('/'),
			'title' => 'Chubes.net',
		);
	}

	return $parent;
}
add_filter('chubes_parent_page', 'chubes_journal_parent_page');

if (defined('WP_CLI') && WP_CLI) {
	require_once __DIR__ . '/class-journal-cli.php';
	WP_CLI::add_command('chubes journal', 'Chubes_Journal_CLI');
}
