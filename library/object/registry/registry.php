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
 * Object Registry
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 */
class ObjectRegistry extends \ArrayObject implements ObjectRegistryInterface
{
    /**
     * The identifier aliases
     *
     * @var  array
     */
    protected $_aliases = array();

    /**
     * Get a an object from the registry
     *
     * @param  ObjectIdentifier $identifier
     * @return  ObjectInterface   The object or NULL if the identifier could not be found
     */
    public function get(ObjectIdentifier $identifier)
    {
        $identifier = (string) $identifier;

        if($this->offsetExists($identifier)) {
            $result = $this->offsetGet($identifier);
        } else {
            $result = null;
        }

        return $result;
    }

    /**
     * Set an object in the registry
     *
     * @param  ObjectIdentifier $identifier
     * @param  mixed            $data
     * @return ObjectRegistry
     */
    public function set(ObjectIdentifier $identifier, $data = null)
    {
        if($data == null) {
            $data = $identifier;
        }

        $this->offsetSet((string) $identifier, $data);
        return $this;
    }

    /**
     * Check if an object exists in the registry
     *
     * @param  ObjectIdentifier $identifier
     * @return  boolean
     */
    public function has(ObjectIdentifier $identifier)
    {
        return $this->offsetExists((string) $identifier);
    }

    /**
     * Remove an object from the registry
     *
     * @param  ObjectIdentifier $identifier
     * @return  ObjectRegistry
     */
    public function remove(ObjectIdentifier $identifier)
    {
        $this->offsetUnset((string) $identifier);
        return $this;
    }

    /**
     * Clears out all objects from the registry
     *
     * @return  ObjectRegistry
     */
    public function clear()
    {
        $this->exchangeArray(array());
        return $this;
    }

    /**
     * Try to find an object based on an identifier string
     *
     * @param   mixed  $identifier
     * @return  ObjectIdentifier  An ObjectIdentifier or NULL if the identifier does not exist.
     */
    public function find($identifier)
    {
        $identifier = (string) $identifier;

        //Resolve the real identifier in case an alias was passed
        while(array_key_exists($identifier, $this->_aliases)) {
            $identifier = $this->_aliases[$identifier];
        }

        //Find the identifier
        if($this->offsetExists($identifier))
        {
            $result = $this->offsetGet($identifier);

            if($result instanceof ObjectInterface) {
                $result = $result->getIdentifier();
            }
        }
        else  $result = null;

        return $result;
    }

    /**
     * Register an alias for an identifier
     *
     * @param mixed $alias      The alias
     * @param ObjectIdentifier  $identifier
     * @return ObjectRegistry
     */
    public function alias($alias, ObjectIdentifier $identifier)
    {
        $alias = trim((string) $alias);
        $this->_aliases[$alias] = (string) $identifier;

        return $this;
    }

    /**
     * Get a list of all the identifier aliases
     *
     * @return array
     */
    public function getAliases()
    {
        return $this->_aliases;
    }

    /**
     * Get a list of all identifiers in the registry
     *
     * @return  array
     */
    public function getIdentifiers()
    {
        return array_keys($this->getArrayCopy());
    }
}