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

    public function register()
    {
        while ($this->unregisteredPosts) {
            $object = array_pop($this->unregisteredPosts);
            $object->register();
        }
        while ($this->unregisteredTaxs) {
            $object = array_pop($this->unregisteredTaxs);
            $object->register();
        }
    }
}