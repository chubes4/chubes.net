<?php
/**
 * Tests for the visualizer block's registration.
 *
 * @package VisualizerBlock\Tests
 */

declare( strict_types = 1 );

if ( ! class_exists( 'WP_UnitTestCase' ) ) {
	return;
}

/**
 * @group visualizer-block
 */
class VisualizerBlockTest extends WP_UnitTestCase {

	public function test_block_is_registered(): void {
		// Published pages store <!-- wp:visualizer-block/visualizer -->.
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'visualizer-block/visualizer' ) );
	}

	public function test_version_constant_matches_plugin_header(): void {
		$header = get_file_data( dirname( __DIR__ ) . '/visualizer-block.php', array( 'Version' => 'Version' ) );

		$this->assertSame( $header['Version'], VisualizerBlock::VERSION );
	}
}
