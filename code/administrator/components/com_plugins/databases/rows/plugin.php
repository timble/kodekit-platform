<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Plugins
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Plugin Database Row Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Plugins    
 */
class ComPluginsDatabaseRowPlugin extends KDatabaseRowDefault
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
	 * This method is specialized because of the magic property "description"
	 * which reads from the plugin xml file
	 *
	 * @param   string  The key name.
	 * @return  string  The corresponding value.
	 */
	public function __get($column)
	{
	    if($column == 'manifest' && empty($this->_data['manifest'])) 
		{
            $file = JPATH_PLUGINS.'/'.$this->type.'/'.$this->name.'.xml';
		     
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
	        $file = JPATH_PLUGINS.'/'.$this->type.'/'.$this->name.'.xml';
	        $this->_data['params'] = new JParameter( $this->_data['params'], $file, 'module' );
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