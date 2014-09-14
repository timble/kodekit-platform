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
 * Abstract Controller
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
abstract class ControllerAbstract extends Library\ControllerModel
{
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

	protected function _actionCopy(Library\ControllerContextInterface $context)
	{
		$entity = $this->getModel()->fetch();

		if(!$entity->isNew())
		{
			$entity->setProperties(Library\ObjectConfig::unbox($context->request->data->toArray()));

			//Only throw an error if the action explicitly failed.
			if($entity->copy() === false)
			{
				$error = $entity->getStatusMessage();
                throw new Library\ControllerExceptionActionFailed($error ? $error : 'Copy Action Failed');
			}
			else
            {
                $context->response->setStatus(
                    $entity->getStatus() === $entity::STATUS_CREATED ? HttpResponse::CREATED : HttpResponse::NO_CONTENT
                );
            }
		}
		else throw new Library\ControllerExceptionResourceNotFound('Resource Not Found');

		return $entity;
	}

	protected function _actionMove(Library\ControllerContextInterface $context)
	{
		$entity = $this->getModel()->fetch();

		if(!$entity->isNew())
		{
			$entity->setProperties(Library\ObjectConfig::unbox($context->request->data->toArray()));

			//Only throw an error if the action explicitly failed.
			if($entity->move() === false)
			{
				$error = $entity->getStatusMessage();
                throw new Library\ControllerExceptionActionFailed($error ? $error : 'Move Action Failed');
			}
			else
            {
                $context->response->setStatus(
                    $entity->getStatus() === $entity::STATUS_CREATED ? HttpResponse::CREATED : HttpResponse::NO_CONTENT
                );
            }
		}
		else throw new Library\ControllerExceptionResourceNotFound('Resource Not Found');

		return $entity;
	}
}
