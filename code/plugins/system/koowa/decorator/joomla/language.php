<?php
/**
 * @version		$Id$
* @category		Koowa
* @package 		Koowa_Decorator
* @subpackage 	Joomla
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.koowa.org
 */

/**
 * Joomla Language Decorator
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package 	Koowa_Decorator
 * @subpackage 	Joomla
 * @uses        KPatternDecorator
 * @uses 		KFilterAscii
 */
class KDecoratorJoomlaLanguage extends KPatternDecorator
{
	/**
	 * Decorate the langauge transliterate function
	 *
	 * This method processes a string and replaces all accented UTF-8 characters by unaccented
	 * ASCII-7 "equivalents"
	 *
	 * @param	string	$string 	The string to transliterate
	 * @return	string	The transliteration of the string
	 */
	public function transliterate($string)
	{
		$filter = new KFilterAscii();
		return $filter->sanitize($string);
	}

	/**
	 * Loads a single language file and appends the results to the existing strings
	 *
	 * @param	string 	$extension 	The extension for which a language file should be loaded
	 * @param	string 	$basePath  	The basepath to use
	 * @param	string	$lang		The language to load, default null for the current language
	 * @param	boolean $reload		Flag that will force a language to be reloaded if set to true
	 * @return	boolean	True, if the file has successfully loaded.
	 */
	public function load( $extension = 'joomla', $basePath = JPATH_BASE, $lang = null, $reload = false )
	{
		if ( ! $lang ) {
			$lang = $this->getObject()->_lang;
		}
			
		$cache = KFactory::tmp('lib.joomla.cache', array('sys_language', 'output'));
		$identifier = md5($extension.$basePath.$lang);
		
		if (!$data = $cache->get($identifier)) 
		{
			$strings = $this->getObject()->_strings; //backup the strings 
			$this->getObject()->_strings = array();  //empty the strings array
			
			$this->getObject()->load($extension, $basePath, $lang, $reload);
			
			//Store the strings in the cache
		   	$cache->store(serialize($this->getObject()->_strings), $identifier);
		} 
		else $strings = array_reverse(unserialize($data));
			
		$this->getObject()->_strings = array_merge( $strings, $this->getObject()->_strings );
		return true;
	}
}