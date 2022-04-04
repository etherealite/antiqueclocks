<?php
namespace Curios\App\Wordpress;

use \WP_SCREEN;

use Curios\App\Wordpress\CollectablePostType;
use Curios\App\Wordpress\ManufacturerPostType;

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
                add_filter('allowed_block_types_all', function ($allowed_types, $editer_context) {
                    if (empty($editer_context->post)){
                        return $allowed_types;
                    }
                    return ['create-block/sample-block'];
                }, 10, 2);
            }
        }



    }

    public function changeMenuPositions(): void
    {
        $builtinPostType = get_post_type_object('post');
        $builtinPostType->menu_position = 25;
    }
}