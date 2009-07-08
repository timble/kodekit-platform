<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Loader
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Loader Adpater for a component
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Loader
 * @subpackage 	Adapter
 * @uses		KInflector
 */
class KLoaderAdapterComponent implements KLoaderAdapterInterface
{
	/**
	 * Load the class
	 *
	 * @param string  The class name
	 * @return string|false	Returns the path on success FALSE on failure
	 */
	public function load($class)
	{
		$word  = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $class));
		$parts = explode('_', $word);

		$component = 'com_'.strtolower(array_shift($parts));

		if(JComponentHelper::getComponent($component, true)->enabled)
		{
			if(count($parts) > 1) {
				$path = KInflector::pluralize(array_shift($parts)).DS.implode(DS, $parts);
			} else {
				$path = $word;
			}

			//Get the basepath
			$basepath = JPATH_BASE.DS.'components';

			return $basepath.DS.$component.DS.$path.'.php';
		}

		return false;
	}
}