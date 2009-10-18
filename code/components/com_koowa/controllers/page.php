<?php
/** 
 * @version		$Id: person.php 246 2009-10-12 22:41:50Z johan $
 * @package		Koowa
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Form Controller
 *
 * @package		Koowa
 */
class KoowaControllerPage extends KControllerPage
{
	/**
	 * Display a single item
	 *
	 * @return void
	 */
	protected function _actionRead()
	{
		$layout	= KRequest::get('get.layout', 'cmd', 'default' );
		
		$this->getView()
			->setLayout($layout)
			->display();
	}
}