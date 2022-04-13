<?php
namespace Curios\App\Wordpress;

use \WP_SCREEN;

use Curios\App\Wordpress\CollectablePostType;
use Curios\App\Wordpress\ManufacturerPostType;

class AdminExtentions {


    public function register(): void
    {
        $this->globalAlterations();

        add_action('current_screen', [$this, 'currentScreen']);
    }

    public function currentScreen(WP_SCREEN $screen): void
    {
        if ($screen->base === 'post') {
            if ($screen->post_type === CollectablePostType::slug()) {
                /** Prevent wordpres from fetching patterns from online sources */
                add_filter('should_load_remote_block_patterns', fn($b) => false);
                /** change place holder text for new posts */
                add_filter('enter_title_here', fn($t) => 'Add model name');
                /** Limit the blocks available to the inserter */
                add_filter('allowed_block_types_all', function ($allowed_types, $editer_context) {
                    if (empty($editer_context->post)){
                        return $allowed_types;
                    }
                    /** allow nothing */
                    return [];
                }, 10, 2);
            }
        }
    }

    public function globalAlterations(): void
    {
        $this->changeMenuPositions();
    }

    public function changeMenuPositions(): void
    {
        $builtinPostType = get_post_type_object('post');
        $builtinPostType->menu_position = 25;
    }
}