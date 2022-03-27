<?php
namespace Curios\App\Wordpress;

use \WP_SCREEN;

use Curios\App\Wordpress\CollectablePostType;
use Curios\App\Wordpress\ManufacturerPostType;
use Curios\App\Wordpress\ManufacturerPostEditScreen;

class AdminExtentions {


    public function register(): void
    {
        add_action('current_screen', [$this, 'currentScreen']);
    }

    public function currentScreen(WP_SCREEN $screen): void
    {
        if ($screen->base === 'post') {
            if ($screen->post_type === CollectablePostType::slug()) {
                add_filter('enter_title_here', fn($t) => 'add Model');
            }
            elseif ($screen->post_type === ManufacturerPostType::slug()) {
                (new ManufacturerPostEditScreen())->register();
            }
        }

    }
}