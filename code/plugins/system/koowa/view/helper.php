<?php
/**
 * @version		$Id:helper.php 251 2008-06-14 10:06:53Z mjaz $
 * @category	Koowa
 * @package		Koowa_View
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * View Helper Class
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_View
 * @subpackage	Helper
 * @uses   		KFactory
 */
class KViewHelper
{	
	/**
	 * List of paths with helpers
	 *
	 * @var	array
	 */
	protected static $_paths;
	
	/**
	 * Class loader method
	 *
	 * Additional arguments may be supplied and are passed to the sub-class.
	 * Additional include paths are also able to be specified for third-party use
	 *
	 * @param	string	The name of helper method to load, (prefix).(class).function
	 *                  prefix and class are optional and can be used to load custom
	 *                  html helpers.
	 * @throws KViewException
	 */
	public static function _( $type )
	{
		//Initialise variables
		$prefix = 'KViewHelper';
		$file   = '';
		$func   = $type;

		// Check to see if we need to load a helper file
		$parts = explode('.', $type);

		switch(count($parts))
		{
			case 3 :
			{
				$prefix		= ucfirst($parts[0]).'Helper';
				$file		= $parts[1];
				$func		= $parts[2];
			} break;

			case 2 :
			{
				$prefix		= 'KViewHelper';
				$file		= $parts[0];
				$func		= $parts[1];
			} break;
		}

		$className	= $prefix.ucfirst($file);

		if (!class_exists( $className ))
		{
			jimport('joomla.filesystem.path');
			if ($path = JPath::find(KViewHelper::getIncludePaths(), $file.'.php'))
			{
				require_once $path;

				if (!class_exists( $className )) {
					throw new KViewException($className.'::' .$func. ' not found in file.' );
				}
			}
			else throw new KViewException( $prefix.$file . ' not supported. File not found.' );
		}

		if (!is_callable( array( $className, $func ) )) {
			throw new KViewException( $className.'::'.$func.' not supported.' );
		}	
		
		$args = func_get_args();
		array_shift( $args );
		return call_user_func_array( array( $className, $func ), $args );
	}
	
	/**
	 * Add a directory where KViewHelper should search for helpers. You may
	 * either pass a string or an array of directories.
	 *
	 * @param	string|array	Path(s) to search
	 */
	public static function addIncludePath( $dirs = array() )
	{
		if (!isset(self::$_paths)) {
			self::$_paths = array( dirname(__FILE__).DS.'helper');
		}

		// force path to array
		settype($dirs, 'array');

		// loop through the path directories
		foreach ($dirs as $dir)
		{
			if (!empty($dir) && !in_array($dir, self::$_paths)) {
				array_unshift(self::$_paths, JPath::clean( $dir ));
			}
		}
	}
	
	/**
	 * Get a list of directories where to search for helpers
	 *
	 * @return	array
	 */
	public static function getIncludePaths()
	{
		if (!isset(self::$_paths)) {
			self::$_paths = array( dirname(__FILE__).DS.'helper');
		}
		
		return self::$_paths;
	}

	/**
	 * Write a <script></script> element
	 *
	 * @access	public
	 * @param	string 	The name of the script file
	 * @param	string 	The relative or absolute path of the script file
	 * @param	boolean If true, the mootools library will be loaded
	 */
	public static function script($filename, $path = 'media/plg_koowa/js/', $mootools = true)
	{
		// Include mootools framework
		if($mootools) {
			KViewHelper::_('behavior.mootools');
		}

		if(strpos($path, 'http') !== 0 && $path[0] != '/') {
			$path =  JURI::root(true).'/'.$path;
		};

		$document = KFactory::get('lib.joomla.document');
		$document->addScript( $path.$filename );
		return;
	}

	/**
	 * Write a <link rel="stylesheet" style="text/css" /> element
	 *
	 * @access	public
	 * @param	string 	The relative URL to use for the href attribute
	 */
	public static function stylesheet($filename, $path = 'media/plg_koowa/css/', $attribs = array())
	{
		if(strpos($path, 'http') !== 0 && $path[0] != '/') {
			$path =  JURI::root(true).'/'.$path;
		};

		$document = KFactory::get('lib.joomla.document');
		$document->addStylesheet( $path.$filename, 'text/css', null, $attribs );
		return;
	}
	
	/**
	 * Returns formated date according to current local and adds time offset
	 *
	 * @access	public
	 * @param	string	date in an US English date format
	 * @param	string	format optional format for strftime
	 * @returns	string	formated date
	 * @see		strftime
	 */
	public static function date($date, $format = null, $offset = NULL)
	{
		if ( ! $format ) {
			$format = JText::_('DATE_FORMAT_LC1');
		}

		if(is_null($offset))
		{
			$config = KFactory::get('lib.joomla.config');
			$offset = $config->getValue('config.offset');
		}
		
		$instance = KFactory::get('lib.joomla.date', array($date));
		$instance->setOffset($offset);

		return $instance->toFormat($format);
	}
}
