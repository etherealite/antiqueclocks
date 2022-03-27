<?php
namespace Curios\App\Wordpress;

use Curios\Wordpress\CustomTaxonomy;

class ManufacturerTaxonomy extends CustomTaxonomy {

    public function register(): void 
    {
        $labels = [
            'name' => __( 'Custom Tags', 'bonestheme' ), /* name of the custom taxonomy */
            'singular_name' => __( 'Custom Tag', 'bonestheme' ), /* single taxonomy name */
            'search_items' =>  __( 'Search Custom Tags', 'bonestheme' ), /* search title for taxomony */
            'all_items' => __( 'All Custom Tags', 'bonestheme' ), /* all title for taxonomies */
            'parent_item' => __( 'Parent Custom Tag', 'bonestheme' ), /* parent title for taxonomy */
            'parent_item_colon' => __( 'Parent Custom Tag:', 'bonestheme' ), /* parent taxonomy title */
            'edit_item' => __( 'Edit Custom Tag', 'bonestheme' ), /* edit custom taxonomy title */
            'update_item' => __( 'Update Custom Tag', 'bonestheme' ), /* update title for taxonomy */
            'add_new_item' => __( 'Add New Custom Tag', 'bonestheme' ), /* add new title for taxonomy */
            'new_item_name' => __( 'New Custom Tag Name', 'bonestheme' ) /* name title for taxonomy */
        ];
        $rewrite = [
            'slug'                       => 'manufacturer',
            'with_front'                 => true,
            'hierarchical'               => false,
        ];
        $args = [
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => false,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => false,
            'rewrite'                    => $rewrite,
            'show_in_rest'               => true,
            'rest_base'                  => 'manufacturer',
        ];
        register_taxonomy('curios_manufacturer',
            ['curios_collectable', 'curios_manufacturer'], $args
        );
    }


}