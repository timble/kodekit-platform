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
class ControllerBehaviorCacheable extends Library\BehaviorAbstract
{
	protected function _afterAdd(Library\ControllerContextInterface $context)
	{
		$identifier = $this->getMixer()->getIdentifier();

		$varnish = $this->getObject('com:varnish.database.row.socket');
		$varnish->connect();

		$varnish->ban('obj.http.x-lists ~ '. $identifier);
	}

	protected function _afterEdit(Library\ControllerContextInterface $context)
	{
		$entity		= $context->result;
		$identifier = $this->getMixer()->getIdentifier();

		if(!$entity->isModified()) {
			$varnish = $this->getObject('com:varnish.database.row.socket');
			$varnish->connect();
			$varnish->ban('obj.http.x-entities ~ '. $identifier.':'.$entity->id);
		}
	}
}