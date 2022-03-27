<?php
namespace Curios\App\Wordpress;

use Curios\Wordpress\Admin\PostEditScreen;
use Curios\App\Wordpress\ManufacturerPostType;

class ManufacturerPostEditScreen extends PostEditScreen {

    public static function postTypeSlug(): string
    {
        return ManufacturerPostType::slug();
    }

    public function register(): void
    {
        $this->setPostLabels();
        add_filter('enter_title_here', [$this, 'setTitlePlaceholder']);
    }

    public function setPostLabels(): void
    {
        $postTypeObject = get_post_type_object($this->postTypeSlug());
        $labels = $postTypeObject->labels;
        $labels->add_new_item = 'Add New Manufacturer';
    }
    public function setTitlePlaceholder(): string
    {
        return 'add Name';
    }
}