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
     * @var string
     */
    public $filepath = '';
    
    /**
     * The base path
     *
     * @var string
     */
    public $basepath = '';
    
    /**
     * The classname
     *
     * @var string
     */
    public $classname = '';
    
    /**
     * Constructor
     *
     * @param   string|object   Identifier string or object in [application::]type.package.[.path].name format
     * @throws  KIndetifierException if the identfier is not valid
     */
    public function __construct($identifier)
    {
        // We also accept objects to allow for auto-cloning
        $identifier = (string) $identifier;
        
        //Check if the identifier is valid
        if(strpos($identifier, '.') === FALSE) {
            throw new KIdentifierException('Wrong identifier format : '.$identifier);
        }

        //Set the application
        if(strpos($identifier, '::')) { 
            list($this->application, $parts) = explode('::', $identifier);
        } else {
            $parts = $identifier;
        }

        $parts = explode('.', $parts);

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
        
         //Cache the identifier to increase performance
        $this->_identifier = $identifier;
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
            $this->{'_'.$property} = $value;
            
            //Set the basepath
            if($property == 'application')
            { 
               if(!isset(self::$_applications[$value])) {
                    throw new KIdentifierException('Unknow application : '.$value);  
               }
               
               $this->basepath = self::$_applications[$value];
            }
                
            //Force recreation of the identifier string
            $this->_identifier = '';
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
        if(isset($this->{'_'.$property})) {
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