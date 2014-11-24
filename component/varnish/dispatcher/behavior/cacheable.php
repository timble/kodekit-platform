<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Varnish;

use Nooku\Library;

/**
 * Dispatcher Varnishable Behavior
 *
 * @author  Dave Li <http://github.com/daveli>
 * @package Component\Varnish
 */
class DispatcherBehaviorCacheable extends Library\ControllerBehaviorAbstract
{
	protected function _beforeSend(Library\DispatcherContextInterface $context)
	{
		$this->setCacheHeaders($context);
	}

	public function setCacheHeaders($context)
	{
		$response	= $context->response;
		$controller = $context->getSubject()->getController();

		$model		= $controller->getModel();
		$entities	= $model->fetch();

		$headers = $response->getHeaders();

		if (!$model->getState()->isUnique()) {
			$headers->set('x-entities', (string) $controller->getIdentifier());
		} else {
			$headers->set('x-entities', (string) $controller->getIdentifier().':'.implode(';', array_keys($entities->toArray())));
		}
	}
}