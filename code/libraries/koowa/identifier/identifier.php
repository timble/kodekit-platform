<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Identifier
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Domain Object Identifier
 *
 * Wraps identifiers of the form [application::]type.package.[.path].name
 * in an object, providing public accessors and methods for derived formats.
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Identifier
 */
class KIdentifier implements KIdentifierInterface
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
	 * The identifier package
	 * 
	 * @var string
	 */
	public $package = '';

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
	 * The classname
	 *
	 * @var	string
	 */
	public $classname = '';
	
	/**
	 * Constructor
	 *
	 * @param	string|object	Identifier string or object in [application::]type.package.[.path].name format
	 * @throws 	KIndetifierException if the identfier is not valid
	 */
	public function __construct($identifier)
	{
		// We also accept objects to allow for auto-cloning
		$identifier = (string) $identifier;
		
		if(strpos($identifier, '.') === FALSE) {
			throw new KIdentifierException('Wrong identifier format : '.$identifier);
		}
	
		//Set the application name (if present)
		if(strpos($identifier, '::')) {	
			list($this->application, $identifier) = explode('::', $identifier);
		}

		//Explode the parts
		$parts = explode('.', $identifier);

		// Set the extension
		$this->type = array_shift($parts);
		
		// Set the extension
		$this->package = array_shift($parts);

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
	 * Formats the indentifier as a [application::]type.package.[.path].name string
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
			$identifier .= $this->type;
		}
		
		if(!empty($this->package)) {
			$identifier .= '.'.$this->package;
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