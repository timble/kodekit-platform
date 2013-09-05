<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Module Database Row
 *
 * @author  Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package Nooku\Component\Pages
 */
class DatabaseRowModule extends Library\DatabaseRowTable
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
	 * This method is specialized because of the magic property "pages" which is in a 1:n relation with modules
	 *
	 * @param   string  The key name.
	 * @return  string  The corresponding value.
	 */
	public function __get($column)
	{  
	    if($column == 'title' && empty($this->_data['title']))
	    {
            if($this->manifest instanceof \SimpleXMLElement) {
                $this->_data['title'] = $this->manifest->name;
            } else {
                 $this->_data['title'] = null;
            }
	    }

        if($column == 'identifier' && empty($this->_data['identifier']))
        {
            $name        = substr( $this->name, 4);
            $package     = substr($this->extension_name, 4);

            $this->_data['identifier'] = $this->getIdentifier('com:'.$package.'.module.'.$name.'.html');
        }

        if($column == 'attribs' && empty($this->_data['attribs'])) {
            $this->_data['attribs'] = array();
        }

        if($column == 'chrome' && empty($this->_data['chrome'])) {
            $this->_data['chrome'] = array();
        }

	    if($column == 'manifest' && empty($this->_data['manifest']))
		{
            $path = dirname($this->identifier->classpath);
            $file = $path.'/'.basename($path).'.xml';

            if(file_exists($file)) {
		        $this->_data['manifest'] = simplexml_load_file($file);
            } else {
                $this->_data['manifest'] = '';
            }
        }

		if(in_array($column, self::$_manifest_fields) && empty($this->_data[$column])) {
            $this->_data[$column] = isset($this->manifest->{$column}) ? $this->manifest->{$column} : '';
        }
        
	    if($column == 'params' && !($this->_data['params']) instanceof \JParameter)
        {
            $path = dirname($this->identifier->classpath);
            $file = $path.'/config.xml';

	        $this->_data['params'] = new \JParameter( $this->_data['params'], $file, 'module' );
        }

	    if($column == 'pages' && !isset($this->_data['pages'])) 
		{
		    if(!$this->isNew()) 
		    {
		        $table = $this->getObject('com:pages.database.table.modules_pages');
				$query = $this->getObject('lib:database.query.select')
                    ->columns('pages_page_id')
                    ->where('pages_module_id = :id')
                    ->bind(array('id' => $this->id));
                
				$pages = $table->select($query, Library\Database::FETCH_FIELD_LIST);
				
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
		    $table = $this->getObject('com:pages.database.table.modules');
		
		    //Clean up existing assignemnts
		    $table->select(array('pages_module_id' => $this->id))->delete();

		    if(is_array($this->pages)) 
		    {
                foreach($this->pages as $page)
			    {
				    $table
					    ->select(null, Library\Database::FETCH_ROW)
					    ->setData(array(
							'pages_module_id' => $this->id,
							'pages_page_id' => $page
				    	))
					    ->save();
			    }

		    } 
		    elseif($this->pages == 'all') 
		    {
                $table
				    ->select(null, Library\Database::FETCH_ROW)
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

        $data['title']  = (string) $this->title;
        $data['params'] = $this->params->toArray();
        return $data;
    }
}