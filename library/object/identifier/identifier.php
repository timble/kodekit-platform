<?php
/**
 * @package		Koowa_Object
 * @subpackage  Identifier
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

/**
 * Object Identifier
 *
 * Wraps identifiers of the form type://package.[.path].name in an object, providing public accessors and methods for
 * derived formats.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage  Identifier
 */
class ObjectIdentifier implements ObjectIdentifierInterface
{
    /**
     * The object manager
     *
     * @var ObjectManager
     */
    private $__object_manager;

    /**
     * The identifier
     *
     * @var string
     */
    protected $_identifier = '';

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
     * Constructor
     *
     * @param   string $identifier Identifier string or object in type://namespace/package.[.path].name format
     * @param 	ObjectManagerInterface	$manager  A ObjectManagerInterface object
     * @throws  ObjectExceptionInvalidIdentifier If the identifier is not valid
     */
    public function __construct($identifier, ObjectManagerInterface $manager)
    {
        $this->__object_manager = $manager;

        //Check if the identifier is valid
        if(strpos($identifier, ':') === FALSE) {
            throw new ObjectExceptionInvalidIdentifier('Malformed identifier : '.$identifier);
        }

        //Get the parts
        if(false === $parts = parse_url($identifier)) {
            throw new ObjectExceptionInvalidIdentifier('Malformed identifier : '.$identifier);
        }

        // Set the type
        $this->type = $parts['scheme'];

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
     * Get the object locator for the identifier
     *
     * @return  ObjectLocatorInterface Return object locator or FALSE if no locator can be found.
     */
    final public function getLocator()
    {
        $locator = null;

        $locators = $this->__object_manager->getLocators();
        if(isset($locators[$this->_type])) {
            $locator = $locators[$this->_type];
        }

        return $locator;
    }

	/**
	 * Serialize the identifier
	 *
	 * @return string 	The serialised identifier
	 */
	public function serialize()
	{
        $data = array(
            'type'		  => $this->_type,
            'package'	  => $this->_package,
            'path'		  => $this->_path,
            'name'		  => $this->_name,
            'identifier'  => $this->_identifier,
            'filepath'	  => $this->filepath,
            'classname'   => $this->classname,
        );

        return serialize($data);
	}

	/**
	 * Unserialize the identifier
	 *
	 * @return string $data	The serialised identifier
	 */
	public function unserialize($data)
	{
	    $data = unserialize($data);

	    foreach($data as $property => $value) {
	        $this->{'_'.$property} = $value;
	    }
	}

    /**
     * Implements the virtual class properties
     *
     * This functions creates a string representation of the identifier.
     *
     * @param   string  $property The virtual property to set.
     * @param   string  $value    Set the virtual property to this value.
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

            //Set the type
            if($property == 'type')
            {
                $this->_type = $value;

                //Check the type
                if(!$this->getLocator())  {
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
     * @param   string  $property The virtual property to return.
     * @return  array   The value of the virtual property.
     */
    public function &__get($property)
    {
        $result = null;
        if(isset($this->{'_'.$property}))
        {
            if($property == 'filepath' && empty($this->_filepath)) {
                $this->_filepath = $this->getLocator()->findPath($this);
            }

            if($property == 'classname' && empty($this->_classname)) {
                $this->_classname = $this->getLocator()->locate($this);
            }

            $result =& $this->{'_'.$property};
        }

        return $result;
    }

    /**
     * This function checks if a virtual property is set.
     *
     * @param   string  $property The virtual property to return.
     * @return  boolean True if it exists otherwise false.
     */
    public function __isset($property)
    {
        $name = ltrim($property, '_');
        $vars = get_object_vars($this);

        return isset($vars['_'.$name]);
    }

    /**
     * Formats the identifier as a type://package.[.path].name string
     *
     * @return string
     */
    public function toString()
    {
        if($this->_identifier == '')
        {
            $this->_identifier .= $this->_type;
            $this->_identifier .= ':';

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