<?php
/**
 * Plugin Name:       Poem Block
 * Description:       Paginated poem block: one stanza per page with previous/next navigation.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.2
 * Author:            Chris Huber
 * Author URI:        https://chubes.net
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       poem-block
 *
 * @package PoemBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function poem_block_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'poem_block_init' );
