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

		$identifier = $model->getIdentifier().'.ids';

		$name = Library\StringInflector::singularize($entity->getIdentifier()->name);

		$previous = $context->user->get($identifier);

		$context->user->set($identifier, array_keys($entity->toArray()));

		if(is_array($previous)) {
			$context->user->set($identifier, array_unique(array_merge($previous, array_keys($entity->toArray()))));
		}

		header('x-'.$name.'-ids: '.implode('; ', $context->user->get($identifier)));
	}
}