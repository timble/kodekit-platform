<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-users for the canonical source repository
 */

namespace Kodekit\Component\Users;

use Kodekit\Library;

/**
 * User Controller Toolbar
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Users
 */
class ControllerToolbarUser extends Library\ControllerToolbarActionbar
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
		$this->addEnable();
		$this->addDisable();
		$this->addSeparator();
    }
}