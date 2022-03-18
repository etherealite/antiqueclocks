<?php
/**
 * Antique Clocks
 *
 * @package   antique-clocks
 * @author    Evan Bangham <seclayer@gmail.com>
 * @copyright Evan Bangham 2002
 * @license   N/A
 * @link      https://github.com/etherealite
 *
 * Plugin Name:     Antique Clocks
 * Plugin URI:      https://github.com/etherealite
 * Description:     glue for antique clock guide core functionality
 * Version:         0.0.1
 * Author:          Evan Bangham
 * Author URI:      https://github.com/etherealite
 * Text Domain:     the-plugin-name-text-domain
 * Domain Path:     /languages
 * Requires PHP:    7.4
 * Requires WP:     5.5.9
 * Namespace:       etherealite
 */

declare( strict_types = 1 );


/** Plugin disabled for now code copied from https://github.com/wp-strap/wordpress-plugin-boilerplate */
return;

/**
 * Define the default root file of the plugin
 *
 * @since 1.0.0
 */
const _THE_PLUGIN_NAME_PLUGIN_FILE = __FILE__;

/**
 * Load PSR4 autoloader
 *
 * @since 1.0.0
 */
$the_plugin_name_autoloader = require plugin_dir_path( _THE_PLUGIN_NAME_PLUGIN_FILE ) . 'vendor/autoload.php';

/**
 * Setup hooks (activation, deactivation, uninstall)
 *
 * @since 1.0.0
 */
register_activation_hook( __FILE__, [ 'ThePluginName\Config\Setup', 'activation' ] );
register_deactivation_hook( __FILE__, [ 'ThePluginName\Config\Setup', 'deactivation' ] );
register_uninstall_hook( __FILE__, [ 'ThePluginName\Config\Setup', 'uninstall' ] );

/**
 * Bootstrap the plugin
 *
 * @since 1.0.0
 */
if ( ! class_exists( '\ThePluginName\Bootstrap' ) ) {
	wp_die( __( '{{The Plugin Name}} is unable to find the Bootstrap class.', 'the-plugin-name-text-domain' ) );
}
add_action(
	'plugins_loaded',
	static function () use ( $the_plugin_name_autoloader ) {
		/**
		 * @see \ThePluginName\Bootstrap
		 */
		try {
			new \ThePluginName\Bootstrap( $the_plugin_name_autoloader );
		} catch ( Exception $e ) {
			wp_die( __( '{{The Plugin Name}} is unable to run the Bootstrap class.', 'the-plugin-name-text-domain' ) );
		}
	}
);

/**
 * Create a main function for external uses
 *
 * @return \ThePluginName\Common\Functions
 * @since 1.0.0
 */
function the_plugin_name(): \ThePluginName\Common\Functions {
	return new \ThePluginName\Common\Functions();
}