<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Node Controller Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */

class ComFilesControllerDefault extends ComDefaultControllerDefault
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'persistable' => false,
			'request' => array(
				'container' => 'files-files'
			)
		));

		parent::_initialize($config);
	}

	public function getRequest()
	{
		$request = parent::getRequest();
		// "e_name" is needed to be compatible with com_content of Joomla
		if ($request->e_name) {
			$request->editor = $request->e_name;
		}

		// "config" state is only used in HMVC requests and passed to the JS application
		if ($this->isDispatched()) {
			unset($request->config);
		}

		$limit = $request->limit;

		//If limit is empty use default
		if(empty($limit)) {
			$limit = $this->_limit->default;
		}

		//Force the maximum limit
		if($limit > $this->_limit->max) {
			$limit = $this->_limit->max;
		}

		$request->limit = $limit;

		return $request;
	}

	protected function _actionCopy(KCommandContext $context)
	{
		$data = $this->getModel()->getItem();

		if(!$data->isNew())
		{
			$data->setData(KConfig::unbox($context->data));

			//Only throw an error if the action explicitly failed.
			if($data->copy() === false)
			{
				$error = $data->getStatusMessage();
				$context->setError(new KControllerException(
				   $error ? $error : 'Copy Action Failed', KHttpResponse::INTERNAL_SERVER_ERROR
				));

			}
			else $context->status = $data->getStatus() === KDatabase::STATUS_CREATED ? KHttpResponse::CREATED : KHttpResponse::NO_CONTENT;
		}
		else $context->setError(new KControllerException('Resource Not Found', KHttpResponse::NOT_FOUND));

		return $data;
	}

	protected function _actionMove(KCommandContext $context)
	{
		$data = $this->getModel()->getItem();

		if(!$data->isNew())
		{
			$data->setData(KConfig::unbox($context->data));

			//Only throw an error if the action explicitly failed.
			if($data->move() === false)
			{
				$error = $data->getStatusMessage();
				$context->setError(new KControllerException(
				   $error ? $error : 'Move Action Failed', KHttpResponse::INTERNAL_SERVER_ERROR
				));

			}
			else $context->status = $data->getStatus() === KDatabase::STATUS_CREATED ? KHttpResponse::CREATED : KHttpResponse::NO_CONTENT;
		}
		else $context->setError(new KControllerException('Resource Not Found', KHttpResponse::NOT_FOUND));

		return $data;
	}

	/**
	 * Overridden method to be able to use it with both resource and service controllers
	 */
	protected function _actionGet(KCommandContext $context)
	{
		if ($this->getIdentifier()->name == 'image' || ($this->getIdentifier()->name == 'file' && $this->getRequest()->format == 'html'))
		{
			//Load the language file for HMVC requests who are not routed through the dispatcher
			if(!$this->isDispatched()) {
				JFactory::getLanguage()->load('com_'.$this->getIdentifier()->package);
			}

			$result = $this->getView()->display();
			return $result;
		}

		return parent::_actionGet($context);

	}
}
