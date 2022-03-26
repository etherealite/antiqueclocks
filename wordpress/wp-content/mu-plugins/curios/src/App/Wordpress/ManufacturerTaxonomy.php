<?php
namespace Curios\App\Wordpress;

use Curios\Wordpress\CustomPostType;

class ManufacturerTaxonomy {

    public function register(): void 
    {
        $labels = array(
            'name'                       => _x( 'Manufacturers', 'Taxonomy General Name', 'curios' ),
            'singular_name'              => _x( 'Manufacturer', 'Taxonomy Singular Name', 'curios' ),
            'menu_name'                  => __( 'Taxonomy', 'curios' ),
            'all_items'                  => __( 'All Items', 'curios' ),
            'parent_item'                => __( 'Parent Item', 'curios' ),
            'parent_item_colon'          => __( 'Parent Item:', 'curios' ),
            'new_item_name'              => __( 'New Item Name', 'curios' ),
            'add_new_item'               => __( 'Add New Item', 'curios' ),
            'edit_item'                  => __( 'Edit Item', 'curios' ),
            'update_item'                => __( 'Update Item', 'curios' ),
            'view_item'                  => __( 'View Item', 'curios' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'curios' ),
            'add_or_remove_items'        => __( 'Add or remove items', 'curios' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'curios' ),
            'popular_items'              => __( 'Popular Items', 'curios' ),
            'search_items'               => __( 'Search Items', 'curios' ),
            'not_found'                  => __( 'Not Found', 'curios' ),
            'no_terms'                   => __( 'No items', 'curios' ),
            'items_list'                 => __( 'Items list', 'curios' ),
            'items_list_navigation'      => __( 'Items list navigation', 'curios' ),
        );
        $rewrite = array(
            'slug'                       => 'manufacturer',
            'with_front'                 => true,
            'hierarchical'               => false,
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => false,
            'rewrite'                    => $rewrite,
            'show_in_rest'               => true,
            'rest_base'                  => 'manufacturer',
        );
        register_taxonomy( 'curios_manufacturer', array( 'curios_collectable' ), $args );
    }


}