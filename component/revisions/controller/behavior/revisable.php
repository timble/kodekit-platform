<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Revisions;

use Nooku\Library;

/**
 * Revisable Controller Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Revisions
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