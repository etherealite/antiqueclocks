<?php

namespace Curios;

use Psr\Container\ContainerInterface;
use Pimple\Container as PimpleContainer;
use Curios\Container;

class App extends Container implements ContainerInterface {

    protected ?PimpleContainer $pimple;

    protected $bootProvider;

    protected string $path;

    public function __construct($bootProvider) {
        $this->pimple = new PimpleContainer();
        $this->bootProvider = $bootProvider;
    }

    public function boot(): void
    {
        $this->bootProvider->provide($this->pimple);
        $this->get('lifecycle')->start();
    }

}