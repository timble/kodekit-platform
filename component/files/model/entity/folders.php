<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Folders Model Entity
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ModelEntityFolders extends ModelEntityNodes
{
    /**
     * Returns if an iterator can be created for the current entry.
     *
     * @return	boolean
     */
	public function hasChildren()
	{
		return current($this->_data)->hasChildren();
	}

	/**
     * Returns an iterator for the current entry.
     *
     * @return	\RecursiveIterator
     */
	public function getChildren()
	{
		return $this->current()->getChildren();
	}

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
}