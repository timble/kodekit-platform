<?php
/**
 * @version 	$Id:factory.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package		Koowa_Loader
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Loader Adapter Interface
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Loader
 * @subpackage 	Adapter
 */
interface KLoaderAdapterInterface
{
	/**
	 * Load the class
	 * 
	 * @param string  The class name
	 * @return boolean Return TRUE on success, FALSE on failure
	 */
	public function load($class);
}