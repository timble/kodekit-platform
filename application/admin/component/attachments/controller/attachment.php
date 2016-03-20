<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Attachments;

use Kodekit\Library;
use Kodekit\Component\Attachments;

/**
 * Attachment Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Attachments
 */
class ControllerAttachment extends Attachments\ControllerAttachment
{
	protected function _initialize(Library\ObjectConfig $config)
	{
		$config->append(array(
            'behaviors' => array(
                'editable', 'persistable',
            ),
            'model' => 'com:attachments.model.attachments'
		));

		parent::_initialize($config);
	}
}