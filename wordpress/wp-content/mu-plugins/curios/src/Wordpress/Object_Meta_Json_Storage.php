<?php

namespace Curios\Wordpress;

use \Closure;

class Object_Meta_Json_Storage {

    private bool $hooks_registered = false;
    private bool $is_intercepting = false;
    private array $hook_meta = array();
    private array $meta_types = array();
    private Closure $remove_hooks_func;

    public function register_hooks() {
        if ($this->hooks_registered) {
            return;
        }
        $hooks_to_remove = array();
        foreach( $this->hook_meta as $meta ) {
            $meta_key = $meta[0];
            $object_type = $meta[1];
            $object_subtype = $meta[2]['object_subtype'];
            $this->meta_types[$object_type][$object_subtype][$meta_key] = true;
            add_filter(
                "sanitize_{$object_type}_meta_{$meta_key}_for_{$object_subtype}",
                [$this, 'sanitize_filter'],
                PHP_INT_MAX, 3
            );
            $hooks_to_remove[] = [
                "sanitize_{$object_type}_meta_{$meta_key}_for_{$object_subtype}", 
                [$this, 'sanitize_filter'],
                PHP_INT_MAX, 3
            ];
        }

        foreach ( array_keys( $this->meta_types ) as $meta_type ) {
            add_filter(
                "get_{$meta_type}_metadata",
                [$this, 'get_metadata_filter'],
                PHP_INT_MAX, 5
            );
            $hooks_to_remove[] = [
                "get_{$meta_type}_metadata",
                [$this, 'get_metadata_filter'],
                PHP_INT_MAX, 5
            ];
        }

        $this->hooks_registered = true;

        $this->remove_hooks_func = function() use (  $hooks_to_remove ):void {
            foreach ($hooks_to_remove as $hook) {
                remove_filter($hook[0], $hook[1], $hook[2]);
            }
        };
    }

    public function remove_hooks() {
        if ( ! $this->hooks_registered )
        {
            return;
        }
        ($this->remove_hooks_func)();

        $this->hooks_registered = false;
    }


    public function enable_meta( $object_type, $object_subtype, $meta_key ) {
        if ($this->hooks_registered) {
            return;
        }

        /** @var non-empty-array<string, array> */ 
        $descriptor = get_registered_meta_keys( $object_type, $object_subtype )[$meta_key];

        $type = $descriptor['type'];
        assert( ($type === 'object' || $type == 'array') );

        $this->hook_meta[] = [$meta_key, $object_type, ['object_subtype' => $object_subtype]];
    }

    public function sanitize_filter( $meta_value, $meta_key, $object_type ) 
    {
        return json_encode( $meta_value );
    }

    public function get_metadata_filter( $check, $object_id, $meta_key, $single, $meta_type )
    {
        $object_subtype = get_object_subtype( $meta_type, $object_id );
        if ( ! isset( $this->meta_types[$meta_type][$object_subtype][$meta_key] ) ) {
            return null;
        }
        if ($this->is_intercepting) {
            return null;
        }
        $this->is_intercepting = true;
        $metadata_json = get_metadata_raw( $meta_type, $object_id, $meta_key, $single );
        $this->is_intercepting = false;

        if (is_string($metadata_json)) {
            $metadata_json = array( $metadata_json );
        }
        $metadata = array_map(function($json) {
            return json_decode($json, true);
        }, $metadata_json);
        
        return $metadata;
    }
}