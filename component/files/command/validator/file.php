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
 * File Validator Command
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class CommandValidatorFile extends CommandValidatorNode
{
	protected function _databaseBeforeSave(Library\CommandContext $context)
	{
		$row = $context->getSubject();

		if (is_string($row->file) && !is_uploaded_file($row->file))
		{
			// remote file
			try
            {
				$file = $this->getObject('com:files.database.row.url');
				$file->setData(array('file' => $row->file));
				$file->load();
				$row->contents = $file->contents;

			} catch (DatabaseRowUrlException $e) {
				throw new \RuntimeException($e->getMessage(), $e->getCode());
			}

			if (empty($row->name))
			{
				$uri  = $this->getObject('lib:http.url', array('url' => $row->file));
	        	$path = $uri->toString(Library\HttpUrl::PATH | Library\HttpUrl::FORMAT);
	        	if (strpos($path, '/') !== false) {
	        		$path = basename($path);
	        	}

	        	$row->name = $path;
			}
		}

        $result = parent::_databaseBeforeSave($context);

        if ($result)
        {
            $filter = $this->getObject('com:files.filter.file.uploadable');
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
