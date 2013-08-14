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
 * Abstract Controller
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
abstract class ControllerAbstract extends Library\ControllerModel
{
	protected function _initialize(Library\ObjectConfig $config)
	{
		$config->append(array(
			'persistable'   => false,
		));

		parent::_initialize($config);
	}

	public function getRequest()
	{
		$request = parent::getRequest();

		//The "config" state is only used in HMVC requests and passed to the JS application
		if ($this->isDispatched()) {
			unset($request->query->config);
		}

        //Make sure we have a default container in the request.
        if(!$request->query->has('container')) {
            $request->query->container = 'files-files';
        }

		return $request;
	}

	protected function _actionCopy(Library\CommandContext $context)
	{
		$entity = $this->getModel()->getRow();

		if(!$entity->isNew())
		{
			$entity->setData(Library\ObjectConfig::unbox($context->request->data->toArray()));

			//Only throw an error if the action explicitly failed.
			if($entity->copy() === false)
			{
				$error = $entity->getStatusMessage();
                throw new Library\ControllerExceptionActionFailed($error ? $error : 'Copy Action Failed');
			}
			else
            {
                $context->response->setStatus(
                    $entity->getStatus() === Library\Database::STATUS_CREATED ? self::STATUS_CREATED : self::STATUS_UNCHANGED
                );
            }
		}
		else throw new Library\ControllerExceptionNotFound('Resource Not Found');

		return $entity;
	}

	protected function _actionMove(Library\CommandContext $context)
	{
		$entity = $this->getModel()->getRow();

		if(!$entity->isNew())
		{
			$entity->setData(Library\ObjectConfig::unbox($context->request->data->toArray()));

			//Only throw an error if the action explicitly failed.
			if($entity->move() === false)
			{
				$error = $entity->getStatusMessage();
                throw new Library\ControllerExceptionActionFailed($error ? $error : 'Move Action Failed');
			}
			else
            {
                $context->response->setStatus(
                    $entity->getStatus() === Library\Database::STATUS_CREATED ? self::STATUS_CREATED : self::STATUS_UNCHANGED
                );
            }
		}
		else throw new Library\ControllerExceptionNotFound('Resource Not Found');

		return $entity;
	}
}
