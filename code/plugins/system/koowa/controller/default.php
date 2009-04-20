<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Controller
 * @copyright	Copyright (C) 2007 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Default Controller Class
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Controller
 */
class KControllerDefault extends KControllerAbstract
{
	/**
	 * Display a single item
	 */
	public function read()
	{
		$layout	= KInput::get('layout', 'get', 'cmd', null, 'default' );
		
		$this->getView()
			->setLayout($layout)
			->display();
	}
}
