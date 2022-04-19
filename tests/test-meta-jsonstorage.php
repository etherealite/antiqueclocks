<?php

use Curios\Wordpress\Object_Meta_Json_Storage;


class Test_Post_Meta_Json_Storage extends WP_UnitTestCase {

    protected static $post_id;

    public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory) {
        self::$post_id = $factory->post->create( array( 'post_type' => 'page') );
    }

    
    public function test_remove_hooks_method_removes_sanitize_hooks() {
        register_meta( 'post', 'flight_info', array(
            'object_subtype' => 'page',
            'type' => 'object',
            'single' => true,
        ) );

        register_meta( 'post', 'flight_extended', array(
            'object_subtype' => 'page',
            'type' => 'object',
            'single' => true,
        ) );

        $json_storage = new Object_Meta_Json_Storage();
        $json_storage->enable_meta( 'post', 'page', 'flight_info' );
        $json_storage->enable_meta( 'post', 'page', 'flight_extended' );
        $json_storage->register_hooks();

        $this->assertEquals(
            PHP_INT_MAX,
            has_filter(
                "sanitize_post_meta_flight_info_for_page",
                [$json_storage, 'sanitize_filter'],
                PHP_INT_MAX, 3
            )
        );

        $this->assertEquals(
            PHP_INT_MAX,
            has_filter(
                "sanitize_post_meta_flight_extended_for_page",
                [$json_storage, 'sanitize_filter'],
                PHP_INT_MAX, 3
            )
        );


        $json_storage->remove_hooks();

        $this->assertFalse(
            has_filter(
                "sanitize_post_meta_flight_info_for_page",
                [$json_storage, 'sanitize_filter'],
                PHP_INT_MAX, 3
            )
        );

        $this->assertFalse(
            has_filter(
                "sanitize_post_meta_flight_extended_for_page",
                [$json_storage, 'sanitize_filter'],
                PHP_INT_MAX, 3
            )
        );
    }


    public function test_remove_hooks_method_removes_get_meta_type_hooks() {

        register_meta( 'post', 'flight_info', array(
            'object_subtype' => 'page',
            'type' => 'object',
            'single' => true,
        ) );

        $json_storage = new Object_Meta_Json_Storage();
        $json_storage->enable_meta( 'post', 'page', 'flight_info' );
        $json_storage->register_hooks();

        $this->assertEquals(
            PHP_INT_MAX,
            has_filter(
                "get_post_metadata",
                [$json_storage, 'get_metadata_filter'],
                PHP_INT_MAX, 5
            )
        );

        $json_storage->remove_hooks();

        $this->assertFalse(
            has_filter(
                "get_post_metadata",
                [$json_storage, 'get_metadata_filter'],
                PHP_INT_MAX, 5
            )
        );
    }


    public function test_retrieved_value_matches_input_value() {

        register_meta( 'post', 'flight_info', array(
            'object_subtype' => 'page',
            'type' => 'object',
            'single' => true,
        ) );


        $json_storage = new Object_Meta_Json_Storage();
        $json_storage->enable_meta('post', 'page', 'flight_info');
        $json_storage->register_hooks();

        $input_data = array(
            'price' => '$6.70',
            'date' => 'July 5th',
            'seats' => array( 'a1', 'a2', 'b1' ),
        );

        $mid = add_metadata( 'post', self::$post_id, 'flight_info', $input_data);

        $retrieved_data = get_metadata( 'post', self::$post_id, 'flight_info', true);

        $this->assertEquals($input_data, $retrieved_data);
    }


    public function test_stored_as_json_string_in_db() {
        register_meta( 'post', 'flight_info', array(
            'object_subtype' => 'page',
            'type' => 'object',
            'single' => true,
        ) );


        $json_storage = new Object_Meta_Json_Storage();
        $json_storage->enable_meta( 'post', 'page', 'flight_info' );
        $json_storage->register_hooks();
        $input_data = array(
            'price' => '$6.70',
            'date' => 'July 5th',
            'seats' => array( 'a1', 'a2', 'b1' => array( 'has_exit' ) ),
        );

        $mid = add_metadata( 'post', self::$post_id, 'flight_info', $input_data);

        $raw_db_value = $this->query_meta_db_value_by_id( $mid, 'post' );

        $input_json = json_encode($input_data);

        $raw_db_value = $this->query_meta_db_value_by_id( $mid, 'post' );

        $this->assertEquals($input_data, json_decode($raw_db_value, true));
    }


    private function query_meta_db_value_by_id( $mid, $meta_type ){
        global $wpdb;

        $table = _get_meta_table( $meta_type );

        $object_type_col = sanitize_key( $meta_type . '_id' );

        $meta_list = $wpdb->get_col("SELECT meta_value, $object_type_col, meta_id FROM $table WHERE meta_id = $mid;");

        return $meta_list[0] ?? null;
    }
}