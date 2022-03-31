<?php
namespace Curios\App\Wordpress;

use \WP_SCREEN;

use Curios\App\Wordpress\CollectablePostType;
use Curios\App\Wordpress\ManufacturerPostType;
use Curios\App\Wordpress\ManufacturerPostEditScreen;

class AdminExtentions {


    public function register(): void
    {
        $this->changeMenuPositions();
        add_action('current_screen', [$this, 'currentScreen']);
    }

    public function currentScreen(WP_SCREEN $screen): void
    {
        if ($screen->base === 'post') {
            if ($screen->post_type === CollectablePostType::slug()) {
                add_filter('enter_title_here', fn($t) => 'add Model');
            }
        }



    }

    public function changeMenuPositions(): void
    {
        $builtinPostType = get_post_type_object('post');
        $builtinPostType->menu_position = 25;
    }
}