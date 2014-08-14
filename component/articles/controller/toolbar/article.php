<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Articles;

use Nooku\Library;

/**
 * Article Controller Actionbar
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Articles
 */
class ControllerToolbarArticle extends Library\ControllerToolbarActionbar
{
    /**
     * Add default toolbar commands
     * .
     * @param	Library\ControllerContextInterface	$context A controller context object
     */
    protected function _afterBrowse(Library\ControllerContextInterface $context)
    {
        parent::_afterBrowse($context);
        
        $this->addSeparator();
        $this->addEnable(array('label' => 'publish', 'attribs' => array('data-data' => '{published:1}')));
        $this->addDisable(array('label' => 'unpublish', 'attribs' => array('data-data' => '{published:0}')));
    }
}