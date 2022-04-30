<?php

namespace Curios;

use Psr\Container\ContainerInterface;
use Pimple\Container as PimpleContainer;
use Curios\Container;

class App extends Container implements ContainerInterface {

    protected ?PimpleContainer $pimple;

    protected $bootProvider;

    protected string $basePath;
    protected string $pluginPath;

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

        $paths = $this->get('paths');
        $this->pluginPath = $paths['plugin'];
        $this->basePath = $paths['base'];
        $this->get('wp_lifecycle')->start($this);
    }

    public function getPluginPath(): string
    {
        return $this->pluginPath;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

}