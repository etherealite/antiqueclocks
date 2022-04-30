<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package antiqueclocks
 * @since 1.0.0
 */

/**
 * The theme version.
 *
 * @since 1.0.0
 */
define( 'ANTIQUECLOCKS_VERSION', wp_get_theme()->get( 'Version' ) );

/**
 * Add theme support for block styles and editor style.
 *
 * @since 1.0.0
 *
 * @return void
 */
function antiqueclocks_setup() {
	add_theme_support( 'wp-block-styles' );
	add_editor_style( './assets/css/style-shared.min.css' );

	/*
	 * Load additional block styles.
	 * See details on how to add more styles in the readme.txt.
	 */
	$styled_blocks = [ 'button', 'file', 'latest-comments', 'latest-posts', 'post-title', 'quote', 'search' ];
	foreach ( $styled_blocks as $block_name ) {
		$args = array(
			'handle' => "antiqueclocks-$block_name",
			'src'    => get_theme_file_uri( "assets/css/blocks/$block_name.min.css" ),
			$args['path'] = get_theme_file_path( "assets/css/blocks/$block_name.min.css" ),
		);
		// Replace the "core" prefix if you are styling blocks from plugins.
		wp_enqueue_block_style( "core/$block_name", $args );
	}

}
add_action( 'after_setup_theme', 'antiqueclocks_setup' );

/**
 * Enqueue the CSS files.
 *
 * @since 1.0.0
 *
 * @return void
 */
function antiqueclocks_styles() {
	wp_enqueue_style(
		'antiqueclocks-style',
		get_stylesheet_uri(),
		[],
		ANTIQUECLOCKS_VERSION
	);
	wp_enqueue_style(
		'antiqueclocks-shared-styles',
		get_theme_file_uri( 'assets/css/style-shared.min.css' ),
		[],
		ANTIQUECLOCKS_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'antiqueclocks_styles' );


/**
 * Prevent the Gutenberg plugin from trying to autoload block patterns
 * 
 * @author etherealite
 * 
 * @return void
 */
function disable_guttenberg_plugin_pattern_loader() {
	remove_action ('init', 'gutenberg_register_theme_block_patterns');
}
add_action('after_setup_theme', 'disable_guttenberg_plugin_pattern_loader');

// Filters.
require_once get_theme_file_path( 'inc/filters.php' );

// Webfonts.
require_once get_theme_file_path( 'inc/fonts.php' );

// Block variation example.
require_once get_theme_file_path( 'inc/register-block-variations.php' );

// Block style examples.
require_once get_theme_file_path( 'inc/register-block-styles.php' );

// Block pattern and block category examples.
require_once get_theme_file_path( 'patterns/register-block-patterns.php' );
