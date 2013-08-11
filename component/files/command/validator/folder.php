<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Folder Validator Command
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class CommandValidatorFolder extends CommandValidatorNode
{
	protected function _databaseBeforeSave($context)
	{
        $result = parent::_databaseBeforeSave($context);

        if ($result)
        {
            $filter = $this->getObject('com:files.filter.folder.uploadable');
            $result = $filter->validate($context->getSubject());
            if ($result === false)
            {
                $errors = $filter->getErrors();
                if (count($errors)) {
                    $context->getSubject()->setStatusMessage(array_shift($errors));
                }
            }
        }

        return $result;
	}
}