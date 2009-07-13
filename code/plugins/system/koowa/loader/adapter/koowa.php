<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Loader
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Loader Adapter for the Koowa framework
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Loader
 * @subpackage 	Adapter
 * @uses 		Koowa
 */
class KLoaderAdapterKoowa implements KLoaderAdapterInterface
{
	/**
	 * Load a class based on a class name
	 *
	 * Is capable of autoloading Koowa library classes based on a camelcased
     * classname that represents the directory structure.
	 *
	 * @param string  The class name
	 * @return string|false	Returns the path on success FALSE on failure
	 */
	public function load($class)
	{
		// If class start with a 'K' it is a Koowa framework class and we handle it
		if(substr($class, 0, 1) == 'K')
		{
			$word  = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', substr_replace($class, '', 0, 1)));
			$parts = explode('_', $word);

			if(count($parts) > 1) {
				$path = str_replace('_', DS, $word);
			} else {
				$path = $word.DS.$word;
			}

			//Get the basepath
			$basepath = Koowa::getPath();

			if(!is_file($basepath.DS.$path.'.php')) {
				$path = $path.DS.array_pop($parts);
			}

			//Return the full path
			return $basepath.DS.$path.'.php';
		}

        return false;
	}
}