<?php
namespace Curios\Wordpress;

class Lifecycle {

    private ?string $requestType;

    public function __construct(CustomObjects $customObjects, $adminExtentions)
    {
        $this->requestType = null;
        $this->customObjects = $customObjects;
        $this->adminExtensions = $adminExtentions;
    }

    public function start(): void
    {
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
    }

    public function adminInit(): void
    {
       $this->adminExtensions->register(); 
    }
}