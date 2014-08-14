<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Object Identifier
 *
 * Wraps identifiers of the form type:[//domain/]package.[.path].name in an object, providing public accessors and methods for
 * derived formats.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Object
 */
class ObjectIdentifier implements ObjectIdentifierInterface
{
    /**
     * The identifier config
     *
     * @var array
     */
    private $__config = null;

    /**
     * The runtime config
     *
     * @var ObjectConfig
     */
    protected $_config = null;

    /**
     * The object identifier
     *
     * @var string
     */
    protected $_identifier = '';

    /**
     * The identifier type [com|lib]
     *
     * @var string
     */
    protected $_type = 'lib';

    /**
     * The identifier domain
     *
     * @var string
     */
    protected $_domain = '';

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
     * Constructor
     *
     * @param  string|array $identifier Identifier string or array in type://domain/package.[.path].name format
     * @param	array       $config     An optional associative array of configuration settings.
     * @throws  ObjectExceptionInvalidIdentifier If the identifier cannot be parsed
     */
    public function __construct($identifier, array $config = array())
    {
        //Get the parts
        if(!is_array($identifier))
        {
            if(false === $parts = parse_url($identifier)) {
                throw new ObjectExceptionInvalidIdentifier('Identifier cannot be parsed : '.$identifier);
            }

            // Set the type
            $this->_type = isset($parts['scheme']) ? $parts['scheme'] : 'lib';

            //Set the domain
            if(isset($parts['host'])) {
                $this->_domain = $parts['host'];
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
        }
        else
        {
            $parts = $identifier;
            foreach ($parts as $key => $value) {
                $this->{'_'.$key} = $value;
            }
        }

        //Cache the identifier to increase performance
        $this->_identifier = $this->toString();

        //The identifier config
        $this->__config = $config;
    }

    /**
     * Serialize the identifier
     *
     * @return string 	The serialised identifier
     */
    public function serialize()
    {
        $data['_type']       = $this->_type;
        $data['_domain']     = $this->_domain;
        $data['_package']    = $this->_package;
        $data['_path']       = $this->_path;
        $data['_name']       = $this->_name;
        $data['_identifier'] = $this->_identifier;
        $data['__config']    = $this->__config;

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
            $this->{$property} = $value;
        }
    }

    /**
     * Get the identifier type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Get the identifier domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->_domain;
    }

    /**
     * Get the identifier package
     *
     * @return string
     */
    public function getPackage()
    {
        return $this->_package;
    }

    /**
     * Get the identifier package
     *
     * @return array
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * Get the identifier package
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Get the config
     *
     * This function will lazy create a config object is one does not exist yet.
     *
     * @return ObjectConfig
     */
    public function getConfig()
    {
        if(!$this->_config instanceof ObjectConfig) {
            $this->_config = new ObjectConfig($this->__config);
        }

        return $this->_config;
    }

    /**
     * Get the mixin registry
     *
     * @return ObjectConfig
     */
    public function getMixins()
    {
        if(!isset($this->getConfig()->mixins)) {
            $this->getConfig()->append(array('mixins' => array()));
        }

        return $this->getConfig()->mixins;
    }

    /**
     * Get the decorators
     *
     *  @return ObjectConfig
     */
    public function getDecorators()
    {
        if(!isset($this->getConfig()->decorators)) {
            $this->getConfig()->append(array('decorators' => array()));
        }

        return $this->getConfig()->decorators;
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
            if(!empty($this->_type)) {
                $this->_identifier .= $this->_type;
            }

            if(!empty($this->_domain)) {
                $this->_identifier .= '://'.$this->_domain.'/';
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
     * Formats the identifier as an associative array
     *
     * @return array
     */
    public function toArray()
    {
        $data = array(
            'domain'      => $this->_domain,
            'type'		  => $this->_type,
            'package'	  => $this->_package,
            'path'		  => $this->_path,
            'name'		  => $this->_name,
        );

        return $data;
    }

    /**
     * Implements access to virtual properties so that it appears to be a read-only public property.
     *
     * @param   string  $property The virtual property to return.
     * @return  array   The value of the virtual property.
     */
    public function __get($property)
    {
        $result = null;
        if(isset($this->{'_'.$property})) {
            $result = $this->{'_'.$property};
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
     * Allow casting of the identifier to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Prevent creating clones of this class
     */
    final private function __clone()
    {
        trigger_error("An object identifier is an immutable object and should not be cloned.", E_USER_WARNING);
    }
}