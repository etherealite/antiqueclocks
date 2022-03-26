<?php
namespace Curios\Wordpress;

class LifecycleStage {
    
    public function __construct($provider)
    {
        $this->provider = $provider;
    }
}