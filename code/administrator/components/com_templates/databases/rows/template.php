<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Template Database Row Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 */
class ComTemplatesDatabaseRowTemplate extends KDatabaseRowAbstract
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
     * Whitelist for virtual keys to be lazy initiated
     *
     * @var array
     */
    protected static $_virtual_fields = array(
        'title',
        'params',
        'positions'
    );
    
    /**
     * Blacklist for hidden fields
     *
     * @var array
     */
    protected static $_hidden_fields = array(
    	'path',
    );

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'identity_column'   => 'name'
        ));

        parent::_initialize($config);
    }

    /**
     * Get a value by key
     *
     * @param   string  The key name.
     * @return  string  The corresponding value.
     */
    public function __get($column)
    {
        if($column == 'name' && empty($this->_data['name'])) {
            $this->_data['name'] = basename($this->_data['path']);
        }
   
        if($column == 'title' && empty($this->_data['title'])) {
            $this->_data['title'] = $this->manifest->name;
        }
        
        if($column == 'manifest' && empty($this->_data['manifest'])) 
		{
            $file = $this->_data['path'].'/templateDetails.xml';
            
            if(file_exists($file)) {
		        $this->_data['manifest'] = simplexml_load_file($file);
            } else {
                $this->_data['manifest'] = '';
            }
        }

		if(in_array($column, self::$_manifest_fields) && empty($this->_data[$column])) {
            $this->_data[$column] = $this->manifest->{$column};
        }
        
        if($column == 'params' && !isset($this->_data['params']))
        {
        	$file = $this->_data['path'].'/params.ini';
        	
            if(file_exists($file)) {
                $this->_data['params'] = file_get_contents($file);
            } else {
                $this->_data['params'] = '';
            }
        }

        if($column == 'positions' && !isset($this->_data['positions']))
        {
            $this->_data['positions'] = array();
            if($this->manifest && isset($this->manifest->positions))
            {
                foreach($this->manifest->positions->children() as $position) {
                    $this->_data['positions'][] = (string) $position;
                }
            }
        }

        return parent::__get($column);
    }

    /**
     * Saves to the template data, like default state, and params.
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
	public function save()
	{
		if(isset($this->_modified['default']) && $this->default)
		{
			//Update the params
		    $params = JComponentHelper::getParams('com_templates')->set($this->application, $this->name);

		    //Save the params
			$result = KFactory::get('admin::com.components.database.table.components', array('name' => 'components'))
                    ->select(array('option' => 'com_templates'), KDatabase::FETCH_ROW)
                    ->set('params', $params->toString())
			        ->save();
           

			return $result;
		}

		if(isset($this->_modified['params']))
		{
			$params = KFactory::tmp('admin::com.templates.filter.ini')->sanitize($this->params);
			if(!file_put_contents($this->path.'/params.ini', $params)) {
			    return false;
			}
		}

		return true;
	}
    
    /**
     * Templates are newer new, they simply exist or don't
     *
     * @return boolean
     */
    public function isNew()
    {
        return false;
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
        
        //Include the virtual fields
        foreach(self::$_virtual_fields as $field) 
        {   
            if(is_array($this->$field)) {
                $data[$field] = (array) $this->$field; 
            } else {
                $data[$field] = (string) $this->$field; 
            }
        }
        
        //Remove the hidden fields
        foreach(self::$_hidden_fields as $field) {
            unset($data[$field]);   
        }
          
        return $data;
    }
}
