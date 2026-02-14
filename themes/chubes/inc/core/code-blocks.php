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

	// Ensure language class is on the <code> element for Prism.js
	if ( $language && strpos( $block_content, '<code class="language-' ) === false ) {
		$block_content = str_replace( '<code>', '<code class="language-' . esc_attr( $language ) . '">', $block_content );
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
	if ( ! $post ) {
		return;
	}

	// Check for Gutenberg code blocks OR raw <pre><code> in content
	$has_code = has_block( 'core/code', $post ) || strpos( $post->post_content, '<pre><code' ) !== false || strpos( $post->post_content, '<pre class=' ) !== false;
	if ( ! $has_code ) {
		return;
	}

	wp_enqueue_style(
		'chubes-code-blocks',
		get_template_directory_uri() . '/assets/css/code-blocks.css',
		array(),
		filemtime( get_template_directory() . '/assets/css/code-blocks.css' )
	);

	// Prism.js syntax highlighting
	wp_enqueue_style(
		'prism-theme',
		get_template_directory_uri() . '/assets/vendor/prism/prism-tomorrow.min.css',
		array(),
		'1.29.0'
	);

	wp_enqueue_script(
		'prism-core',
		get_template_directory_uri() . '/assets/vendor/prism/prism.min.js',
		array(),
		'1.29.0',
		true
	);

	wp_enqueue_script(
		'prism-autoloader',
		get_template_directory_uri() . '/assets/vendor/prism/prism-autoloader.min.js',
		array( 'prism-core' ),
		'1.29.0',
		true
	);

	// Set autoloader CDN path for language grammars
	wp_add_inline_script(
		'prism-autoloader',
		'Prism.plugins.autoloader.languages_path = "https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/";',
		'before'
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
