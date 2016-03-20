<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Block Template Filter
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Pages
 */
class TemplateFilterBlock extends Library\TemplateFilterBlock
{
    /**
     * Get the name blocks
     *
     * @param $name
     * @return array List of blocks
     */
    public function getBlocks($name)
    {
        $blocks  = parent::getBlocks($name);
        $modules = $this->getObject('pages.modules')->find(array('position' => $name));

        foreach($modules as $module)
        {
            if($module->canAccess()) {
                $blocks[] = $module;
            }
        }

        return array_reverse($blocks);
    }

    /**
     * Check if blocks exist
     *
     * @param $name
     * @return bool TRUE if blocks exist, FALSE otherwise
     */
    public function hasBlocks($name)
    {
        $blocks = $this->getBlocks($name);

        return (bool) count($blocks);
    }

    /**
     * Render a block
     *
     * @param array     $block   The block data
     * @return string   The rendered block
     */
    protected function _renderBlock($block)
    {
        $result = '';

        if($block instanceof ModelEntityModule)
        {
            $result = $this->getObject('com:pages.controller.module')
                ->params($block->attribs)
                ->layout('module')
                ->id($block->id)
                ->render();
        }
        else $result = parent::_renderBlock($block);

        return $result;
    }
}