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
 * Auto-detect programming language from code content.
 *
 * @param string $code The code content (may contain HTML entities).
 * @return string Language identifier or empty string.
 */
function chubes_detect_code_language( $code ) {
	$text = html_entity_decode( wp_strip_all_tags( $code ) );

	// PHP
	if ( preg_match( '/(<\?php|\$this->|function\s+\w+\s*\(|->|namespace\s|use\s+[A-Z]|\barray\s*\(|\bnew\s+\w+)/', $text ) ) {
		return 'php';
	}
	// JavaScript / TypeScript
	if ( preg_match( '/(const\s+\w+\s*=|let\s+\w+\s*=|=>\s*{|import\s+.*from\s|require\s*\(|module\.exports|console\.\w+)/', $text ) ) {
		return 'javascript';
	}
	// CSS
	if ( preg_match( '/(\{[^}]*:\s*[^;]+;|@media\s|@import\s|\.[\w-]+\s*\{|#[\w-]+\s*\{)/', $text ) ) {
		return 'css';
	}
	// SQL
	if ( preg_match( '/\b(SELECT|INSERT INTO|UPDATE\s+\w+\s+SET|CREATE TABLE|ALTER TABLE|DROP TABLE|WHERE|JOIN)\b/i', $text ) ) {
		return 'sql';
	}
	// Bash / Shell
	if ( preg_match( '/(^(sudo|apt|npm|wp |composer |git |ssh |chmod|chown|mkdir|curl|wget|systemctl|cd |ls |cat |grep |echo )|^\#!\/bin\/(bash|sh)|&&\s*\\\\?$|\|\s*grep)/m', $text ) ) {
		return 'bash';
	}
	// INI / systemd
	if ( preg_match( '/^\[(Unit|Service|Install|Timer|Socket|Mount)\]/m', $text ) ) {
		return 'ini';
	}
	// JSON
	if ( preg_match( '/^\s*[\[{]/', $text ) && preg_match( '/["\']:\s*/', $text ) ) {
		return 'json';
	}
	// YAML
	if ( preg_match( '/^[\w-]+:\s*.+$/m', $text ) && preg_match( '/^\s{2,}[\w-]+:/m', $text ) ) {
		return 'yaml';
	}
	// HTML
	if ( preg_match( '/<(div|span|section|article|header|footer|html|body|head|form|input|button)\b/i', $text ) ) {
		return 'markup';
	}
	// Nginx / Apache config
	if ( preg_match( '/(server\s*{|location\s+[\\/~]|proxy_pass|<VirtualHost|RewriteRule)/', $text ) ) {
		return 'nginx';
	}

	return '';
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

	// Auto-detect language from code content if none specified
	if ( ! $language && preg_match( '/<code[^>]*>(.*?)<\/code>/si', $block_content, $code_m ) ) {
		$language = chubes_detect_code_language( $code_m[1] );
	}

	// Ensure language class on both <pre> and <code> for Prism.js
	$lang_class = 'language-' . esc_attr( $language );
	if ( $language ) {
		if ( strpos( $block_content, '<code class="language-' ) === false ) {
			$block_content = str_replace( '<code>', '<code class="' . $lang_class . '">', $block_content );
		}
		if ( strpos( $block_content, 'language-' ) === false || ! preg_match( '/pre[^>]*class="[^"]*language-/', $block_content ) ) {
			$block_content = preg_replace( '/(<pre[^>]*class="[^"]*)(")/', '$1 ' . $lang_class . '$2', $block_content );
		}
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
 * Enhance raw <pre><code> blocks in post content (e.g. from REST API posts)
 * Adds wp-block-code class, language detection, header bar, and copy button.
 *
 * @param string $content Post content.
 * @return string Enhanced content.
 */
function chubes_enhance_raw_code_blocks( $content ) {
	// Match <pre> tags that DON'T already have wp-block-code (already handled by render_block)
	if ( strpos( $content, '<pre' ) === false ) {
		return $content;
	}

	$sprite_url = get_template_directory_uri() . '/assets/icons/chubes.svg';

	$content = preg_replace_callback(
		'/<pre(?P<pre_attrs>[^>]*)>\s*<code(?P<code_attrs>[^>]*)>(?P<code>.*?)<\/code>\s*<\/pre>/si',
		function ( $m ) use ( $sprite_url ) {
			$pre_attrs  = $m['pre_attrs'];
			$code_attrs = $m['code_attrs'];
			$code_body  = $m['code'];

			// Skip if already enhanced (has wp-block-code)
			if ( strpos( $pre_attrs, 'wp-block-code' ) !== false && strpos( $pre_attrs, 'code-block-header' ) !== false ) {
				return $m[0];
			}

			// Detect language from pre or code class
			$language = '';
			if ( preg_match( '/language-(\w+)/', $pre_attrs . ' ' . $code_attrs, $lang_m ) ) {
				$language = $lang_m[1];
			}

			// Auto-detect language from code content if none specified
			if ( ! $language ) {
				$language = chubes_detect_code_language( $code_body );
			}

			// Ensure language class on <code>
			if ( $language && strpos( $code_attrs, 'language-' ) === false ) {
				if ( strpos( $code_attrs, 'class=' ) !== false ) {
					$code_attrs = preg_replace( '/class="([^"]*)"/', 'class="$1 language-' . esc_attr( $language ) . '"', $code_attrs );
				} else {
					$code_attrs = trim( $code_attrs ) . ' class="language-' . esc_attr( $language ) . '"';
				}
			}

			// Ensure wp-block-code and language class on <pre>
			$pre_classes = 'wp-block-code';
			if ( $language ) {
				$pre_classes .= ' language-' . esc_attr( $language );
			}
			if ( strpos( $pre_attrs, 'class=' ) !== false ) {
				// Merge into existing class attribute
				if ( strpos( $pre_attrs, 'wp-block-code' ) === false ) {
					$pre_attrs = preg_replace( '/class="([^"]*)"/', 'class="$1 ' . $pre_classes . '"', $pre_attrs );
				} elseif ( $language && strpos( $pre_attrs, 'language-' ) === false ) {
					$pre_attrs = preg_replace( '/class="([^"]*)"/', 'class="$1 language-' . esc_attr( $language ) . '"', $pre_attrs );
				}
			} else {
				$pre_attrs .= ' class="' . $pre_classes . '"';
			}

			// Build header
			$header  = '<div class="code-block-header">';
			$header .= '<span class="code-block-language">' . esc_html( $language ) . '</span>';
			$header .= '<button class="code-copy-btn" aria-label="Copy code">';
			$header .= '<svg><use href="' . esc_url( $sprite_url ) . '#icon-copy"></use></svg>';
			$header .= '</button>';
			$header .= '</div>';

			return '<pre' . $pre_attrs . '>' . $header . '<code' . $code_attrs . '>' . $code_body . '</code></pre>';
		},
		$content
	);

	return $content;
}
add_filter( 'the_content', 'chubes_enhance_raw_code_blocks', 20 );

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
