<?php
namespace Curios\App\Wordpress;

use \WP_Error;

use Curios\Wordpress\CustomTaxonomy;

use Curios\App\Wordpress\CollectablePostType;

class ManufacturerTaxonomy extends CustomTaxonomy {

    public const Slug = 'manufacturer';

    private bool $rollingBack = false;


    public function register(): void 
    {
        $slug = $this::Slug;

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
            [CollectablePostType::Slug], 
            $args
        );

        add_action('set_object_terms', [$this, 'oneToOneConstraint'], 10, 6);
    }

    public function oneToOneConstraint(
        int $object_id,
        array $terms,
        array $tt_ids,
        string $taxonomy,
        bool $append,
        array $old_tt_ids
    ): void {
        $slug = $this::Slug;
        if ($taxonomy !== $slug || count($terms) <= 1 || $this->rollingBack) {
            return;
        }

        $this->rollingBack = true;
        // wp_remove_object_terms($object_id, $terms, $slug);
        wp_set_object_terms($object_id, $old_tt_ids, $slug);
        $this->rollingBack = false;
        wp_die(new WP_Error(
            'curios_constraint_failure',
            'A post is limited to a single Manufacturer.'
        ));
    }

}
