<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Extensions;

use Nooku\Library;

/**
 * Extension Database Row
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class DatabaseRowExtension extends Library\DatabaseRowTable
{
	/**
     * Whitelist for keys to get from the xml manifest
     *
     * @var array
     */
    protected static $_manifest_fields = array(
    	'date',
        'author',
        'copyright',
        'version',
        'description'
    );
	
	/**
	 * Get a value by key
	 *
	 * This method is specialized because of the magic property "description"
	 * which reads from the plugin xml file
	 *
	 * @param   string  The key name.
	 * @return  string  The corresponding value.
	 */
	public function __get($column)
	{
	    if($column == 'title' && empty($this->_data['title'])) 
	    {
            if(!empty($this->manifest)) {
                $this->_data['title'] = $this->manifest->name;
            } else {
                 $this->_data['title'] = '';
            }
        }
	    
	    if($column == 'manifest' && empty($this->_data['manifest'])) 
		{
            $file = JPATH_APPLICATION.'/component/'.$this->option.'/manifest.xml';

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
            $file = JPATH_APPLICATION.'/component/'.$this->option.'/config.xml';
	        $this->_data['params'] = new \JParameter( $this->_data['params'], $file, 'component' );
        }
        
		return parent::__get($column);
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