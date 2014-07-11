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
 * Varnishable Database Behavior
 *
 * @author  Dave Li <http://nooku.assembla.com/profile/daveli>
 * @package Nooku\Component\Varnish
 */
class DatabaseBehaviorVarnishable extends Library\DatabaseBehaviorAbstract
{
	protected function _initialize(Library\ObjectConfig $config)
	{
		$config->append(array(
			'priority'     => self::PRIORITY_LOWEST,
		));

		parent::_initialize($config);
	}

	protected function _afterUpdate(Library\DatabaseContext $context)
	{
		$this->_clear();
	}

	/**
	 * Ban the object in Varnish
	 *
	 * @param Library\DatabaseContext	$context A database context object
	 * @return void
	 */
	protected function _clear()
	{
		$modified   = $this->getTable()->filter($this->getProperties(true));
		$identifier = $this->getMixer()->getIdentifier();

		// Make sure that we only ban if data has been changed.
		if($modified) {
			$varnish = $this->getObject('com:varnish.database.row.socket');
			$varnish->connect();

			$varnish->ban('obj.http.x-'. $identifier->name .'-IDs ~ '. $this->id);
		}
	}
}