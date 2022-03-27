<?php
namespace Curios;

/**
 * Compound Service Provider Interface
 * 
 * A provider that registers other providers
 */
interface CompoundServiceProviderInterface 
{
    /**
     * return an array of providers
     * 
     * Array of providers must be in order that their
     * services should be instantiated
     */
    public function providers();
}