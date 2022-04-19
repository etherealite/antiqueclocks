<?php
namespace Curios\Wordpress;

class Lifecycle {

    private ?string $requestType;

    public function __construct(
        CustomObjects $customObjects, 
        $adminExtentions,
        $blocks,
        $plugin
    )
    {
        $this->requestType = null;
        $this->customObjects = $customObjects;
        $this->adminExtensions = $adminExtentions;
        $this->blocks = $blocks;
        $this->plugin = $plugin;
    }

    public function start($app): void
    {   
        $this->app = $app;
        $runStages = function($context) {
            $this->dispatch($context);
        };
        $this->requestContext = new RequestContext($runStages);
        $this->requestContext->runSync();
    }

    public function dispatch($context): void
    {
        $this->requestType = $context;

        if (defined('WP_INSTALLING')){
            // do nothing
            return;
        }

        add_action('init', [$this, 'init']);

        if ($requestType = $this->requestContext::ADMIN){
            add_action('init', [$this, 'adminInit']);
        }
    }

    public function init(): void
    {
        $this->customObjects->register();
        $this->blocks->register($this->app->getPluginPath());
        $this->plugin->init();
    }

    public function adminInit(): void
    {
       $this->adminExtensions->register(); 
    }
}