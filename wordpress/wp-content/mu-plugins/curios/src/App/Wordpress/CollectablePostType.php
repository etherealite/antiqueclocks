<?php
namespace Curios\App\Wordpress;

use \WP_Post;
use \WP_REST_Response;
use \WP_REST_Request;

use Curios\Wordpress\CustomPostType;

class CollectablePostType extends CustomPostType {

    private const Content = '<!-- wp:curios/collectable /-->';

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
            'supports'              => array('title', 'editor', 'thumbnail', 'custom-fields'),
            'taxonomies'            => [$typeTaxSlug],
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-coffee',
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => 'clocks',
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
            'template'              => [
                ['curios/collectable', [], []]
            ],
        ];
        register_post_type($slug, $args);

        $post_type_object = get_post_type_object($slug);
        $post_type_object->template_lock = 'all';

        $sale_schema = [
            'type' => 'object',
            'properties' => [
                'kind' => [
                    'type' => 'string',
                    'pattern' => '^auction$'
                ],
                'date' => [
                    'type' => 'string',
                    'format' => 'date-time'
                ],
                'realizedPrice' => [
                    'type' => 'string',
                    'pattern' => '^(0|([1-9]+[0-9]*))(\.[0-9]{1,2})?$'
                ],
                /**
                 * the pre-auction price estimate if this is an 
                 * auction sale.
                 */ 
                'estimate' => [
                    'type' => 'string',
                    'pattern' => '^(0|([1-9]+[0-9]*))(\.[0-9]{1,2})?$'
                ]
            ],
            'required' => ['realizedPrice']
        ];

        register_post_meta($slug, 'collectable_sale', [
            'single' => true,
            'type' => 'object',
            'show_in_rest' => [
                'schema' => $sale_schema
            ]
        ]);


        add_filter('post_type_link', [$this, 'postTypeLink'], 10, 3);

        add_filter('wp_insert_post_data', [$this, 'wpInsertPostData'], 10, 1);

        add_filter('rest_prepare_' . $slug, [$this, 'restPrepare'], 10 , 3);

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

    public function wpInsertPostData($data): array
    {
        if ($data['post_type'] !== $this::slug()) {
            return $data;
        }
        $data['post_content'] = '';
        return $data;
    }

    public function restPrepare(
        WP_REST_Response $response, 
        WP_Post $post, 
        WP_REST_Request $request
    )
    {
        $data = $response->get_data();
        $data['content']['raw'] = $this::Content;
        $response->set_data($data);
        return $response;
    }
}