<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Search Controller Class
 *
 * @author    	Arunas Mazeika <http://nooku.assembla.com/profile/amazeika>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 */
class ComSearchControllerSearch extends ComDefaultControllerDefault
{
	protected function _initialize(KConfig $config)
	{
		// The whole identifier must be provided as otherwise KControllerResource::setModel will
		// pluralize the provided string (search => searches).
		$config->append(array(
			'model'      => 'site::com.search.model.search',
			'behaviors'  => array('filter')
		));
		
		parent::_initialize($config);
	}
}