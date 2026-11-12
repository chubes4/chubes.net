<?php
/**
 * Tests for the Journal: a category on regular posts that lives at /journal/
 * and stays out of the blog index and main feed.
 *
 * @package Chubes\Tests
 */

declare( strict_types = 1 );

if ( ! class_exists( 'WP_UnitTestCase' ) ) {
	return;
}

/**
 * @group journal
 */
class JournalTest extends WP_UnitTestCase {

	private int $journal_post;

	private int $blog_post;

	public function set_up(): void {
		parent::set_up();

		$this->set_permalink_structure( '/%postname%' );
		chubes_journal_rewrites();
		flush_rewrite_rules();

		$term = self::factory()->category->create(
			array(
				'slug' => CHUBES_JOURNAL_SLUG,
				'name' => 'Journal',
			)
		);

		$this->journal_post = self::factory()->post->create(
			array(
				'post_name'     => 'thoughts-from-a-train',
				'post_category' => array( $term ),
			)
		);
		$this->blog_post    = self::factory()->post->create( array( 'post_name' => 'a-blog-post' ) );
	}

	public function test_journal_post_detection(): void {
		$this->assertTrue( chubes_is_journal_post( $this->journal_post ) );
		$this->assertFalse( chubes_is_journal_post( $this->blog_post ) );
	}

	public function test_query_helpers_split_blog_and_journal(): void {
		$blog    = get_posts( chubes_blog_query_args( array( 'fields' => 'ids' ) ) );
		$journal = get_posts( chubes_journal_query_args( array( 'fields' => 'ids' ) ) );

		$this->assertSame( array( $this->blog_post ), $blog );
		$this->assertSame( array( $this->journal_post ), $journal );
	}

	public function test_category_link_points_at_journal(): void {
		$this->assertSame( chubes_journal_url(), get_category_link( chubes_journal_term() ) );
		$this->assertSame( '/journal', wp_parse_url( chubes_journal_url(), PHP_URL_PATH ) );
	}

	public function test_journal_url_is_the_category_archive(): void {
		$this->go_to( home_url( '/journal' ) );

		$this->assertTrue( chubes_is_journal_archive() );
		$this->assertSame( array( $this->journal_post ), wp_list_pluck( $GLOBALS['wp_query']->posts, 'ID' ) );
	}

	public function test_blog_index_excludes_journal(): void {
		$this->go_to( home_url( '/' ) );

		$this->assertTrue( is_home() );
		$this->assertSame( array( $this->blog_post ), wp_list_pluck( $GLOBALS['wp_query']->posts, 'ID' ) );
	}

	public function test_main_feed_excludes_journal(): void {
		$this->go_to( home_url( '/feed' ) );

		$this->assertTrue( is_feed() );
		$this->assertSame( array( $this->blog_post ), wp_list_pluck( $GLOBALS['wp_query']->posts, 'ID' ) );
	}

	public function test_journal_feed_has_only_journal(): void {
		$this->go_to( home_url( '/journal/feed' ) );

		$this->assertTrue( is_feed() );
		$this->assertTrue( chubes_is_journal_archive() );
		$this->assertSame( array( $this->journal_post ), wp_list_pluck( $GLOBALS['wp_query']->posts, 'ID' ) );
	}

	public function test_legacy_journal_url_is_flagged_for_redirect(): void {
		// Old CPT permalinks were /journal/{slug}. They resolve to the post and
		// carry the query var that makes template_redirect 301 to /{slug}.
		$this->go_to( home_url( '/journal/thoughts-from-a-train' ) );

		$this->assertTrue( is_single( $this->journal_post ) );
		$this->assertSame( '1', get_query_var( 'chubes_journal_legacy' ) );
	}
}
