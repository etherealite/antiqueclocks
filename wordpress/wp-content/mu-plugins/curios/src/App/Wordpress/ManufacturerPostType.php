<?php
namespace Curios\App\Wordpress;

use Curios\Wordpress\CustomPostType;

class ManufacturerPostType extends CustomPostType {

    public static function slug(): string {
        return 'curios_manufacturer';
    }

    public function register(): void 
    {
        $slug = $this::slug();
        $labels = [];
        $args = [
            'label'                 => __( 'Manufacturer', 'curios' ),
            'description'           => __( 'A Manufacturer item', 'curios' ),
            'labels'                => $labels,
            'supports'              => array('title', 'thumbnail'),
            'taxonomies'            => array('curios_manufacturer_tax'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-coffee',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
        ];
        register_post_type($slug, $args);

        $post_type_object = get_post_type_object($slug);
        $post_type_object->template = array(
            array( 'core/image' ),
        );
    }
}