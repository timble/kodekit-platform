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
 * Traversable Model Entity Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Model
 */
interface ModelEntityTraversable
{
    /**
     * Find an entity in the collection based on a needle
     *
     * This functions accepts either a know position or associative array of key/value pairs
     *
     * @param 	string $needle The position or the key to search for
     * @return ModelEntityInterface
     */
    public function find($needle);

    /**
     * Get an instance of a entity object for this collection
     *
     * @param   array $options An optional associative array of configuration settings.
     * @return  ModelEntityInterface
     */
    public function createEntity(array $options = array());

    /**
     * Add entities to the collection
     *
     * This function will either clone the entity object, or create a new instance of the entity object for each entity
     * being inserted. By default the entity will be cloned.
     *
     * @param  array   $properties  An associative array of entity properties to be inserted.
     * @param  string  $status  The entities(s) status
     *
     * @return  ModelEntityInterface
     * @see __construct
     */
    public function addEntity(array $properties, $status = NULL);
}