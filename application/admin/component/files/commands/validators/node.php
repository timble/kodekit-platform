<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * File Validator Command Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */
class ComFilesCommandValidatorNode extends Framework\Command
{
	protected function _databaseBeforeSave(Framework\CommandContext $context)
	{
		$row = $context->getSubject();

		if (!$row->isNew() && !$row->overwrite) {
			$row->setStatusMessage(JText::_('Resource already exists and overwrite switch is not present.'));
			return false;
		}

		return true;
	}

	protected function _databaseBeforeCopy(Framework\CommandContext $context)
	{
		$row = $context->getSubject();

		if (!array_intersect(array('destination_folder', 'destination_name'), $row->getModified())) {
            $row->setStatusMessage(JText::_('Please supply a destination.'));
			return false;
		}

		if ($row->fullpath === $row->destination_fullpath) {
            $row->setStatusMessage(JText::_('Source and destination are the same.'));
			return false;
		}

		$dest_adapter = $row->container->getAdapter($row->getIdentifier()->name, array(
			'path' => $row->destination_fullpath
		));
		$exists = $dest_adapter->exists();

		if ($exists)
		{
			if (!$row->overwrite) {
                $row->setStatusMessage(JText::_('Destination resource already exists.'));
				return false;
			} else {
				$row->overwritten = true;

			}
		}


		return true;
	}

	protected function _databaseBeforeMove(Framework\CommandContext $context)
	{
		return $this->_databaseBeforeCopy($context);
	}
}