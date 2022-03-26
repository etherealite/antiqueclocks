<?php
namespace Curios;

use Pimple\Container as PimpleContainer;
use Psr\Container\ContainerInterface;

/**
 * PSR-11 compliant wrapper.
 *
 * @author (original) Pascal Luna <skalpa@zetareticuli.org>
 * @author Etherealite <seclayer@gmail.com>
 */
class Container implements ContainerInterface
{
    // private ?PimpleContainer $pimple;

    // public function __construct()
    // {
    //     $this->pimple = new PimpleContainer();
    // }

    public function get(string $id)
    {
        return $this->pimple[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->pimple[$id]);
    }
}