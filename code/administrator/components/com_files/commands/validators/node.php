<?php
/**
 * @version     $Id: file.php 1352 2012-01-03 20:01:20Z ercanozkaya $
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * File Validator Command Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */
class ComFilesCommandValidatorNode extends KCommand
{
	protected function _databaseBeforeSave(KCommandContext $context)
	{
		$row = $context->caller;

		if (!$row->isNew() && !$row->overwrite) {
			$context->setError(JText::_('Resource already exists and overwrite switch is not present.'));
			return false;
		}

		return true;
	}

	protected function _databaseBeforeCopy(KCommandContext $context)
	{
		$row = $context->caller;

		if (!array_intersect(array('destination_folder', 'destination_name'), $row->getModified())) {
			$context->setError(JText::_('Please supply a destination.'));
			return false;
		}

		if ($row->fullpath === $row->destination_fullpath) {
			$context->setError(JText::_('Source and destination are the same.'));
			return false;
		}

		$dest_adapter = $row->container->getAdapter($row->getIdentifier()->name, array(
			'path' => $row->destination_fullpath
		));
		$exists = $dest_adapter->exists();

		if ($exists)
		{
			if (!$row->overwrite) {
				$context->setError(JText::_('Destination resource already exists.'));
				return false;
			} else {
				$row->overwritten = true;

			}
		}


		return true;
	}

	protected function _databaseBeforeMove(KCommandContext $context)
	{
		return $this->_databaseBeforeCopy($context);
	}
}