<?php
namespace Curios;

use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;

use Pimple\Container as PimpleContainer;

use Curios\Wordpress\Lifecycle;

class CoreServiceProvider implements ServiceProviderInterface {

    public function register(PimpleContainer $container): void
    {
        $container['paths'] = [
            'plugin' => dirname(__DIR__),
            'base' => __DIR__,
        ];

        $container['monolog'] = function (PimpleContainer $c): Logger {
            // create a log channel, note: other plugins might be using monolog
            /** @var \Monolog\Logger */
            $logger = new Logger('curios');
            $logger->pushHandler(new ErrorLogHandler());
            return $logger;
        };

        $container['wp_lifecycle'] = function(PimpleContainer $c): Lifecycle {
            return new Lifecycle(
                $c['wp_custom_objects'],
                $c['wp_admin_extensions'],
                $c['wp_blocks'],
                $c['wp_plugin'],
            );
        };
    }
}