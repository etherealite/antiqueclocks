<?php
namespace Curios;

use Curios\Wordpress\PluginBootProvider;
use Curios\App\AppProvider;

class Bootstrap {

    protected $plugin_path;

    public function plugin_boot($pluginPath): void
    {
        if (!class_exists(__NAMESPACE__ .'\App')) {
            require $this->plugin_path . 'vendor/autoload.php';
        }
        $bootProvider = new PluginBootProvider(new AppProvider());
        $app = new App($bootProvider);
        // $app->boot();
    }
}