<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Http Dispatcher
 *
 * @author  Dave Li <http://nooku.assembla.com/profile/daveli>
 * @package Component\Articles
 */
class ArticlesDispatcherHttp extends Library\DispatcherHttp
{
	/**
	 * @param Library\ObjectConfig $config
	 */
	protected function _initialize(Library\ObjectConfig $config)
	{
		$config->append(array(
			'behaviors'        => array('com:varnish.dispatcher.behavior.varnishable'),
		));

		parent::_initialize($config);
	}
}