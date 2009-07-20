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
 * Factory Identifier
 *
 * Wraps identifiers of the form [application::]type.component.[.path].name
 * in an object, providing public accessors and methods for derived formats.
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Identifier
 */
class KFactoryIdentifierDefault extends KObject implements KFactoryIdentifierInterface
{
	/**
	 * The application name
	 *
	 * @var	string
	 */
	public $application = '';

	/**
	 * The identifier type [com|plg|lib|mod]
	 * 
	 * @var string
	 */
	public $type = '';
	
	/**
	 * The identifier component
	 * 
	 * @var string
	 */
	public $component = '';

	/**
	 * The identifier path 
	 * 	 
	 * @var array
	 */
	public $path = array();

	/**
	 * The identifier object name
	 *
	 * @var string
	 */
	public $name = '';
	
	/**
	 * The file path
	 *
	 * @var	string
	 */
	public $filepath = '';
	
	/**
	 * The file name
	 *
	 * @var	string
	 */
	public $filename = '';

	/**
	 * Constructor
	 *
	 * @param	string|object	Identifier string or object in [application::]type.component.[.path].name format
	 */
	public function __construct($identifier)
	{
		// We also accept objects
		$identifier = (string) $identifier;
		
		//Set the application name (if present)
		if(strpos($identifier, '::')) {	
			list($this->application, $identifier) = explode('::', $identifier);
		}

		//Explode the parts
		$parts = explode('.', $identifier);

		// Set the extension
		$this->type = array_shift($parts);
		
		// Set the extension
		$this->component = array_shift($parts);

		// Set the name (last part)
		if(count($parts)) {
			$this->name = array_pop($parts);
		}

		// Set the path (rest)
		if(count($parts)) {
			$this->path = $parts;
		}
	}

	/**
	 * Formats the indentifier as a [application::]type.component.[.path].name string
	 *
	 * @return string
	 */
	public function __toString()
	{
		//Create the identifier string		
		$identifier = '';
		
		if(!empty($this->application)) {
			$identifier .= $this->application.'::';
		}
		
		if(!empty($this->type)) {
			$identifier .= '.'.$this->type;
		}
		
		if(count($this->path)) {
			$identifier .= '.'.implode('.',$this->path);
		}

		if(!empty($this->name)) {
			$identifier .= '.'.$this->name;
		}

		return $identifier;
	}
}