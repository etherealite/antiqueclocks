<?php
namespace Curios\App\Wordpress;

use \Wp_Post;

use Curios\Wordpress\CustomPostType;

class CollectablePostType extends CustomPostType {

    public static function slug(): string {
        return 'curios_collectable';
    }

    public function register(): void 
    {
        $slug = $this::slug();
        $typeTaxSlug = CollectableTypeTaxonomy::slug();
        $labels = [];
        $args = [
            'label'                 => __( 'Collectable', 'curios' ),
            'description'           => __( 'A collectable item', 'curios' ),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail'),
            'taxonomies'            => [$typeTaxSlug],
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-coffee',
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => false,
            'rewrite'               => [
                'slug' => "%{$typeTaxSlug}%",
                'with_front' => true,
                'pages' => false,
                'feed' => false,
                'ep_mask' => EP_NONE,
                'walk_dirs' => false,
            ],
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
        ];
        register_post_type($slug, $args);

        $post_type_object = get_post_type_object($slug);
        $post_type_object->template = [
            // array( 'core/image' ),
        ];

        // global $wp_rewrite;
        // $slug = $this::slug();
        // $wp_rewrite->add_rewrite_tag( '%collectable_name%', '([^/]+)', 'collectable_name=' );
        // $wp_rewrite->add_permastruct('collectable', '%collectable_type%/%collectable_name%',
        //     [
        //         'with_front' => false,
        //         'ep_mask' => EP_NONE,
        //         'paged' => false,
        //     ]
        // );
        

        // add_filter('rewrite_rules_array', function($rules) {
        //     global $wp_rewrite;
        //     $struct = $wp_rewrite->get_extra_permastruct('collectable');
        //     $collectableRules = $wp_rewrite->generate_rewrite_rules($struct, EP_NONE, false, false, false);
        //     return array_merge($rules, $collectableRules);
        // });

        add_filter( 'wp_insert_post_data', [$this, 'wpInsertPostData'], 10, 4);
        add_filter( 'post_type_link', [$this, 'postTypeLink'], 10, 3 );
    }

    public function wpInsertPostData($data, $postarr, $unsanitized_postarr)
    {
        return $data;
    }

    public function postTypeLink(string $link, WP_Post $post, string $leavename): string
    {
        $taxSlug = CollectableTypeTaxonomy::slug();
        $postSlug = $this::slug();
        if ($post->post_type !== $postSlug) {
            return $link;
        }

        $term = ($terms = get_the_terms($post, $taxSlug)) ? $terms[0] : false;
        $defaultTerm = get_taxonomy($taxSlug);
        $termSlug = $term ? $term->slug :  $defaultTerm->default_term['slug'];
        return str_replace("%{$taxSlug}%", $termSlug, $link);
    }
}