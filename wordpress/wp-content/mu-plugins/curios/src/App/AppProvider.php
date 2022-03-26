<?php
namespace Curios\App;

class AppProvider {

    public function provide($app): void
    {
        $app->get('wp_custom_objects')->add([Wordpress\CollectablePostType::class]);
    }
}
