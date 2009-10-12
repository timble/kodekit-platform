<?php
/**
 * Taxonomy
 * 
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class TagsModelMaps extends KModelTable
{
	public function __construct($options = array())
	{
		parent::__construct($options);
		
		// Set the state
		$this->_state
		 	->insert('tags_tag_id', 'int');
	}
	
	/**
     * Get a tag object
     *
     * @return KDatabaseRow
     */
    public function getItem()
    {
        // Get the data if it doesn't already exist
        if (!isset($this->_item))
        {
        	if($table = $this->getTable()) 
        	{
         		$query = $this->_buildQuery()->where('tbl.tags_tag_id', '=', $this->_state->tags_tag_id);
        		$this->_item = $table->fetchRow($query);
        	} 
        	else $this->_item = null;
        }

        return parent::getItem();
    }
}