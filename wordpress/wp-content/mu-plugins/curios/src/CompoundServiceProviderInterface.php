<?php
namespace Curios;

/**
 * Compound Service Provider Interface
 * 
 * A provider that registers other providers
 */
interface CompoundServiceProviderInterface extends ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Container $container);

    
    public function providers();
}