<?php
namespace Curios\App\Wordpress;

use Curios\Wordpress\CustomTaxonomy;

use Curios\App\Wordpress\CollectablePostType;

class CollectableTypeTaxonomy extends CustomTaxonomy {

    public const Slug = 'curios_collect_type';


    public function register(): void 
    {
        $slug = $this::Slug;
        $labels = [
            'name' => __( 'Types', 'curios' ),
            'singular_name' => __( 'Collectable Type', 'curios' ),
            'search_items' =>  __( 'Search Collectable Types', 'curios' ),
            'all_items' => __( 'All Collectable Types', 'curios' ),
            'parent_item' => __( 'Parent Collectable Type', 'curios' ),
            'parent_item_colon' => __( 'Parent Collectable Type:', 'curios' ),
            'edit_item' => __( 'Edit Collectable Type', 'curios' ),
            'update_item' => __( 'Update Collectable Type', 'curios' ),
            'add_new_item' => __( 'Add New Collectable Type', 'curios' ),
            'new_item_name' => __( 'New Collectable Type Name', 'curios' ),
        ];

        /**
        * @var array{
        *   labels?: string[],
        *   description?: string,
        *   public?: bool,
        *   publicly_queryable?: bool,
        *   hierarchical?: bool,
        *   show_ui?: bool,
        *   show_in_menu?: bool,
        *   show_in_nav_menus?: bool,
        *   show_in_rest?: bool,
        *   rest_base?: string,
        *   rest_namespace?: string,
        *   rest_controller_class?: string,
        *   show_tagcloud?: bool,
        *   show_in_quick_edit?: bool,
        *   show_admin_column?: bool,
        *   meta_box_cb?: bool|callable,
        *   meta_box_sanitize_cb?: callable,
        *   capabilities?: string[],
        *   rewrite?: bool|array,
        *   query_var?: string|bool,
        *   update_count_callback?: callable,
        *   default_term?: string|array,
        *   sort?: bool,
        *   args?: array,
        *   _builtin?: bool,
        * }
        */
        $args = [
            'labels'                     => $labels,
            'description'                => 'a rare, unusual, or intriguing object.',
            'public'                     => false,
            'publicly_queryable'         => true,
            'hierarchical'               => false,
            'show_ui'                    => false,
            'show_in_menu'               => false,
            'show_in_nav_menus'          => true,
            'show_in_rest'               => true,
            'rest_base'                  => $slug,
            'rest_namespace'             => null,
            'rest_controller_class'      => null,
            'show_tagcloud'              => false,
            'show_in_quick_edit'         => null,
            'show_admin_column'          => true,
            'meta_box_cb'               => null,
            'meta_box_sanitize_cb'      => null,
            'capabilities'              => [],
            'rewrite'                    => [
                'slug' => $slug,
                'ep_mask' => EP_NONE,
                'pages' => false,
                'walk_dirs' => false,
            ],
            'query_var'                 => null,
            'update_count_callback'        => null,
            'default_term'               => [
                'name' => 'Clock',
                'slug' => 'clock',
            ],
            'sort'                      => null,
            'args'                      => null,
            '_builtin'                  => false,
        ];

        register_taxonomy($slug,
            [CollectablePostType::Slug], 
            $args
        );
    }
}
