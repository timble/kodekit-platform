<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

 /**
 * Template static class
 * * 
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Template
 */
class KTemplate
{

	/**
	 * Load a helper and pass the arguments
	 * 
	 * Additional arguments may be supplied and are passed to helper
	 *
	 * @param	string	Name of the helper, dot separated
	 * @param	mixed	Parameters to be passed to the helper
	 * @return 	string	Helper output
	 * @throws 	KTemplateException
	 */
	public static function loadHelper($type)
	{
		//Initialise variables
		$base  = 'lib.koowa.template.helper';
		$file  = 'default';
		$func  = $type;
		
		// Check to see if we need to load a helper file
		$parts = explode('.', $type);
		
		switch(count($parts))
		{
			case 5 :
			{
				$base		= $parts[0].'.'.$parts[1].'.'.$parts[2];
				$file		= $parts[3];
				$func		= $parts[4];
			} break;

			case 2 :
			{
				$file		= $parts[0];
				$func		= $parts[1];
			} break;
		}
		
		//Create the object
		$helper = KFactory::get($base.'.'.$file);
		
		if (!is_callable( array( $helper, $func ) )) {
			throw new KTemplateException( get_class($helper).'::'.$func.' not supported.' );
		}	
		
		$args = func_get_args();
		array_shift( $args );
		
		return call_user_func_array( array( $helper, $func ), $args );
	}
}