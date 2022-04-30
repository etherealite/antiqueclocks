<?php
namespace Curios\App\Wordpress;

use \WP_SCREEN;
use \WP_Post;

use Curios\App\Wordpress\{
    CustomObjects,
    CollectablePostType, 
    ManufacturerPostType,
    CollectableTypeTaxonomy,
    ManufacturerTaxonomy
};


class AdminExtentions {

    public function register(): void
    {
        $this->globalAlterations();

        add_action('current_screen', [$this, 'currentScreen']);
    }

    public function currentScreen(WP_SCREEN $screen): void
    {
        if ($screen->base === 'post') {
            if ($screen->post_type === CollectablePostType::Slug) {
                /** Prevent wordpres from fetching patterns from online sources */
                add_filter('should_load_remote_block_patterns', fn($b) => false);
                /** change place holder text for new posts */
                add_filter('enter_title_here', fn($t) => 'Add model name');
                /** Limit the blocks available to the inserter */
                add_filter('allowed_block_types_all', function ($allowed_types, $editer_context) {
                    if (empty($editer_context->post)){
                        return $allowed_types;
                    }
                    /** allow everything for now */
                    return $allowed_types;
                }, 10, 2);
            }
        }
        if ($screen->base === 'edit') {
            if ($screen->post_type === CollectablePostType::Slug) {
                add_filter('manage_posts_columns', function($posts_columns) {
                    $posts_columns[
                        'taxonomy-' . ManufacturerTaxonomy::Slug
                    ] = 'Manufacturer';
                    $posts_columns[
                        'taxonomy-' . CollectableTypeTaxonomy::Slug
                    ] = 'Type';
                    return $posts_columns;
                });
            }
        }
    }

    public function globalAlterations(): void
    {
        $this->changeMenuPositions();
    }

    public function changeMenuPositions(): void
    {
        /** @var \WP_Post_Type */
        $builtinPostType = get_post_type_object('post');
        $builtinPostType->menu_position = 25;
    }
}