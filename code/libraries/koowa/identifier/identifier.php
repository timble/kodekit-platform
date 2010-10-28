<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Identifier
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Domain Object Identifier
 *
 * Wraps identifiers of the form [application::]type.package.[.path].name
 * in an object, providing public accessors and methods for derived formats.
 *
 * @author		Johan Janssens <johan@nooku.org>
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
	protected $_application = '';

	/**
	 * The identifier type [com|plg|lib|mod]
	 * 
	 * @var string
	 */
	protected $_type = '';
	
	/**
	 * The identifier package
	 * 
	 * @var string
	 */
	protected $_package = '';

	/**
	 * The identifier path 
	 * 	 
	 * @var array
	 */
	protected $_path = array();

	/**
	 * The identifier object name
	 *
	 * @var string
	 */
	protected $_name = '';
	
	/**
	 * The file path
	 *
	 * @var	string
	 */
	protected $_filepath = '';
	
	/**
	 * The classname
	 *
	 * @var	string
	 */
	protected $_classname = '';
	
	/**
	 * The identifier
	 *
	 * @var	string
	 */
	protected $_identifier = '';
	
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
		
		//Cache the identifier to increase performance
		$this->_identifier = $identifier;
		
		//Set the application name (if present)
		if(strpos($identifier, '::')) {	
			list($this->_application, $identifier) = explode('::', $identifier);
		}

		//Explode the parts
		$parts = explode('.', $identifier);

		// Set the extension
		$this->_type = array_shift($parts);
		
		// Set the extension
		$this->_package = array_shift($parts);

		// Set the name (last part)
		if(count($parts)) {
			$this->_name = array_pop($parts);
		}

		// Set the path (rest)
		if(count($parts)) {
			$this->_path = $parts;
		}
	}
	
	/** 
     * Implements the virtual class properties
     * 
     * This functions creates a string representation of the identifier.
     * 
     * @param 	string 	The virtual property to set.
     * @param 	string 	Set the virtual property to this value.
     */
	public function __set($property, $value)
	{
		if(isset($this->{'_'.$property})) 
		{
			$this->{'_'.$property} = $value;
			
			//Recreate the identifier
			$this->_identifier = '';
			$this->_identifier = (string) $this;
		}
	}
	
	/**
     * Implements access to virtual properties by reference so that it appears to be 
     * a public property.
     * 
     * @param 	string	The virtual property to return.
     * @return 	array 	The value of the virtual property.
     */
	public function &__get($property)
	{
		if(isset($this->{'_'.$property})) {
			return $this->{'_'.$property};
		}
	}
	
	/**
     * This function checks if a virtual property is set.
     * 
     * @param 	string	The virtual property to return.
     * @return 	boolean True if it exists otherwise false.
     */
	public function __isset($property)
    {
    	return isset($this->{'_'.$property});
    }

	/**
	 * Formats the indentifier as a [application::]type.package.[.path].name string
	 *
	 * @return string
	 */
	public function __toString()
	{
		if($this->_identifier == '')
		{
			if(!empty($this->_application)) {
				$this->_identifier .= $this->_application.'::';
			}
		
			if(!empty($this->_type)) {
				$this->_identifier .= $this->_type;
			}
		
			if(!empty($this->_package)) {
				$this->_identifier .= '.'.$this->_package;
			}
		
			if(count($this->_path)) {
				$this->_identifier .= '.'.implode('.',$this->_path);
			}

			if(!empty($this->_name)) {
				$this->_identifier .= '.'.$this->_name;
			}
		}

		return $this->_identifier;
	}
}