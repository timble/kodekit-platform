<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Revisions;

use Nooku\Library;

/**
 * Revisable Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Revisions
 */
class ControllerBehaviorRevisable extends Library\ControllerBehaviorAbstract
{
	protected function _beforeControllerRender(Library\CommandContext $context)
	{
	    $this->attachToolbar('com:revisions.controller.toolbar.revisable');
	}
}