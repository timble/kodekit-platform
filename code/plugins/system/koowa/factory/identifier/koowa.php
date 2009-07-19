<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage 	Identifier
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Identifier for a Koowa class
 *
 * Wraps identifiers of the form lib.koowa.[.path].name  in an object, providing
 * public accessors and methods for derived formats
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Identifier
 */
class KFactoryIdentifierKoowa extends KObject implements KFactoryIdentifierInterface
{
	/**
	 * The extension [lib]
	 * 
	 * @var string
	 */
	public $extension;

	/**
	 * The library name
	 * 
	 * @var string
	 */
	public $library;

	/**
	 * The path array
	 * 
	 * @var array
	 */
	public $path = array();

	/**
	 * The Object name
	 * 
	 * @var string
	 */
	public $name;

	/**
	 * Constructor
	 *
	 * @param	string	Identifier lib.koowa[.path].name
	 * @return 	void
	 */
	public function __construct($identifier)
	{
		// we also accept objects
		$identifier = (string) $identifier;

		// we only deal with lib.koowa
		$parts = explode('.', $identifier);
		$this->extension 	= array_shift($parts);
		$this->library 		= array_shift($parts);

		if($this->extension == 'lib' && $this->library == 'koowa')
		{
			$this->name = array_pop($parts);
			$this->path	= $parts;
		}

	}
	
	/**
	 * Get the class name
	 *
	 * @return string
	 */
	public function getClassName()
	{
        $classname = 'K'.KInflector::implode($this->path).ucfirst($this->name);
		return $classname;
	}

	/**
	 * Get the classname for the KFooBar or KFooDefault class
	 *
	 * @return string
	 */
	public function getDefaultClassName()
	{
	    $classname = 'K'.KInflector::implode($this->path).'Default';
		return $classname;
	}
	
	/**
	 * Formats the indentifier as a string
	 *
	 * @return string
	 */
	public function __toString()
	{
		$string = $this->extension.'.'.$this->library;

		if(count($this->path)) {
			$string .= '.'.implode('.',$this->path);
		}

		$string .= '.'.$this->name;
		return $string;
	}
}