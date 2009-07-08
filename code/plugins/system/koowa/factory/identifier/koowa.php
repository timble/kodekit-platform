<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage 	Identifier
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
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
	 * Extension [lib]
	 * @var string
	 */
	public $extension;

	/**
	 * Library name
	 * @var string
	 */
	public $library;

	/**
	 * Path array
	 * @var array
	 */
	public $path = array();

	/**
	 * Name
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

	public function __toString()
	{
		$string = $this->extension.'.'.$this->library;

		if(count($this->path)) {
			$string .= '.'.implode('.',$this->path);
		}

		$string .= '.'.$this->name;
		return $string;
	}

	public function getClassName()
	{
        $classname = 'K'.KInflector::implode($this->path).ucfirst($this->name);
		return $classname;
	}

	public function getDefaultClass()
	{
	    $classname = 'K'.KInflector::implode($this->path).'Default';
		return $classname;
	}

}