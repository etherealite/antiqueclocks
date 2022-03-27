<?php
namespace Curios\App;

use Curios\Wordpress\CustomObjects;
use Curios\App\Wordpress\AdminExtentions;

class AppProvider {

    public function register($container): void
    {
        $container['wp_custom_objects'] = function($c) {
            return new CustomObjects([
                Wordpress\CollectablePostType::class,
                Wordpress\ManufacturerTaxonomy::class,
                Wordpress\ManufacturerPostType::class,
            ]);
        };
        $container['wp_admin_extensions'] = function($c) {
            return new AdminExtentions();
        };
    }
}
