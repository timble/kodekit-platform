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
 * Composable Model Entity Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Model
 */
interface ModelEntityComposable
{
    /**
     * Find an entity in the collection based on a needle
     *
     * This functions accepts either a know position or associative array of property/value pairs
     *
     * @param 	string $needle The position or the key to search for
     * @return ModelEntityInterface
     */
    public function find($needle);

    /**
     * Create an entity for this collection
     *
     * This function will either clone the entity object, or create a new instance of the entity object for each entity
     * being inserted. By default the entity will be cloned.
     *
     * @param   array   $properties The entity properties
     * @param   string  $status     The entity status
     * @return  ModelEntityComposite
     */
    public function create(array $properties = array(), $status = null);

    /**
     * Insert an entity into the collection
     *
     * The entity will be stored by it's identity_key if set or otherwise by it's object handle.
     *
     * @param  ModelEntityInterface $entity
     * @return boolean    TRUE on success FALSE on failure
     * @throws \InvalidArgumentException if the object doesn't implement ModelEntity
     */
    public function insert(ObjectHandlable $entity);

    /**
     * Removes an entity from the collection
     *
     * The entity will be removed based on it's identity_key if set or otherwise by it's object handle.
     *
     * @param  ModelEntityInterface $entity
     * @return ModelEntityComposite
     * @throws \InvalidArgumentException if the object doesn't implement ModelEntityInterface
     */
    public function remove(ObjectHandlable $entity);

    /**
     * Checks if the collection contains a specific entity
     *
     * @param   ModelEntityInterface $entity
     * @return  bool Returns TRUE if the object is in the set, FALSE otherwise
     */
    public function contains(ObjectHandlable $entity);
}