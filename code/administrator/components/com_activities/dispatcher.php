<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Activities
 * @copyright	Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Component Dispatcher
 *
 * @author      Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Activities
 */
class ComActivitiesDispatcher extends ComDefaultDispatcher
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'request' => array(
				'view' => 'activities'
			),
		));
	
		parent::_initialize($config);
	}
}