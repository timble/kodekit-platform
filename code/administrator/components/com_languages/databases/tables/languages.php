<?php

class ComLanguagesDatabaseTableLanguages extends KDatabaseTableAbstract
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'name'      => 'languages',
            'behaviors' => array(
            	'creatable', 'lockable', 'orderable',
                'koowa:database.behavior.sluggable' => array('columns' => array('name'))
            ),
            'filters'   => array(
                'iso_code'  => array('com://admin/languages.filter.iso'),
		    )
        ));

        parent::_initialize($config);
    }
    
    /*public function filter($data)
	{
		if (isset($data['operations']) && is_array($data['operations']))
		{
			$result = 0;
			foreach($data['operations'] as $operation) {
				$result += $operation;
			}
			$data['operations'] = $result;
		}

		$data = parent::filter($data);
		return $data;
	}*/

	/**
	 * Update the rows, and change the #__isocode_tables and nodes isocodes when necessary
	 *
	 * @param  array	An associative array of data to be updated
	 * @param  mixed	Can either be a row, an array of rows or a query object
	 * @return boolean 	True if successful otherwise returns false
	 */
	/*public function update( $data, $where = null)
	{
		$nooku = KFactory::get('admin::com.nooku.model.nooku');
 
 		// Check if the new data contains an iso_code
 		if(isset($data['iso_code']))
 		{
		    $new_iso = $data['iso_code'];
		    $primary = $nooku->getPrimaryLanguage()->iso_code;
		    settype($where, 'array');
		    
		    // Update #__isocode_tables names if necessary
		    foreach($where as $id)
		    {
		        $old_iso = $this->find($id)->get('iso_code');
		        if($new_iso != $old_iso)
		        {	        	
		            if($primary != $old_iso) { // don't rename the primary lang tables, they dont have an iso in there name
		            	$this->_renameIsoTable($old_iso, $new_iso);
		            }
		            $this->_renameIsoNodes($old_iso, $new_iso);
		        }
		    }
 		}
	   
	    
	    return parent::update($data, $where);
	}*/
	
	/**
	 * Rename all #__old_iso_tables to #__new_iso_tables
	 *
	 * @param string	Old iso code
	 * @param string	New iso code
	 */
	/*protected function _renameIsoTable($old, $new)
	{
	    $tables = KFactory::get('admin::com.nooku.model.nooku')->getTables();

	    // build the rename sql statement
	    $renames = array();
	    foreach($tables as $name => $table)
	    {
	        $renames[] = '`#__'.strtolower($old).'_'.$name.'`'
	                    .' TO '
	                    .'`#__'.strtolower($new).'_'.$name.'`';
	    }
	    
	    // Perform the renaming
	    if(count($renames))
	    {
	        $sql = 'RENAME TABLE '.implode(', ', $renames);
	        $this->_db->execute($sql);
	    }
	}*/
	
	
	/**
	 * Rename all #__nooku_nodes.iso_code
	 *
	 * @param string	Old iso code
	 * @param string	New iso code
	 */
	/*protected function _renameIsoNodes($old, $new)
	{
		$table = KFactory::get('admin::com.nooku.table.nodes');
		$table->update(
			array('iso_code' => $new),
			$this->_db->getQuery()->where('iso_code', '=', $old)
		);
	}*/

    /**
     * Drops all tables for the language, as well as all nodes
     */
    /*public function delete($wheres)
    {
        $nooku      = KFactory::get('admin::com.nooku.model.nooku');
        $nodes      = KFactory::get('admin::com.nooku.table.nodes');
        $tables     = $nooku->getTables();
        $primary    = $nooku->getPrimaryLanguage();

        foreach($wheres as $key => $where)
        {
            $iso_code = KFactory::get('admin::com.nooku.table.languages')
                            ->find($where)
                            ->get('iso_code');

            // the primary language can't be deleted
            if($primary->iso_code == $iso_code)
            {
            	unset($wheres[$key]);
                JError::raiseNotice(0, "The primary language can't be deleted");
                if(count($wheres)) {
                    continue;
                } else {
                	return true;
                }
            }

            // Delete all items for this lang from the nodes table
            $query = $this->_db->getQuery()->where('iso_code', '=', $iso_code);
            $nodes->delete($query);

            // Delete all #__isocode_table_name
            foreach ($tables as $table)
            {
                $query = 'DROP TABLE '.$this->_db->quoteName('#__'.strtolower($iso_code).'_'.$table->table_name);
                $this->_db->execute($query);
            }
        }

        // Delete the table item in nooku_tables
        return parent::delete($wheres);
    }*/
}