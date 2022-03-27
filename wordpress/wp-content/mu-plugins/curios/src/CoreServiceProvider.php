<?php
namespace Curios;

use Curios\Wordpress\Lifecycle;

class CoreServiceProvider implements ServiceProviderInterface {

    public function register($container): void
    {
        $container['monolog'] = function ($c) {
            // create a log channel, note: other plugins might be using monolog
            $logger = new Logger('curios');
            $logger->pushHandler(new ErrorLogHandler());
            return $logger;
        };

        $container['wp_lifecycle'] = function($c) {
            return new Lifecycle(
                $c['wp_custom_objects'],
                $c['wp_admin_extensions'],
            );
        };
    }
}