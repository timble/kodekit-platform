<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
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
 * @subpackage  Extensions    
 */

class ComExtensionsDatabaseRowModule extends KDatabaseRowDefault
{
	/**
     * Whitelist for keys to get from the xml manifest
     *
     * @var array
     */
    protected static $_manifest_fields = array(
    	'creationDate',
        'author',
        'copyright',
        'authorEmail',
        'authorUrl',
        'version',
        'description'
    );
	
	/**
	 * Get a value by key
	 *
	 * This method is specialized because of the magic property "pages"
	 * which is in a 1:n relation with modules
	 *
	 * @param   string  The key name.
	 * @return  string  The corresponding value.
	 */
	public function __get($column)
	{  
	    if($column == 'title' && !isset($this->_data['title'])) {
            $this->_data['title'] = $this->manifest->name;
        }
        
	    if($column == 'application' && empty($this->_data['application'])) 
	    {
            $client	= JApplicationHelper::getClientInfo($this->client_id);
	        $this->_data['application'] = $client->name;
        }
	    
	    if($column == 'manifest' && empty($this->_data['manifest'])) 
		{
            $client	= JApplicationHelper::getClientInfo($this->application, true);
		    $file   = $client->path.'/modules/'.$this->type.'/'.$this->type.'.xml';  
            
            if(file_exists($file)) {
		        $this->_data['manifest'] = simplexml_load_file($file);
            } else {
                $this->_data['manifest'] = '';
            }
        }

		if(in_array($column, self::$_manifest_fields) && empty($this->_data[$column])) {
            $this->_data[$column] = isset($this->manifest->{$column}) ? $this->manifest->{$column} : '';
        }
        
	    if($column == 'params' && !($this->_data['params']) instanceof JParameter)
        {
	        $client	= JApplicationHelper::getClientInfo($this->application, true);
		    $file   = $client->path.'/modules/'.$this->type.'/'.$this->type.'.xml';  
		    
	        $this->_data['params'] = new JParameter( $this->_data['params'], $file, 'module' );
        }
	     
	    if($column == 'pages' && !isset($this->_data['pages'])) 
		{
		    if(!$this->isNew()) 
		    {
		        $table = KFactory::get('com://admin/extensions.database.table.menus');
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
		
		return parent::__get($column);
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
		    $table = KFactory::get('com://admin/extensions.database.table.menus');
		
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
		    KFactory::get('com://admin/extensions.database.table.menus')
			    ->select(array('moduleid' => $this->id))
			    ->delete();
		}

		return $result;
	}
	
	/**
     * Return an associative array of the data.
     *
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
        
        //Include the manifest fields
        foreach(self::$_manifest_fields as $field) {
           $data[$field] = (string) $this->$field;
        }
        
        $data['title']        = (string) $this->title;
        $data['application']  = (string) $this->application;
        $data['params']       = $this->params->toArray();
        return $data;
    }
}