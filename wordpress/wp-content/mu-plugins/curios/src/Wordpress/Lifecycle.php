<?php
namespace Curios\Wordpress;

class Lifecycle {

    private ?string $requestType;

    public function __construct()
    {
        $this->requestType = null;
        // $self->hooks = $hooks;
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

        // register hooks that run in all contexts

        $requestContext = $this->requestContext;
        $map = [
            $requestContext::ADMIN => $this->admin,
        ];
    }

    public function allContexts(): void
    {
    }
}