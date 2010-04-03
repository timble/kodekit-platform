<?php
/**
* @version      $Id$
* @category		Koowa
* @package		Koowa_Toolbar
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
*/

/**
 * Default Toolbar class
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Toolbar
 */
class KToolbarDefault extends KToolbarAbstract
{
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
        parent::__construct($config);
		
		$app 		= $this->_identifier->application;
		$package 	= $this->_identifier->package;
		$name 		= $this->_identifier->name;
		
		if(KInflector::isPlural($name))
		{		 
			//Create the toolbar
			$this->append('new')
				 ->append('edit')
				 ->append('delete');	
		}
		else
		{
			// Create the toolbar
			$this->append('save')
				 ->append('apply')
    			 ->append('cancel');
		}
	}
}