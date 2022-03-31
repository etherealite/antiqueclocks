<?php
namespace Curios\Wordpress;

class CustomObjects {

    private array $unregisteredPosts = [];

    private array $unregisteredTaxs = [];

    public function __construct(array $classes)
    {
        foreach ($classes as  $class) {
            $instance = new $class();

            assert(
                $instance instanceof CustomPostType || 
                $instance instanceof CustomTaxonomy
            );

            if ($instance instanceof CustomPostType) {
                $this->unregisteredPosts[$class] = $instance;
            }

            if ($instance instanceof CustomTaxonomy) {
                $this->unregisteredTaxs[$class] = $instance;
            }

        }
    }

    /**
     * Call the registration method of each custom object
     * 
     * The custom taxonomies must come before the posts when a post
     * uses a taxonmy's rewrite tag in it's own rewrite slug.
     */
    public function register()
    {
        while ($this->unregisteredTaxs) {
            $object = array_pop($this->unregisteredTaxs);
            $object->register();
        }
        while ($this->unregisteredPosts) {
            $object = array_pop($this->unregisteredPosts);
            $object->register();
        }

    }
}