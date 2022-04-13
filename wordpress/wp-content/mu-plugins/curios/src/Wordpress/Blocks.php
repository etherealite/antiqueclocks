<?php
namespace Curios\Wordpress;

class Blocks {

    private array $unregisteredBocks = [];

    public function __construct(array $blocks)
    {
        foreach ($blocks as  $block) {
            $this->unregisteredBlocks[] = $block;
        }
    }

    public function register($pluginPath): void
    {
        $base = $pluginPath . '/build/blocks/';
        while ($this->unregisteredBlocks) {
            $blockName = array_pop($this->unregisteredBlocks);
            register_block_type($base . $blockName);
        }
    }
}