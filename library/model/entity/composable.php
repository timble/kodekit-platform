<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Composable Model Entity Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Model
 */
interface ModelEntityComposable extends ModelEntityInterface
{
    /**
     * Insert a new entity
     *
     * This function will either clone a entity prototype or create a new instance of the entity object for each
     * entity being inserted. By default the entity will be cloned. The entity will be stored by it's identity_key
     * if set or otherwise by it's object handle.
     *
     * @param   ModelEntityInterface|array $entity  A ModelEntityInterface object or an array of entity properties
     * @param   string  $status     The entity status
     * @return  ModelEntityComposable
     */
    public function insert($entity, $status = null);

    /**
     * Find an entity in the collection based on a needle
     *
     * This functions accepts either a know position or associative array of property/value pairs
     *
     * @param string $needle The position or the key to search for
     * @return ModelEntityInterface
     */
    public function find($needle);

    /**
     * Checks if the collection contains a specific entity
     *
     * @param   ModelEntityInterface $entity
     * @return  bool Returns TRUE if the object is in the set, FALSE otherwise
     */
    public function contains($entity);

    /**
     * Removes an entity from the collection
     *
     * The entity will be removed based on it's identity_key if set or otherwise by it's object handle.
     *
     * @param  ModelEntityInterface $entity
     * @return ModelEntityComposite
     * @throws \InvalidArgumentException if the object doesn't implement ModelEntityInterface
     */
    public function remove($entity);
}

