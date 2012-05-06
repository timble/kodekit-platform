<?php
/**
 * @version		$Id: dispatcher.php 1485 2012-02-10 12:32:02Z johanjanssens $
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