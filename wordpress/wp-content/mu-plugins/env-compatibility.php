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

  /**
   * Flush rewrite rules on changes to made to the git
   * working tree
   */
  
  $flush = static function(): void {
    if (! (defined('AUTO_FLUSH') && AUTO_FLUSH)) {
      return;
    }

    $now = time();
    $lastTime = get_option('last_flush_time');

    if($lastTime >= ($now - 1)) {
      return;
    }
    
    $repoRoot = dirname($_SERVER['DOCUMENT_ROOT']);
    $treeHash = exec($repoRoot . '/bin/working-tree-hash');
    $lastHash = get_option('last_flush_hash');
    if ($lastHash !== $treeHash) {
      flush_rewrite_rules();
      update_option('last_flush_hash', $treeHash, true);
      update_option('last_flush_time', $now, true);
    }
  };
  add_action('init', $flush);

  /**
   * Administrator's sessions never expire
   */
  add_filter('auth_cookie_expiration', function($seconds, $user_id, $remember){
    $user_meta = get_userdata($user_id);
    if (! $user_meta instanceof \WP_User) {
      return $seconds;
    }
    $user_roles = $user_meta->roles;
    if (in_array('administrator', $user_roles)) {
      return  YEAR_IN_SECONDS;
    }
    return $seconds;
  }, 99, 3);

  /**
   * Get rid of annoying post auto save notices by disabling autosave.
   */
  $disable_autosave = function(): void {

    $classic = function(): void {
      add_action( 'admin_init', function() {
        wp_deregister_script( 'autosave' );
      });
      define('AUTOSAVE_INTERVAL', PHP_INT_MAX);
    };

    /** TODO: This doesn't work at all */
    $block = function(): void {
      add_action('enqueue_block_editor_assets', function() {
        wp_register_script('disable-block-editor-autosave', false, 
          array( 'wp-edit-post', 'wp-blocks', 'wp-dom-ready' ), 
        '1.0', true);
        wp_add_inline_script('disable-block-editor-autosave', 
          '
          wp.domReady( function() {
            wp.data.dispatch( "core/editor" ).updateEditorSettings({
                autosaveInterval: Number.MAX_SAFE_INTEGER,
          });
          '
        );
      });
    };

    $classic();
    $block();
  };
  $disable_autosave();
}