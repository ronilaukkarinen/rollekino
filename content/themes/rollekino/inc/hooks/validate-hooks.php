<?php
/**
 * Validate HTML: Remove WP REST API json links in <head> html
 * @link https://validator.w3.org/nu/#textarea
 */
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );

/**
 * Validate HTML: Remove unnecessary type attributes to suppress Nu HTML validator messages
 * @link https://validator.w3.org/nu/#textarea
 */
add_filter( 'style_loader_tag', __NAMESPACE__ . '\remove_type_attr', 10, 2 );
add_filter( 'script_loader_tag', __NAMESPACE__ . '\remove_type_attr', 10, 2 );
add_filter( 'autoptimize_html_after_minify', __NAMESPACE__ . '\remove_type_attr', 10, 2 );
function remove_type_attr( $tag, $handle = '' ) {
  return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag ); // phpcs:ignore
}

/**
 * Validate HTML: Remove Gutenberg inline styles (NB! Check if they are needed or not!)
 * @link https://validator.w3.org/nu/#textarea
 */
function remove_wp_block_library_css() {
  wp_dequeue_style( 'wp-block-library' );
  wp_dequeue_style( 'wp-block-library-theme' );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\remove_wp_block_library_css', 10 );

/**
 * Validate HTML: Remove even more unnecessary type attributes to suppress Nu HTML validator messages
 * @link https://validator.w3.org/nu/#textarea
 */
function remove_script_and_style_types() {
  add_theme_support( 'html5', [ 'script', 'style' ] );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\remove_script_and_style_types' );

/**
 * Validate HTML: Remove unnecessary WordPress injected .recentcomments
 * @link https://validator.w3.org/nu/#textarea
 */
function remove_recent_comments_style() {
  global $wp_widget_factory;
  remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', __NAMESPACE__ . '\remove_recent_comments_style' );

/**
 * Validate HTML: Disable the WordPress emojis
 * @link https://validator.w3.org/nu/#textarea
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	// Remove from TinyMCE
	add_filter( 'tiny_mce_plugins', __NAMESPACE__ . '\disable_emojis_tinymce' );
}
add_action( 'init', __NAMESPACE__ . '\disable_emojis' );

/**
 * Validate HTML: Filter out the tinymce emoji plugin.
 * @link https://validator.w3.org/nu/#textarea
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}
