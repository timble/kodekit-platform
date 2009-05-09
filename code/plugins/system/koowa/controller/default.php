<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Default Controller Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Controller
 */
class KControllerDefault extends KControllerAbstract
{
	/**
	 * Display a single item
	 * 
	 * @return void
	 */
	public function read()
	{
		$layout	= KRequest::get('get.layout', 'cmd', 'default' );
		
		$this->getView()
			->setLayout($layout)
			->display();
	}
}
