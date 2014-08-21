<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Cache Object Registry
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Object\Registry\Cache
 */
class ObjectRegistryCache extends ObjectRegistry
{
    /**
     * The root registry namespace
     *
     * @var string
     */
    protected $_namespace = 'nooku';

    /**
     * Constructor
     *
     * @return ObjectRegistryCache
     * @throws \RuntimeException    If the APC PHP extension is not enabled or available
     */
    public function __construct()
    {
        if (!static::isSupported()) {
            throw new \RuntimeException('Unable to use ObjectRegistryCache as APC is not enabled.');
        }
    }

    /**
     * Checks if the APC PHP extension is enabled
     * @return bool
     */
    public static function isSupported()
    {
        return extension_loaded('apc');
    }

	/**
     * Get the registry cache namespace
     *
     * @param string $namespace
     * @return void
     */
	public function setNamespace($namespace)
	{
	    $this->_namespace = $namespace;
	}

	/**
     * Get the registry cache namespace
     *
     * @return string
     */
	public function getNamespace()
	{
	    return $this->_namespace;
	}

    /**
     * Register a class for an identifier
     *
     * @param  ObjectIdentifier|string $identifier An ObjectIdentifier, identifier string
     * @param string                   $class      The class
     * @return ObjectRegistry
     */
    public function setClass($identifier, $class)
    {
        $identifier = (string) $identifier;

        if(parent::offsetExists($identifier))
        {
            $data = array(
                'identifier' =>  parent::offsetGet($identifier),
                'class'      =>  $class
            );

            apc_store($this->getNamespace().'-object_'.$identifier, $data);
        }

        return  parent::setClass($identifier, $class);
    }

 	/**
     * Get an item from the array by offset
     *
     * @param   int     $offset The offset
     * @return  mixed   The item from the array
     */
    public function offsetGet($offset)
    {
        if(!parent::offsetExists($offset))
        {
            if($data = apc_fetch($this->getNamespace().'-object_'.$offset))
            {
                $class      = $data['class'];
                $identifier = $data['identifier'];

                //Set the identifier
                parent::offsetSet($offset, $identifier);

                //Set the class
                $this->setClass($offset, $class);
            }
        }
        else $identifier = parent::offsetGet($offset);

        return $identifier;
    }

    /**
     * Set an item in the array
     *
     * @param   int     $offset The offset of the item
     * @param   mixed   $value  The item's value
     * @return  object  ObjectRegistryCache
     */
    public function offsetSet($offset, $identifier)
    {
        if($identifier instanceof ObjectIdentifierInterface)
        {
            $data = array(
                'identifier' =>  $identifier,
                'class'      =>  $this->getClass($identifier)
            );

            apc_store($this->getNamespace().'-object_'.$offset, $data);
        }

        parent::offsetSet($offset, $identifier);
    }

	/**
     * Check if the offset exists
     *
     * @param   int     $offset The offset
     * @return  bool
     */
    public function offsetExists($offset)
    {
        if(false === $result = parent::offsetExists($offset)) {
            $result = apc_exists($this->getNamespace().'-object_'.$offset);
        }

        return $result;
    }

    /**
     * Unset an item from the array
     *
     * @param   int     $offset
     * @return  void
     */
    public function offsetUnset($offset)
    {
        apc_delete($this->getNamespace().'-object_'.$offset);
        parent::offsetUnset($offset);
    }

    /**
     * Clears APC cache
     *
     * @return $this
     */
    public function clear()
    {
        // Clear user cache
        apc_clear_cache('user');

        return parent::clear();
    }
}