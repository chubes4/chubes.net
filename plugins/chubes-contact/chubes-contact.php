<?php
/**
 * Plugin Name: Chubes Contact
 * Plugin URI: https://chubes.net
 * Description: A Gutenberg block-based contact form with REST API integration.
 * Version: 0.1.3
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Chris Huber
 * Author URI: https://chubes.net
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: chubes-contact
 *
 * @package ChubesContact
 */

// Prevent direct access
if ( ! defined('ABSPATH') ) {
	exit;
}

// Define plugin constants
define('CHUBES_CONTACT_VERSION', '0.1.3');
define('CHUBES_CONTACT_PATH', plugin_dir_path(__FILE__));
define('CHUBES_CONTACT_URL', plugin_dir_url(__FILE__));

require_once CHUBES_CONTACT_PATH . 'inc/Api/ContactEndpoint.php';

/**
 * Initialize the plugin
 */
function chubes_contact_init() {
	// Register REST API routes
	if ( class_exists('Chubes\\Contact\\Api\\ContactEndpoint') ) {
		$api = new \Chubes\Contact\Api\ContactEndpoint();
		$api->register();
	}

	// Register Blocks
	register_block_type(CHUBES_CONTACT_PATH . 'build');
}
add_action('init', 'chubes_contact_init');

/**
 * Localize script for frontend
 */
function chubes_contact_localize_script() {
	$config = array(
		'nonce'    => wp_create_nonce('contact_nonce'),
		'rest_url' => get_rest_url(null, 'chubes/v1/contact'),
	);

	// The handle is derived from the block name "chubes/contact-form"
	// plus "-view-script" because it is defined as "viewScript" in block.json
	wp_localize_script('chubes-contact-form-view-script', 'chubesContactParams', $config);
}
add_action('wp_enqueue_scripts', 'chubes_contact_localize_script');
