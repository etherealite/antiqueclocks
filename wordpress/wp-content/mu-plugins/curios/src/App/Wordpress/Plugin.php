<?php
namespace Curios\App\Wordpress;

class Plugin {
    public const Version = '0.0.1';
    // TODO: add schema version and provide migrations

    public function init()
    {
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure('/blog/%postname%/');
    }
}