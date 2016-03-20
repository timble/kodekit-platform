<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-articles for the canonical source repository
 */

namespace Kodekit\Component\Articles;

use Kodekit\Library;

/**
 * Article Controller Actionbar
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Articles
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