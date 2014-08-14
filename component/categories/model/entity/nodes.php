<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Nodes Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Categories
 */
class ModelEntityNodes extends Library\ModelEntityRowset
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
    public function insert(Library\ObjectHandlable $entity)
    {
        if(isset($entity->path) && !empty($entity->path))
        {
        	$nodes   = $this;
			$node    = null;
			$parents = $entity->path;

			foreach($parents as $parent)
       		{
       			if($node) {
					$nodes = $node->getChildren();
				}
					
       			$node = $nodes->find($parent);
			}

            unset($entity->path);
			$node->insertChild($entity);
        }
        else parent::insert($entity);

		return $this;
    }

    /**
     * Defined by IteratorAggregate
     *
     * @return \RecursiveArrayIterator
     */
    public function getIterator()
    {
        return new \RecursiveArrayIterator($this->_data);
    }
}