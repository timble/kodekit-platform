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
     * @param   Library\ControllerContextModel  $context A controller context object
     */
    protected function _afterBrowse(Library\ControllerContextModel $context)
    {
        parent::_afterBrowse($context);

        $this->addSeparator();
        $this->addEnable(array(
            'label' => 'publish',
            'data'  => array('data' => array('published' => 1))
        ));

        $this->addDisable(array(
            'label' => 'unpublish',
            'data'  => array('data' => array('published' => 0))
        ));
    }
}