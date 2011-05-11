<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Modules Database Row Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules    
 */

class ComModulesDatabaseRowModule extends KDatabaseRowDefault
{
	/**
	 * Get a value by key
	 *
	 * This method is specialized because of the magic property "pages"
	 * which is in a 1:n relation with modules
	 *
	 * @param   string  The key name.
	 * @return  string  The corresponding value.
	 */
	public function __get($key)
	{
		if($key == 'pages' && !isset($this->_data['pages'])) 
		{
		    if(!$this->isNew()) 
		    {
		        $table = KFactory::get('admin::com.modules.database.table.menus');
				$query = $table->getDatabase()->getQuery()
								->select('menuid')
								->where('moduleid', '=', $this->id);
				$pages = $table->select($query, KDatabase::FETCH_FIELD_LIST);
				
				if(count($pages) == 1 && $pages[0] == 0) {
		            $pages = 'all';
				}
				
				if(!$pages) {
				    $pages = 'none';
				}
		    }
		    else $pages = 'all';
			    
		    $this->_data['pages'] = $pages;
		}
		
		return parent::__get($key);
	}
	
	/**
	 * Saves the row to the database.
	 *
	 * This performs an intelligent insert/update and reloads the properties
	 * with fresh data from the table on success.
	 *
	 * @return boolean	If successfull return TRUE, otherwise FALSE
	 */
	public function save()
	{
		$modified	= $this->getModified();
		$result		= parent::save();

		if(in_array('pages', $modified)) 
		{
		    $table = KFactory::get('admin::com.modules.database.table.menus');
		
		    //Clean up existing assignemnts
		    $table->select(array('moduleid' => $this->id))->delete();

		    if(is_array($this->pages)) 
		    {
                foreach($this->pages as $page)
			    {
				    $table
					    ->select(null, KDatabase::FETCH_ROW)
					    ->setData(array(
							'moduleid'	=> $this->id,
							'menuid'	=> $page
				    	))
					    ->save();
			    }

		    } 
		    elseif($this->pages == 'all') 
		    {
                $table
				    ->select(null, KDatabase::FETCH_ROW)
				    ->setData(array(
						'moduleid'	=> $this->id,
						'menuid'	=> 0
			    	))
				    ->save();
		    }
		}
													
		return $result;
    }

	/**
	 * Deletes the row form the database.
	 *
	 * Customized in order to implement cascading delete
	 *
	 * @return boolean	If successfull return TRUE, otherwise FALSE
	 */
	public function delete()
	{
		$result = parent::delete();
		
		if($this->getStatus() != KDatabase::STATUS_FAILED) 
		{	
		    KFactory::get('admin::com.modules.database.table.menus')
			    ->select(array('moduleid' => $this->id))
			    ->delete();
		}

		return $result;
	}
}