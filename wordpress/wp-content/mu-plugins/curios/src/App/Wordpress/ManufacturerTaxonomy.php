<?php
namespace Curios\App\Wordpress;

use Curios\Wordpress\CustomTaxonomy;

use Curios\App\Wordpress\CollectablePostType;

class ManufacturerTaxonomy extends CustomTaxonomy {

    public static function slug(): string
    {
        return 'manufacturer';
    }

    public function register(): void 
    {
        $slug = $this::slug();

        $labels = [
            'name' => __( 'Manufacturers', 'curios' ), /* name of the custom taxonomy */
            'singular_name' => __( 'Manufacturer', 'curios' ), /* single taxonomy name */
            'search_items' =>  __( 'Search Manufacturers', 'curios' ), /* search title for taxomony */
            'all_items' => __( 'All Manufacturers', 'curios' ), /* all title for taxonomies */
            'parent_item' => __( 'Parent Manufacturer', 'curios' ), /* parent title for taxonomy */
            'parent_item_colon' => __( 'Parent Manufacturer:', 'curios' ), /* parent taxonomy title */
            'edit_item' => __( 'Edit Manufacturer', 'curios' ), /* edit custom taxonomy title */
            'update_item' => __( 'Update Manufacturer', 'curios' ), /* update title for taxonomy */
            'add_new_item' => __( 'Add New Manufacturer', 'curios' ), /* add new title for taxonomy */
            'new_item_name' => __( 'New Manufacturer Name', 'curios' ) /* name title for taxonomy */
        ];
        $rewrite = [
            'slug'                       => $slug,
            'with_front'                 => false,
            'hierarchical'               => false,
        ];
        $args = [
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => false,
            'rewrite'                    => $rewrite,
            'show_in_rest'               => true,
            'rest_base'                  => $slug,
        ];

        register_taxonomy($slug,
            [CollectablePostType::slug()], 
            $args
        );
    }
}