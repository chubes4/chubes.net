<?php
/**
 * Standalone coverage for the Journal category (inc/journal/journal.php).
 *
 * Journal entries are ordinary posts in the `journal` category, kept out of
 * the blog index and the main feed. This checks the decisions behind that
 * with WordPress doubles: which queries count as the blog stream, what the
 * exclusion hook does to them, the query helpers, and the /journal link.
 *
 * Standalone rather than WP_UnitTestCase because WP Codebox cannot yet run
 * PHPUnit for theme components (Extra-Chill/homeboy#17163).
 *
 * Run: php tests/journal.php
 *
 * @package Chubes
 */

// phpcs:disable Universal.Files.SeparateFunctionsFromOO.Mixed, Generic.Files.OneObjectStructurePerFile.MultipleFound, WordPress.WP.AlternativeFunctions.file_system_operations_fwrite, WordPress.PHP.DevelopmentFunctions.error_log_var_export, Generic.CodeAnalysis.UnusedFunctionParameter.Found, WordPress.Security.EscapeOutput.OutputNotEscaped -- Standalone fixture: WP doubles and CLI failure reporting are intentional.

define( 'ABSPATH', __DIR__ . '/' );

const JOURNAL_TERM_ID = 7;

$GLOBALS['chubes_test_is_admin']   = false;
$GLOBALS['chubes_test_permalinks'] = '/%postname%';

/** WordPress term double. */
class WP_Term {
	/** @var int */
	public $term_id;
	/** @var string */
	public $slug;

	/**
	 * @param int    $term_id Term ID.
	 * @param string $slug    Slug.
	 */
	public function __construct( $term_id, $slug ) {
		$this->term_id = $term_id;
		$this->slug    = $slug;
	}
}

/** Hook registration double. */
function add_action() {}

/** Hook registration double. */
function add_filter() {}

/**
 * @param string $slug Slug.
 * @return WP_Term|false
 */
function get_category_by_slug( $slug ) {
	return 'journal' === $slug ? new WP_Term( JOURNAL_TERM_ID, 'journal' ) : false;
}

/**
 * @param string $path Path.
 * @return string
 */
function home_url( $path = '' ) {
	return 'https://chubes.net' . $path;
}

/**
 * @param string $url URL.
 * @return string
 */
function user_trailingslashit( $url ) {
	return $url;
}

/**
 * @param string $name Option.
 * @return mixed
 */
function get_option( $name ) {
	return 'permalink_structure' === $name ? $GLOBALS['chubes_test_permalinks'] : false;
}

/** @return bool */
function is_admin() {
	return $GLOBALS['chubes_test_is_admin'];
}

/** WP_Query double: is_* flags in, query vars out. */
class Fake_Query {
	/** @var array<string, bool> */
	private $flags;
	/** @var array<string, mixed> */
	public $vars = array();

	/**
	 * @param array<string, bool> $flags is_* flags that are true, without the is_ prefix.
	 */
	public function __construct( array $flags = array() ) {
		$this->flags = $flags + array( 'main_query' => true );
	}

	/**
	 * @param string $name Method.
	 * @param array  $args Args.
	 * @return bool
	 */
	public function __call( $name, $args ) {
		$flag = preg_replace( '/^is_/', '', $name );
		return ! empty( $this->flags[ $flag ] );
	}

	/**
	 * @param string $key Var.
	 * @return mixed
	 */
	public function get( $key ) {
		return $this->vars[ $key ] ?? '';
	}

	/**
	 * @param string $key   Var.
	 * @param mixed  $value Value.
	 */
	public function set( $key, $value ) {
		$this->vars[ $key ] = $value;
	}
}

require_once dirname( __DIR__ ) . '/inc/journal/journal.php';

$failures = 0;

/**
 * @param string $label    What is being checked.
 * @param mixed  $expected Expected value.
 * @param mixed  $actual   Actual value.
 */
function check( $label, $expected, $actual ) {
	global $failures;
	if ( $expected === $actual ) {
		echo "ok   {$label}\n";
		return;
	}
	++$failures;
	fwrite( STDERR, "FAIL {$label}\n  expected: " . var_export( $expected, true ) . "\n  actual:   " . var_export( $actual, true ) . "\n" );
}

