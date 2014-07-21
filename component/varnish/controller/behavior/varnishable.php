<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Varnish;

use Nooku\Library;

/**
 * Varnishable Controller Behavior
 *
 * @author  Dave Li <http://nooku.assembla.com/profile/daveli>
 * @package Nooku\Component\Revisions
 */
class ControllerBehaviorVarnishable extends Library\ControllerBehaviorAbstract
{
	protected function _afterBrowse(Library\ControllerContextInterface $context)
	{
		$this->_setHeaders($context);
	}

	protected function _afterRead(Library\ControllerContextInterface $context)
	{
		$this->_setHeaders($context);
	}

	protected function _setHeaders($context)
	{
		$model	= $this->getModel();
		$entity	= $model->fetch();

		$name = Library\StringInflector::singularize($entity->getIdentifier()->name);

		$headers = $this->getObject('response')->getHeaders();

		if($headers->has('x-'. $name .'-ids')) {
			$previous = explode(';', $headers->get('x-'. $name .'-ids'));

			$ids = array_unique(array_merge($previous, array_keys($entity->toArray())));
		} else {
			$ids = array_keys($entity->toArray());
		}

		$headers->set('x-'. $name .'-ids', implode(';', $ids));
	}
}