<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-attachments for the canonical source repository
 */

namespace Kodekit\Component\Attachments;

use Kodekit\Library;

/**
 * Attachments Controller Toolbar
 *
 * @author  Steven Rombauts <http://github.com/stevenrombauts>
 * @package Kodekit\Component\Attachments
 */
class ControllerToolbarAttachments extends Library\ControllerToolbarActionbar
{
	public function getCommands()
	{
		return array();
	}
}