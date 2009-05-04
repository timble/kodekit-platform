<?php
/**
 * @version 	$Id:factory.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package		Koowa_Loader
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Loader Adpater for the Joomla! framework
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Loader
 * @subpackage 	Adapter
 */
class KLoaderAdapterJoomla implements KLoaderAdapterInterface
{
	/**
	 * Load the class
	 * 
	 * @param string  The class name
	 * @return string|false	Returns the path on success FALSE on failure
	 */
	public function load($class) 
	{	
		// If class start with a 'J' it is a Joomla framework class and we handle it
		if(substr($class, 0, 1) == 'J')
		{
			if(JLoader::load($class)) {
				return true;
			}
		}

        return false;
	}
}