<?php
/**
 * @package     Nooku_Server
 * @subpackage  Attachments
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;

/**
 * Attachment Controller Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Attachments
 */
class AttachmentsControllerAttachment extends Library\ControllerModel
{
	protected function _initialize(Library\ObjectConfig $config)
	{
		$config->append(array(
            'model'   => 'com:attachments.model.attachments',
			'request' => array(
				'view' => 'attachments'
			)
		));
		
		parent::_initialize($config);
	}
}