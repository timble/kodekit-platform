<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * File Validator Command
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class DatabaseValidatorNode extends Library\CommandHandlerAbstract
{
	protected function _beforeSave(Library\DatabaseContext $context)
	{
		$entity = $context->getSubject();

		if (!$entity->isNew() && !$entity->overwrite)
        {
            $translator = $this->getObject('translator');
            $entity->setStatusMessage($translator('Resource already exists and overwrite switch is not present.'));
			return false;
		}

		return true;
	}

	protected function _beforeCopy(Library\DatabaseContext $context)
	{
		$entity = $context->getSubject();

        $translator = $this->getObject('translator');

		if (!$entity->isModified('destination_folder') && !$entity->isModified('destination_name'))
        {
            $entity->setStatusMessage($translator('Please supply a destination.'));
			return false;
		}

		if ($entity->fullpath === $entity->destination_fullpath)
        {
            $entity->setStatusMessage($translator('Source and destination are the same.'));
			return false;
		}

		$dest_adapter = $entity->getContainer()->getAdapter($entity->getIdentifier()->name, array(
			'path' => $entity->destination_fullpath
		));

		$exists = $dest_adapter->exists();

		if ($exists)
		{
			if (!$entity->overwrite)
            {
                $entity->setStatusMessage($translator('Destination resource already exists.'));
				return false;
			}
            else $entity->overwritten = true;
		}

		return true;
	}

	protected function _beforeMove(Library\DatabaseContext $context)
	{
		return $this->_beforeCopy($context);
	}
}
