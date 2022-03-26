<?php
namespace Curios\App\Wordpress;

use Curios\Wordpress\CustomPostType;

class CollectablePostType extends CustomPostType {

    public function register(): void 
    {
        $labels = array(
            'name'                  => _x( 'Collectables', 'Post Type General Name', 'curios' ),
            'singular_name'         => _x( 'Collectable', 'Post Type Singular Name', 'curios' ),
            'menu_name'             => __( 'Post Types', 'curios' ),
            'name_admin_bar'        => __( 'Post Type', 'curios' ),
            'archives'              => __( 'Item Archives', 'curios' ),
            'attributes'            => __( 'Item Attributes', 'curios' ),
            'parent_item_colon'     => __( 'Parent Item:', 'curios' ),
            'all_items'             => __( 'All Items', 'curios' ),
            'add_new_item'          => __( 'Add New Item', 'curios' ),
            'add_new'               => __( 'Add New', 'curios' ),
            'new_item'              => __( 'New Item', 'curios' ),
            'edit_item'             => __( 'Edit Item', 'curios' ),
            'update_item'           => __( 'Update Item', 'curios' ),
            'view_item'             => __( 'View Item', 'curios' ),
            'view_items'            => __( 'View Items', 'curios' ),
            'search_items'          => __( 'Search Item', 'curios' ),
            'not_found'             => __( 'Not found', 'curios' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'curios' ),
            'featured_image'        => __( 'Featured Image', 'curios' ),
            'set_featured_image'    => __( 'Set featured image', 'curios' ),
            'remove_featured_image' => __( 'Remove featured image', 'curios' ),
            'use_featured_image'    => __( 'Use as featured image', 'curios' ),
            'insert_into_item'      => __( 'Insert into item', 'curios' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'curios' ),
            'items_list'            => __( 'Items list', 'curios' ),
            'items_list_navigation' => __( 'Items list navigation', 'curios' ),
            'filter_items_list'     => __( 'Filter items list', 'curios' ),
        );
        $args = array(
            'label'                 => __( 'Collectable', 'curios' ),
            'description'           => __( 'A collectable item', 'curios' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'thumbnail', 'custom-fields' ),
            'taxonomies'            => array( 'curios_maker' ),
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
        );
        register_post_type( 'curios_collectable', $args );
    }
}