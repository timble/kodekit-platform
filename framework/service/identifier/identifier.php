<?php
/**
 * @package		Koowa_Service
 * @subpackage  Identifier
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Service Identifier
 *
 * Wraps identifiers of the form type://namespace/package.[.path].name in an object, providing public accessors
 * and methods for derived formats.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Service
 * @subpackage  Identifier
 */
class KServiceIdentifier implements KServiceIdentifierInterface
{
    /**
     * An associative array of namespace paths
     *
     * @var array
     */
    protected static $_namespaces = array();

    /**
     * Associative array of identifier adapters
     *
     * @var array
     */
    protected static $_locators = array();

    /**
     * The identifier
     *
     * @var string
     */
    protected $_identifier = '';

    /**
     * The namespace
     *
     * @var string
     */
    protected $_namespace = '';

    /**
     * The identifier type [com|lib]
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
    protected $_basepath = '';

    /**
     * Constructor
     *
     * @param   string   $identifier Identifier string or object in type://namespace/package.[.path].name format
     * @throws  KServiceExceptionInvalidIdentifier If the identifier is not valid
     */
    public function __construct($identifier)
    {
        //Check if the identifier is valid
        if(strpos($identifier, ':') === FALSE) {
            throw new KServiceExceptionInvalidIdentifier('Malformed identifier : '.$identifier);
        }

        //Get the parts
        if(false === $parts = parse_url($identifier)) {
            throw new KServiceExceptionInvalidIdentifier('Malformed identifier : '.$identifier);
        }

        // Set the type
        $this->type = $parts['scheme'];

        //Set the namespace
        if(isset($parts['host'])) {
            $this->namespace = $parts['host'];
        }

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
	 * Serialize the identifier
	 *
	 * @return string 	The serialised identifier
	 */
	public function serialize()
	{
        $data = array(
            'namespace  ' => $this->_namespace,
            'type'		  => $this->_type,
            'package'	  => $this->_package,
            'path'		  => $this->_path,
            'name'		  => $this->_name,
            'identifier'  => $this->_identifier,
            'basepath'    => $this->_basepath,
            'filepath'	  => $this->filepath,
            'classname'   => $this->classname,
        );

        return serialize($data);
	}

	/**
	 * Unserialize the identifier
	 *
	 * @return string 	The serialised identifier
	 */
	public function unserialize($data)
	{
	    $data = unserialize($data);

	    foreach($data as $property => $value) {
	        $this->{'_'.$property} = $value;
	    }
	}

	/**
	 * Set an namespace path
	 *
	 * @param string	The name of the namespace
	 * @param string	The path of the namespace
	 * @return void
     */
    public static function setNamespace($namespace, $path)
    {
        self::$_namespaces[$namespace] = $path;
    }

	/**
	 * Get an namespace path
	 *
	 * @param string	The name of the namespace
	 * @return string	The path of the namespace
     */
    public static function getNamespace($namespace)
    {
        return isset(self::$_namespaces[$namespace]) ? self::$_namespaces[$namespace] : null;
    }

	/**
     * Get a list of namespaces
     *
     * @return array
     */
    public static function getNamespaces()
    {
        return self::$_namespaces;
    }

	/**
     * Add a identifier adapter
     *
     * @param object    A KServiceLocator
     * @return void
     */
    public static function addLocator(KServiceLocatorInterface $locator)
    {
        self::$_locators[$locator->getType()] = $locator;
    }

	/**
     * Get the registered adapters
     *
     * @return array
     */
    public static function getLocators()
    {
        return self::$_locators;
    }

    /**
     * Implements the virtual class properties
     *
     * This functions creates a string representation of the identifier.
     *
     * @param   string  The virtual property to set.
     * @param   string  Set the virtual property to this value.
     * @throws \DomainException If the namespace or type are unknown
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
            if($property == 'namespace')
            {
               if(!isset(self::$_namespaces[$value])) {
                    throw new \DomainException('Unknow namespace : '.$value);
               }

               $this->_basepath = self::$_namespaces[$value];
            }

            //Set the type
            if($property == 'type')
            {
                //Check the type
                if(!isset(self::$_locators[$value]))  {
                    throw new \DomainException('Unknow type : '.$value);
                }
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
     * Implements access to virtual properties by reference so that it appears to be a public property.
     *
     * @param   string  The virtual property to return.
     * @return  array   The value of the virtual property.
     */
    public function &__get($property)
    {
        if(isset($this->{'_'.$property}))
        {
            if($property == 'filepath' && empty($this->_filepath)) {
                $this->_filepath = self::$_locators[$this->_type]->findPath($this);
            }

            if($property == 'classname' && empty($this->_classname)) {
                $this->_classname = self::$_locators[$this->_type]->findClass($this);
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
     * Formats the indentifier as a type://namespace/package.[.path].name string
     *
     * @return string
     */
    public function toString()
    {
        if($this->_identifier == '')
        {
            if(!empty($this->_type)) {
                $this->_identifier .= $this->_type;
            }

            if(!empty($this->_namespace)) {
                $this->_identifier .= '://'.$this->_namespace.'/';
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

    /**
     * Allow PHP casting of this object
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}