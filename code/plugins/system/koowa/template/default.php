<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @copyright	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

 /**
 * Default template stream wrapper
 * 
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Template
 */
class KTemplateDefault extends KTemplateAbstract
{
   /**
     * Register the stream wrapper 
     * 
     * Function prevents from registering the wrapper twice
     */
	public static function register()
	{	
		if (!in_array('tmpl', stream_get_wrappers())) {
			stream_wrapper_register('tmpl', __CLASS__);
		}
    } 
}