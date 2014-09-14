<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * File Validator Command
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class DatabaseValidatorFile extends DatabaseValidatorNode
{
	protected function _beforeSave(Library\DatabaseContext $context)
	{
		$entity = $context->getSubject();

		if (is_string($entity->file) && !is_uploaded_file($entity->file))
		{
            // remote file
            $file = $this->getObject('com:files.model.entity.url');
            $file->setProperties(array('file' => $entity->file));

            if (!$file->get('contents')) {
                throw new Library\ControllerExceptionActionFailed('File cannot be downloaded');
            }

            $entity->contents = $file->contents;

			if (empty($entity->name))
			{
				$uri  = $this->getObject('lib:http.url', array('url' => $entity->file));
	        	$path = $uri->toString(Library\HttpUrl::PATH | Library\HttpUrl::FORMAT);
	        	if (strpos($path, '/') !== false) {
	        		$path = basename($path);
	        	}

	        	$entity->name = $path;
			}
		}

        $result = parent::_beforeSave($context);

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
