<?php
namespace Curios\App;

use Curios\Wordpress\CustomObjects;
use Curios\Wordpress\Blocks;
use Curios\App\Wordpress\Plugin;
use Curios\App\Wordpress\AdminExtentions;

class AppProvider {

    public function register($container): void
    {
        $container['wp_plugin'] = function($c) {
            return new Plugin();
        };
        $container['wp_blocks'] = function($c) {
            return new Blocks([
                'collectable-image',
                'collectable-gallery',
                'collectable-manufacturing',
                'collectable-sale',
                'collectable',
            ]);
        };
        $container['wp_custom_objects'] = function($c) {
            return new CustomObjects([
                Wordpress\CollectablePostType::class,
                Wordpress\CollectableTypeTaxonomy::class,
                Wordpress\ManufacturerTaxonomy::class,
            ]);
        };
        $container['wp_admin_extensions'] = function($c) {
            return new AdminExtentions();
        };
    }
}
