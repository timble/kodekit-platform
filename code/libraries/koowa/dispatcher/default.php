<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Default controller dispatcher
 * 
 * The default dispatcher mplements a signleton. After instantiation the object can
 * be access using the mapped lib.koowa.dispatcher identifier.
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Dispatcher
 */

class KDispatcherDefault extends KDispatcherAbstract 
{ 
	/**
	 * Force creation of a singleton
	 *
	 * @return KDispatcherDefault
	 */
	public static function instantiate($config = array())
	{
		static $instance;
		
		if ($instance === NULL) 
		{
			//Create the singleton
			$classname = $config->identifier->classname;
			$instance = new $classname($config);
			
			//Add the factory map to allow easy access to the singleton
			KFactory::map('lib.koowa.dispatcher', $config->identifier);
		}
		
		return $instance;
	}
}