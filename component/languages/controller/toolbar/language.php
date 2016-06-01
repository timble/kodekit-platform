<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-languages for the canonical source repository
 */

namespace Kodekit\Component\Languages;

use Kodekit\Library;

/**
 * Language Controller Toolbar
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Languages
 */
class ControllerToolbarLanguage extends Library\ControllerToolbarActionbar
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
        $this->addEnable();
        $this->addDisable();
    }
}