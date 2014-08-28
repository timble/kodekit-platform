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
 * @author  Dave Li <http://nooku.assembla.com/profile/daveli>
 * @package Component\Varnish
 */
class DispatcherBehaviorVarnishable extends Library\ControllerBehaviorAbstract
{
	protected function _beforeSend(Library\DispatcherContextInterface $context)
	{
		$response = $context->response;
		$request  = $context->request;

		$model = $context->getSubject()->getController()->getModel();
		$entity	= $model->fetch();

		$name = Library\StringInflector::singularize($entity->getIdentifier()->name);

		$headers = $response->getHeaders();

		if($headers->has('x-'. $name .'-ids')) {
			$previous = explode(';', $headers->get('x-'. $name .'-ids'));

			$ids = array_unique(array_merge($previous, array_keys($entity->toArray())));
		} else {
			$ids = array_keys($entity->toArray());
		}

		$headers->set('x-'. $name .'-ids', implode(';', $ids));
	}
}