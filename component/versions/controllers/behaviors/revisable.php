<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Versions;

use Nooku\Framework;

/**
 * Revisable Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Versions
 */
class ControllerBehaviorRevisable extends Framework\ControllerBehaviorAbstract
{
	protected function _beforeControllerRender(Framework\CommandContext $context)
	{
	    $this->attachToolbar('com://admin/versions.controller.toolbar.revisable');
	}
}