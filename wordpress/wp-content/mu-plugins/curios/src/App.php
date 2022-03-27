<?php

namespace Curios;

use Psr\Container\ContainerInterface;
use Pimple\Container as PimpleContainer;
use Curios\Container;

class App extends Container implements ContainerInterface {

    protected ?PimpleContainer $pimple;

    protected $bootProvider;

    protected string $path;

    public function __construct(CompoundServiceProviderInterface $bootProvider) {
        $this->pimple = new PimpleContainer();
        $this->bootProvider = $bootProvider;
    }

    public function boot(): void
    {
        $compounded = $this->bootProvider->providers();

        foreach ($compounded as $provider)
        {
            $provider->register($this->pimple);
        }

        $this->get('wp_lifecycle')->start();
    }

}