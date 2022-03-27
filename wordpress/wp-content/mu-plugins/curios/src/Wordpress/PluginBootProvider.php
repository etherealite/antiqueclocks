<?php
namespace Curios\Wordpress;

use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;
use Pimple\Container as pimpleContainer;

use Curios\CompoundServiceProviderInterface;
use Curios\CoreServiceProvider;
use Curios\Container;

class PluginBootProvider implements CompoundServiceProviderInterface {

    protected $pimpleContainer;

    protected array $providers = [];

    public function __construct($appProvider){
        $coreServiceProvider = new CoreServiceProvider();
        $this->providers = [
            $appProvider,
            $coreServiceProvider,
        ];
    }

    public function providers(): array
    {
        return $this->providers;
    }


}