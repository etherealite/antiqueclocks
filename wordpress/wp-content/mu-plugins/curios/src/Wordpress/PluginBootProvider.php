<?php
namespace Curios\Wordpress;

use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;
use Pimple\Container as pimpleContainer;

use Curios\ServiceProviderInterface;
use Curios\Container;

class PluginBootProvider implements ServiceProviderInterface {

    protected $pimpleContainer;

    public function __construct($appProvider){
        $this->appProvider = $appProvider;
    }

    public function register(Container $container): void
    {
        $this->container = $container;
        $this->always($container);
    }

    public function always($container): void
    {
        $container['monolog'] = function ($c) {
            // create a log channel, note: other plugins might be using monolog
            $logger = new Logger('curios');
            $logger->pushHandler(new ErrorLogHandler());
            return $logger;
        };
        $container['lifecycle'] = function($c) {
            return new Lifecycle();
        };
    }
}