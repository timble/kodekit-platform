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
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Factory
 * @subpackage  Identifier
 */
class KIdentifier implements KIdentifierInterface
{
    /**
     * An associative array of application paths
     * 
     * @var array
     */
    protected static $_applications = array();
    
    /**
     * Associative array of identifier adapters
     *
     * @var array
     */
    protected static $_adapters = array();
    
    /**
	 * The identifier alias map
	 *
	 * @var	array
	 */
	protected static $_identifier_map = array();
    
    /**
     * The identifier
     *
     * @var string
     */
    protected $_identifier = '';
    
    /**
     * The application name
     *
     * @var string
     */
    protected $_application = '';

    /**
     * The identifier type [com|plg|mod]
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
     * @var string
     */
    protected $_filepath = '';
    
     /**
     * The classname
     *
     * @var string
     */
    protected $_classname = '';
    
    /**
     * The base path
     *
     * @var string
     */
    public $basepath;
    
    /**
     * Constructor
     *
     * @param   string|object   Identifier string or object in [application::]type.package.[.path].name format
     * @throws  KIdentifierException if the identfier is not valid
     */
    public function __construct($identifier)
    {
        // We also accept objects to allow for auto-cloning
        $identifier = (string) $identifier;
        
        //Check if the identifier is valid
        if(strpos($identifier, ':') === FALSE) {
            throw new KIdentifierException('Wrong identifier format : '.$identifier);
        }
        
        //Get the parts
        $parts = parse_url($identifier);

        //Set the application
        if(isset($parts['host'])) { 
            $this->application = $parts['host'];
        }
         
        // Set the type
        $this->_type = $parts['scheme'];
        
        // Set the path
        $this->_path = trim($parts['path'], '/'); 
        $this->_path = explode('.', $this->_path);
        
        // Set the extension (first part)
        $this->_package = array_shift($this->_path);
        
        // Set the name (last part)
        if(count($this->_path)) {
            $this->_name = array_pop($this->_path);
        }

        //Cache the identifier to increase performance
        $this->_identifier = $identifier;
    }
    
	/**
	 * Returns an identifier string. 
	 * 
	 * Accepts various types of parameters and returns a valid identifier. Parameters can either be an 
	 * object that implements KObjectIdentifiable, or a KIdentifierInterface, or valid identifier 
	 * string. Function will also check for identifier mappings and return the mapped identifier.
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that 
	 *                  implements KIdentifierInterface or valid identifier string
	 * @return KIdentifier
	 */
	public static function identify($identifier)
	{		
		if(!is_string($identifier)) 
		{
			if($identifier instanceof KObjectIdentifiable) {
			    $identifier = $identifier->getIdentifier();
		    }   
		} 
		
		$alias = (string) $identifier;
		if(array_key_exists($alias, self::$_identifier_map)) {
			$identifier = self::$_identifier_map[$alias];
		}
		
		if(is_string($identifier)) {
		    $identifier = new KIdentifier($identifier);
		}
		
		return $identifier;
	}
	
	/**
	 * Creates an alias for an identifier
	 *
	 * @param mixed  The alias 
	 * @param mixed  The class indentifier or identifier object
	 */
	public static function map($alias, $identifier)
	{		
		$identifier = self::identify($identifier);
		
		self::$_identifier_map[$alias] = $identifier;
	}
    
    /**
	 * Register an application path
	 * 
	 * @param string	The name of the application
	 * @param string	The path of the application
	 * return void
     */
    public static function registerApplication($application, $path)
    {
        self::$_applications[$application] = $path;
    }
    
	/**
     * Add a factory adapter
     *
     * @param object    A KFactoryAdapter
     * @return void
     */
    public static function registerAdapter(KIdentifierAdapterInterface $adapter)
    {
        self::$_adapters[$adapter->getType()] = $adapter;
    }
       
    /** 
     * Implements the virtual class properties
     * 
     * This functions creates a string representation of the identifier.
     * 
     * @param   string  The virtual property to set.
     * @param   string  Set the virtual property to this value.
     */
    public function __set($property, $value)
    {
        if(isset($this->{'_'.$property})) 
        {
            //Force the path to an array
            if($property == 'path')
            {
                if(is_scalar($value)) {
                     $value = (array) $value;   
                }
            }
              
            //Set the basepath
            if($property == 'application')
            { 
               if(!isset(self::$_applications[$value])) {
                    throw new KIdentifierException('Unknow application : '.$value);  
               }
               
               $this->basepath = self::$_applications[$value];
            }
            
            //Set the properties
            $this->{'_'.$property} = $value;
                
            //Unset the properties
            $this->_identifier = '';
            $this->_classname  = '';
            $this->_filepath   = '';
        }
    }
    
    /**
     * Implements access to virtual properties by reference so that it appears to be 
     * a public property.
     * 
     * @param   string  The virtual property to return.
     * @return  array   The value of the virtual property.
     */
    public function &__get($property)
    {
        if(isset($this->{'_'.$property})) 
        { 
            if($property == 'filepath' && empty($this->_filepath)) {
                $this->_filepath = self::$_adapters[$this->_type]->findPath($this);
            }
              
            if($property == 'classname' && empty($this->_classname)) {
                $this->_classname = self::$_adapters[$this->_type]->findClass($this);
            }
            
            return $this->{'_'.$property};
        }
    }
    
    /**
     * This function checks if a virtual property is set.
     * 
     * @param   string  The virtual property to return.
     * @return  boolean True if it exists otherwise false.
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
            if(!empty($this->_type)) {
                $this->_identifier .= $this->_type;
            }
            
            if(!empty($this->_application)) {
                $this->_identifier .= '://'.$this->_application.'/';
            } else {
                $this->_identifier .= ':';
            }
        
            if(!empty($this->_package)) {
                $this->_identifier .= $this->_package;
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