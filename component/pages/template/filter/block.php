<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Block Template Filter
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
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

        foreach($modules as $module) {
            $blocks[] = $module;
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
        $blocks  = parent::hasBlocks($name);
        $modules = $this->getObject('pages.modules')->count(array('position' => $name));

        return (bool) ($blocks || $modules);
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
                ->params($block['attribs'])
                ->layout('module')
                ->id($block->id)
                ->render();
        }
        else $result = parent::_renderBlock($block);

        return $result;
    }
}