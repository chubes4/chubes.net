<?php
/**
 * Code Block Enhancements
 * 
 * Hooks into core/code block rendering to add copy-to-clipboard functionality
 * with optional language label display. Establishes pattern for future
 * syntax highlighting integration.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter core/code block output to inject copy button and language label
 *
 * @param string $block_content The block content
 * @param array  $block         The block data
 * @return string Modified block content
 */
function chubes_enhance_code_block( $block_content, $block ) {
	if ( $block['blockName'] !== 'core/code' ) {
		return $block_content;
	}

	$language = '';
	if ( preg_match( '/class="[^"]*language-(\w+)[^"]*"/', $block_content, $matches ) ) {
		$language = $matches[1];
	}

	$sprite_url = get_template_directory_uri() . '/assets/icons/chubes.svg';

	$header = '<div class="code-block-header">';
	$header .= '<span class="code-block-language">' . esc_html( $language ) . '</span>';
	$header .= '<button class="code-copy-btn" aria-label="Copy code">';
	$header .= '<svg><use href="' . esc_url( $sprite_url ) . '#icon-copy"></use></svg>';
	$header .= '</button>';
	$header .= '</div>';

	$block_content = preg_replace(
		'/(<pre[^>]*class="[^"]*wp-block-code[^"]*"[^>]*>)/',
		'$1' . $header,
		$block_content
	);

	return $block_content;
}
add_filter( 'render_block', 'chubes_enhance_code_block', 10, 2 );

/**
 * Enqueue code block assets when code blocks are present
 */
function chubes_enqueue_code_block_assets() {
	if ( ! is_singular() ) {
		return;
	}

	$post = get_post();
	if ( ! $post || ! has_block( 'core/code', $post ) ) {
		return;
	}

	wp_enqueue_style(
		'chubes-code-blocks',
		get_template_directory_uri() . '/assets/css/code-blocks.css',
		array(),
		filemtime( get_template_directory() . '/assets/css/code-blocks.css' )
	);

	wp_enqueue_script(
		'chubes-code-copy',
		get_template_directory_uri() . '/assets/js/code-copy.js',
		array(),
		filemtime( get_template_directory() . '/assets/js/code-copy.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'chubes_enqueue_code_block_assets' );
