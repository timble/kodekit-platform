<?php
/**
 * @package		Koowa_Object
 * @subpackage  Container
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

/**
 * Object Container Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage  Container
 */
interface ObjectRegistryInterface
{
    /**
     * Get a an object from the registry
     *
     * @param  ObjectIdentifier $identifier
     * @return  ObjectInterface   The object
     */
    public function get(ObjectIdentifier $identifier);

    /**
     * Set an object in the registry
     *
     * @param  ObjectIdentifier $identifier
     * @param  mxixed           $data
     * @return ObjectRegistryInterface
     */
    public function set(ObjectIdentifier $identifier, $data = null);

    /**
     * Check if an object exists in the registry
     *
     * @param  ObjectIdentifier $identifier
     * @return  boolean
     */
    public function has(ObjectIdentifier $identifier);

    /**
     * Remove an object from the registry
     *
     * @param  ObjectIdentifier $identifier
     * @return  ObjectRegistryInterface
     */
    public function remove(ObjectIdentifier $identifier);

    /**
     * Clears out all objects from the registry
     *
     * @return  ObjectRegistryInterface
     */
    public function clear();

    /**
     * Register an alias for an identifier
     *
     * @param mixed $alias      The alias
     * @param ObjectIdentifier  $identifier
     * @return ObjectRegistry
     */
    public function alias($alias, ObjectIdentifier $identifier);

    /**
     * Get a list of all the identifier aliases
     *
     * @return array
     */
    public function getAliases();

    /**
     * Get a list of all identifiers in the registry
     *
     * @return  array  An array of objects
     */
    public function getIdentifiers();
}