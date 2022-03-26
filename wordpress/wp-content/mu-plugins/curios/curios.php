<?php
/**
 * Curios
 *
 * Store and Present information about collectable goods 
 *
 * @link              http://github.com/etherealite
 * @since             1.0.0
 * @package           Curios
 *
 * @wordpress-plugin
 * Plugin Name:       Curios
 * Plugin URI:        http://github.com/etherealite
 * Description:       Store and Present information about collectable goods
 * Version:           1.0.0
 * Author:            Ethrealite
 * Text Domain:       env-compatibility
 * Domain Path:       /languages
 */

 
(static function() {
    $curios_plugin_path = plugin_dir_path(__FILE__);
    if (!class_exists('\Curios\Bootstrap')) {
        require $curios_plugin_path . 'src/Bootstrap.php';
    }
    $bootstrapper = new \Curios\Bootstrap();
    $bootstrapper->plugin_boot($curios_plugin_path,);
})();