<?php
namespace Curios\Wordpress;

use CustomPostType;
use CustomTaxonomy;

class CustomObjects {

    private array $classes = [];

    private array $unregisteredPosts = [];

    private array $unregisteredTaxs = [];

    public function add(array $classes)
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

        }
    }

    public function register()
    {
        while ($this->unregisteredPosts) {
            $object = array_pop($this->unregisteredPosts);
            $object->register();
        }
    }
}