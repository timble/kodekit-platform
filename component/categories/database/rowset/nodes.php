<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Nodes Database Rowset
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Categories
 */
class DatabaseRowsetNodes extends Library\DatabaseRowsetAbstract
{
    /**
     * Adds the rows as an hierarchical tree of nodes.
     *
     * This function requires each row to contain a an enumerated 'path' array containing the node id's from root to
     * the node. If no path exists or the path is empty the row will be added to the root node.
     *
	 * @param  array  	$list   An associative array of row data to be inserted.
	 * @param  string	$status The row(s) status
	 * @return  Library\DatabaseRowsetAbstract
	 * @see __construct
     */
	public function addRow(array $list, $status = null)
    {
    	foreach($list as $k => $row)
		{
		    $options = array(
            	'data'   => $row,
                'status' => $status,
            );
		    
		    $instance = $this->getRow($options);

        	if(isset($row['path']) && !empty($row['path']))
        	{
        		$nodes   = $this;
				$node    = null;
				$parents = $row['path'];

				foreach($parents as $parent)
       			{
       				if($node) {
						$nodes = $node->getChildren();
					}
					
       				$node = $nodes->find($parent);
				}

				$node->insertChild($instance);
        	}
        	else $this->insert($instance);
		}
		
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