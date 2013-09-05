<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Cache Object Registry
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 */
class ObjectRegistryCache extends ObjectRegistry
{
    /**
     * The root registry namespace
     *
     * @var string
     */
    protected $_namespace = 'nooku-registry-object';

    /**
     * Constructor
     *
     * @return ObjectRegistryCache
     * @throws \RuntimeException    If the APC PHP extension is not enabled or available
     */
    public function __construct()
    {
        if (!extension_loaded('apc')) {
            throw new \RuntimeException('Unable to use ObjectRegistryCache as APC is not enabled.');
        }
    }

	/**
     * Get the registry cache namespace
     *
     * @param string $namespace
     * @return void
     */
	public function seNamespace($namespace)
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
     * Get an item from the array by offset
     *
     * @param   int     $offset The offset
     * @return  mixed   The item from the array
     */
    public function offsetGet($offset)
    {
        if(!parent::offsetExists($offset)) {
            $result = unserialize(apc_fetch($this->_namespace.'-'.$offset));
        } else {
            $result = parent::offsetGet($offset);
        }

        return $result;
    }

    /**
     * Set an item in the array
     *
     * @param   int     $offset The offset of the item
     * @param   mixed   $value  The item's value
     * @return  object  ObjectRegistryCache
     */
    public function offsetSet($offset, $value)
    {
        apc_store($this->_namespace.'-'.$offset, serialize($value));

        parent::offsetSet($offset, $value);
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
            $result = apc_exists($this->_namespace.'-'.$offset);
        }

        return $result;
    }
}