<?php
/**
 * Tests for the poem block's registration contract.
 *
 * @package PoemBlock\Tests
 */

declare( strict_types = 1 );

if ( ! class_exists( 'WP_UnitTestCase' ) ) {
	return;
}

/**
 * @group poem-block
 */
class PoemBlockTest extends WP_UnitTestCase {

	/**
	 * Decoded src/block.json.
	 *
	 * @return array<string, mixed>
	 */
	private function metadata(): array {
		$json = file_get_contents( dirname( __DIR__ ) . '/src/block.json' );
		$this->assertIsString( $json );

		$metadata = json_decode( $json, true );
		$this->assertIsArray( $metadata );

		return $metadata;
	}

	public function test_block_name_is_the_one_published_posts_use(): void {
		// Published posts store <!-- wp:create-block/poem-block -->. Renaming the
		// block orphans every poem on the site.
		$this->assertSame( 'create-block/poem-block', $this->metadata()['name'] );
	}

	public function test_block_version_matches_plugin_version(): void {
		$header = get_file_data( dirname( __DIR__ ) . '/my-first-block.php', array( 'Version' => 'Version' ) );

		$this->assertSame( $header['Version'], $this->metadata()['version'] );
	}

	public function test_registration_is_hooked_to_init(): void {
		$this->assertNotFalse( has_action( 'init', 'poem_block_init' ) );
	}
}
