<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
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
     * Constructor
     *
     * @param ObjectConfig $config  An optional Library\ObjectConfig object with configuration options
     * @return Library\DatabaseRowsetAbstract
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_object_set->setIteratorClass('\RecursiveArrayIterator');
    }

    /**
     * Adds the rows as an hierarchical tree of nodes.
     *
     * This function requires each row to contain a an enumerated 'path' array containing the node id's from root to
     * the node. If no path exists or the path is empty the row will be added to the root node.
     *
	 * @param  array  	$list An associative array of row data to be inserted.
	 * @param  boolean	$new If TRUE, mark the row(s) as new (i.e. not in the database yet). Default TRUE
	 * @return  Library\DatabaseRowsetAbstract
	 * @see __construct
     */
	public function addRow(array $list, $new = true)
    {
    	foreach($list as $k => $row)
		{
		    $options = array(
            	'data'   => $row,
                'status' => $new ? NULL : Library\Database::STATUS_LOADED,
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
}