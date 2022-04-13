<?php
namespace Curios\App\Wordpress;

use Curios\Wordpress\CustomTaxonomy;

use Curios\App\Wordpress\CollectablePostType;

class CollectableTypeTaxonomy extends CustomTaxonomy {

    public static function slug(): string
    {
        return 'curios_collect_type';
    }

    public function register(): void 
    {
        $slug = $this::slug();
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
        // $rewrite = [
        //     'slug'                       => $this::slug(),
        //     'with_front'                 => false,
        //     'hierarchical'               => false,
        // ];
        $args = [
            'labels'                     => $labels,
            'default_term'               => [
                'name' => 'Clock',
                'slug' => 'clock',
            ],
            'hierarchical'               => false,
            'public'                     => false,
            'show_ui'                    => false,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => false,
            'rewrite'                    => [
                'slug' => $slug,
                'ep_mask' => EP_NONE,
                'pages' => false,
                'walk_dirs' => false,
            ],
            'show_in_rest'               => true,
            'rest_base'                  => $slug,
        ];
        register_taxonomy($slug,
            [CollectablePostType::slug()], 
            $args
        );

        // $this->rewrites();
    }
}