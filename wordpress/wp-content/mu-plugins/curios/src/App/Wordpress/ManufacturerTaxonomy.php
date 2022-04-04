<?php
namespace Curios\App\Wordpress;

use \WP_Error;

use Curios\Wordpress\CustomTaxonomy;

use Curios\App\Wordpress\CollectablePostType;

class ManufacturerTaxonomy extends CustomTaxonomy {

    private bool $rollingBack = false;

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

        add_action('set_object_terms', [$this, 'oneToOneConstraint'], 10, 6);
    }

    public function oneToOneConstraint(
        $object_id,
        $terms,
        $tt_ids,
        $taxonomy,
        $append,
        $old_tt_ids
    ): void {
        $slug = $this::slug();
        if ($taxonomy !== $slug || count($terms) <= 1 || $this->rollingBack) {
            return;
        }
        if ($this->rollingBack && count($old_tt_ids) > 1) {
            wp_die(new WP_Error(
                'curios_rollback_failure',
                'Rollback would violate single term per post constraint'
            ));
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