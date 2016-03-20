<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-revisions for the canonical source repository
 */

namespace Kodekit\Component\Revisions;

use Kodekit\Library;

/**
 * Revisable Controller Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Revisions
 */
class ControllerBehaviorRevisable extends Library\ControllerBehaviorAbstract
{
    protected function _beforeBrowse(Library\ControllerContextInterface $context)
	{
        $state = $context->getSubject()->getModel()->getState();

        //If we are filtering for all the trashed entities, decorate the actionbar with the revisable toolbar
        if($state->trashed == true && $this->hasToolbar('actionbar')) {
            $this->getToolbar('actionbar')->decorate('com:revisions.controller.toolbar.revisable');
        }
	}
}