<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Folder Validator Command Class
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class CommandValidatorFolder extends CommandValidatorNode
{
	protected function _databaseBeforeSave($context)
	{
		return parent::_databaseBeforeSave($context) && $this->getService('com:files.filter.folder.uploadable')->validate($context);
	}
}