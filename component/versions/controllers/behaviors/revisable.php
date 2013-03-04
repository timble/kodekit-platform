<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

/**
 * Revisable Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Versions
 */
class ComVersionsControllerBehaviorRevisable extends KControllerBehaviorAbstract
{
	protected function _beforeControllerRender(KCommandContext $context)
	{
	    $this->attachToolbar('com://admin/versions.controller.toolbar.revisable');
	}
}