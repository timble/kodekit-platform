<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Folders Database Rowset Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesDatabaseRowsetFolders extends ComFilesDatabaseRowsetNodes
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
     * @return	RecursiveIterator
     */
	public function getChildren()
	{
		return $this->current()->getChildren();
	}

	/**
     * Adds the rows as an hierachical tree of nodes.
     *
     * This function requires each row to contain a an enumerated 'path' array containing the node
     * id's from root to the node. If no path exists or the path is empty the row will be added to
     * the root node.
     *
	 * @param  array  	An associative array of row data to be inserted.
	 * @param  boolean	If TRUE, mark the row(s) as new (i.e. not in the database yet). Default TRUE
	 * @return  KDatabaseRowsetAbstract
	 * @see __construct
     */
	public function addData(array $list, $new = true)
    {
    	foreach($list as $k => $row)
		{
		    //Create a row prototype and clone it this is faster then instanciating a new row
			$instance = $this->getRow()
							->setData($row)
							->setStatus($new ? NULL : KDatabase::STATUS_LOADED);

        	if(isset($row['hierarchy']) && !empty($row['hierarchy']))
        	{
        		$nodes   = $this;
				$node    = null;
				$parents = $row['hierarchy'];

				$current_path = array();
				foreach($parents as $parent)
       			{
       				$current_path[] = $parent;
       				if($node) {
						$nodes = $node->getChildren();
					}

       				$node = $nodes->find(implode('/', $current_path));

				}

				$node->insertChild($instance);
        	}
        	else $this->insert($instance);
		}

		return $this;
    }
}