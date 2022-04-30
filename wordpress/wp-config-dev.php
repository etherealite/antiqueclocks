<?php

/**
 * Tweaks specific to the local docker development environment
 * 
 * This file will be included by the main wp-config.php file.
 */

$site_url = (
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http"
) . "://$_SERVER[HTTP_HOST]";

if (! defined('WP_HOME')) {
    define('WP_HOME', $site_url);
}
if (! defined('WP_SITEURL')) {
    define('WP_SITEURL', $site_url);
}

define('WP_DEBUG', true);
define('SCRIPT_DEBUG', true);

ini_set('assert.exception', '1');