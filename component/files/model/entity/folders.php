<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-files for the canonical source repository
 */

namespace Kodekit\Component\Files;

use Kodekit\Library;

/**
 * Folders Model Entity
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
class ModelEntityFolders extends ModelEntityNodes
{
    /**
     * Insert a entity in an hierarchical tree of nodes.
     *
     * This function requires each entity to contain a an enumerated 'path' array containing the node id's from root to
     * the node. If no path exists or the path is empty the entity will be added to the root node.
     *
     * @param  Library\ModelEntityInterface $entity
     * @return boolean    TRUE on success FALSE on failure
     * @throws \InvalidArgumentException if the object doesn't implement ModelEntity
     */
    public function insert($entity, $status = null)
    {
        if(isset($entity->hierarchy) && !empty($entity->hierarchy))
        {
            $nodes   = $this;
            $node    = null;
            $parents = $entity->hierarchy;

            foreach($parents as $parent)
            {
                if($node) {
                    $nodes = $node->getChildren();
                }

                $node = $nodes->find($parent);
            }

            unset($entity->hierarchy);
            $node->insertChild($entity);
        }
        else parent::insert($entity);

        return true;
    }

    /**
     * Defined by IteratorAggregate
     *
     * @return \RecursiveArrayIterator
     */
    public function getIterator()
    {
        return new \RecursiveArrayIterator($this);
    }
}