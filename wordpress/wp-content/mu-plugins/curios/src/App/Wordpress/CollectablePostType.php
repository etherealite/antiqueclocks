<?php
namespace Curios\App\Wordpress;

use \WP_Error;
use \WP_Post;
use \WP_REST_Response;
use \WP_REST_Request;

use Curios\Wordpress\CustomPostType;
use Curios\Wordpress\Object_Meta_Json_Storage;

class CollectablePostType extends CustomPostType {

    /**
     * Post specific block template code
     */
    private const Content = '<!-- wp:curios/collectable /-->';

    /**
     * Slug key used to identify this post type by wordpress internals
     */
    public const Slug = 'curios_collectable';

    /**
     * Allows storing fields as a json string in the post_meta table
     * as apposed to a serialized php string.
     */
    private Object_Meta_Json_Storage $jsonAdapter;

    public function __construct(?Object_Meta_Json_Storage $jsonAdapter=null)
    {
        if ($jsonAdapter) {
            $this->jsonAdapter = $jsonAdapter;
        }
        else {
            $this->jsonAdapter = new Object_Meta_Json_Storage();
        }
    }


    /**
     * Register wordpress hooks to enable this custom post type
     */
    public function register(): void 
    {
        $slug = $this::Slug;
        $typeTaxSlug = CollectableTypeTaxonomy::Slug;
        $manufacturerTaxSlug = ManufacturerTaxonomy::Slug;
        $labels = [];
        $args = [
            'label'                 => __( 'Collectable', 'curios' ),
            'description'           => __( 'A collectable item', 'curios' ),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'custom-fields'),
            'taxonomies'            => [$typeTaxSlug, $manufacturerTaxSlug],
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
                'with_front' => false,
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

        /** @var \WP_Post_Type */
        $post_type_object = get_post_type_object($slug);
        $post_type_object->template_lock = 'all';

        $this->registerMeta();

        add_filter('post_type_link', [$this, 'postTypeLink'], 10, 3);

        add_filter('wp_insert_post_data', [$this, 'wpInsertPostData'], 10, 1);

        add_filter('rest_prepare_' . $slug, [$this, 'restPrepare'], 10 , 3);
    }

    
    /**
     * Register post meta fields for post type
     */
    protected function registerMeta(): void
    {
        $slug = $this::Slug;

        $sale_schema = [
            'type' => 'object',
            'properties' => [
                'kind' => [
                    'type' => 'string',
                    'pattern' => '^auction|other$'
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
                ],
            ],
            'required' => []
        ];

        register_post_meta($slug, 'collectable_sale', [
            'single' => true,
            'type' => 'object',
            'show_in_rest' => [
                'schema' => $sale_schema,
            ]
        ]);


        $this->jsonAdapter->enable_meta( 'post', $slug, 'collectable_sale' );
        $this->jsonAdapter->register_hooks();
    }


    /**
     * Replace taxonomy slug keys in permalinks
     * 
     * This takes the slug (taxonomy key) param passed in 
     * `register_taxonomy()` and replaces it with the default
     *  term's slug.
     */
    public function postTypeLink(
        string $link,
        WP_Post $post,
        bool $leavename
    ): string {
        if ($post->post_type !== $this::Slug) {
            return $link;
        }
        $taxSlug = CollectableTypeTaxonomy::Slug;
        $maybeTax = get_taxonomy($taxSlug);
 
        $typeTax = $maybeTax instanceof \WP_Taxonomy ? $maybeTax : null;

        $defaultTerm = $typeTax->default_term ?? null;
        $termSlug = $defaultTerm['slug'] ?? null;

        if ($termSlug === null) {
            throw new \Exception('Problem generating permalink');
        }

        return str_replace("%{$taxSlug}%", $termSlug, $link);
    }


    /**
     * Null out any writes to the post content out as an empty string
     * 
     * We want to keep the post content completly empty for the post 
     * spcecific block template to work
     */
    public function wpInsertPostData(array $data): array
    {
        if ($data['post_type'] !== $this::Slug) {
            return $data;
        }
        $data['post_content'] = '';
        return $data;
    }


    /** 
     * Prevent saving any post content on post save
     * 
     * Overwrites post content with a static string  matching the
     * gutenberg block code matching the post specific block 
     * template for this post.
     * 
     */
    public function restPrepare(
        WP_REST_Response $response, 
        WP_Post $post, 
        WP_REST_Request $request
    ): WP_REST_Response {

        $data = $response->get_data();
        $data['content']['raw'] = $this::Content;
        $response->set_data($data);
        return $response;
    }
}