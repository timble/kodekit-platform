<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-files for the canonical source repository
 */

namespace Kodekit\Component\Files;

use Kodekit\Library;

/**
 * File Validator Command
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
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
