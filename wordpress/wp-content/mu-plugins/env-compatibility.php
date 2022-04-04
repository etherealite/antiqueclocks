<?php
/**
 * Environment Compatibility
 *
 * Tweaks wordpress core features to be compatible with the hosting and development environments
 * of this project. 
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       Environment Compatibility
 * Plugin URI:        http://github.com/etherealite
 * Description:       Tweaks wordpress core features to be compatible with hosting and development environments.
 * Version:           1.0.0
 * Author:            Ethrealite
 * Text Domain:       env-compatibility
 * Domain Path:       /languages
 */


/**
 * Prevet wordpress from running Background Updates site health check
 */
 add_filter('site_status_tests', function($tests) {
  unset($tests['async']['background_updates']);
  return $tests;
 }, 10, 1);

if (wp_get_environment_type() === 'development') {
  add_action('init', fn() => flush_rewrite_rules());

  /**
   * Administrator's sessions never expire
   */
  add_filter('auth_cookie_expiration', function($seconds, $user_id, $remember){
    $user_meta = get_userdata($user_id);
    $user_roles = $user_meta->roles;
    if (in_array('administrator', $user_roles)) {
      return  PHP_INT_MAX - time() - 5;
    }
    return $seconds;
  }, 99, 3);
}