// Which queries are the blog stream.
check( 'blog index is the blog stream', true, chubes_is_blog_stream( new Fake_Query( array( 'home' => true ) ) ) );
check( 'main /feed is the blog stream', true, chubes_is_blog_stream( new Fake_Query( array( 'feed' => true ) ) ) );
check(
	'category feed is not',
	false,
	chubes_is_blog_stream(
		new Fake_Query(
			array(
				'feed'    => true,
				'archive' => true,
			)
		)
	)
);
check(
	'search feed is not',
	false,
	chubes_is_blog_stream(
		new Fake_Query(
			array(
				'feed'   => true,
				'search' => true,
			)
		)
	)
);
check(
	'comment feed is not',
	false,
	chubes_is_blog_stream(
		new Fake_Query(
			array(
				'feed'         => true,
				'comment_feed' => true,
			)
		)
	)
);
check(
	'single post feed is not',
	false,
	chubes_is_blog_stream(
		new Fake_Query(
			array(
				'feed'     => true,
				'singular' => true,
			)
		)
	)
);
check( 'category archive is not', false, chubes_is_blog_stream( new Fake_Query( array( 'archive' => true ) ) ) );

// The exclusion hook.
$query = new Fake_Query( array( 'home' => true ) );
chubes_journal_exclude_from_blog( $query );
check( 'blog index excludes the journal category', array( JOURNAL_TERM_ID ), $query->get( 'category__not_in' ) );

$query = new Fake_Query( array( 'feed' => true ) );
$query->set( 'category__not_in', array( 3 ) );
chubes_journal_exclude_from_blog( $query );
check( 'main feed keeps existing exclusions', array( 3, JOURNAL_TERM_ID ), $query->get( 'category__not_in' ) );

$query = new Fake_Query( array( 'archive' => true ) );
chubes_journal_exclude_from_blog( $query );
check( 'journal archive is left alone', '', $query->get( 'category__not_in' ) );

$query = new Fake_Query(
	array(
		'home'       => true,
		'main_query' => false,
	)
);
chubes_journal_exclude_from_blog( $query );
check( 'secondary queries are left alone', '', $query->get( 'category__not_in' ) );

$GLOBALS['chubes_test_is_admin'] = true;
$query                           = new Fake_Query( array( 'home' => true ) );
chubes_journal_exclude_from_blog( $query );
check( 'admin queries are left alone', '', $query->get( 'category__not_in' ) );
$GLOBALS['chubes_test_is_admin'] = false;

// Query helpers.
$blog = chubes_blog_query_args( array( 'posts_per_page' => 3 ) );
check( 'blog helper excludes journal', array( JOURNAL_TERM_ID ), $blog['category__not_in'] );
check( 'blog helper keeps caller args', 3, $blog['posts_per_page'] );
check( 'blog helper only published posts', array( 'post', 'publish' ), array( $blog['post_type'], $blog['post_status'] ) );

$journal = chubes_journal_query_args( array( 'posts_per_page' => 5 ) );
check( 'journal helper selects the category', 'journal', $journal['category_name'] );
check( 'journal helper keeps caller args', 5, $journal['posts_per_page'] );

// Links and query vars.
$journal_term = new WP_Term( JOURNAL_TERM_ID, 'journal' );
check( 'journal url', 'https://chubes.net/journal', chubes_journal_url() );
check( 'category link points at /journal', 'https://chubes.net/journal', chubes_journal_term_link( 'https://chubes.net/category/journal', $journal_term, 'category' ) );
check( 'other categories untouched', '/category/x', chubes_journal_term_link( '/category/x', new WP_Term( 9, 'x' ), 'category' ) );
check( 'same slug in another taxonomy untouched', '/tag/journal', chubes_journal_term_link( '/tag/journal', $journal_term, 'post_tag' ) );
$GLOBALS['chubes_test_permalinks'] = '';
check( 'plain permalinks keep the core link', '/?cat=7', chubes_journal_term_link( '/?cat=7', $journal_term, 'category' ) );
$GLOBALS['chubes_test_permalinks'] = '/%postname%';
check( 'legacy redirect query var registered', array( 's', 'chubes_journal_legacy' ), chubes_journal_query_vars( array( 's' ) ) );

if ( $failures ) {
	fwrite( STDERR, "{$failures} failure(s)\n" );
	exit( 1 );
}
echo "All journal checks passed.\n";
