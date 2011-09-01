<?php
/**
 * @version     $Id: templates.php 1161 2011-05-11 14:52:09Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Language Database Row Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 */

class ComExtensionsDatabaseRowLanguage extends KDatabaseRowAbstract
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
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'identity_column' => 'name'
        ));

        parent::_initialize($config);
    }

	public function __get($column)
	{    
	    if ($column == 'name' && empty($this->_data['name'])) {
			$this->_data['name'] = basename($this->_data['path']);
		}
		
	    if($column == 'title' && empty($this->_data['title'])) {
            $this->_data['title'] = $this->manifest->name;
        }

		if ($column == 'manifest' && empty($this->_data['manifest'])) 
		{
            $file = $this->_data['path'].'/'.basename($this->_data['path']).'.xml';
           
            if(file_exists($file)) {
		        $this->_data['manifest'] = simplexml_load_file($file);
            } else {
                $this->_data['manifest'] = '';
            }
		}

		if (in_array($column, self::$_manifest_fields) && empty($this->_data[$column])) {
			$this->_data[$column] = $this->manifest->{$column};
		}

		return parent::__get($column);
	}
	
    public function save()
	{
		if (isset($this->_modified['default']) && $this->default)
		{   
		    //Update the params
		    $params = JComponentHelper::getParams('com_extensions');
		    $params->set('language_'.$this->application, $this->name);
 
		    //Save the params   
			$result = KFactory::get('com://admin/extensions.database.table.components', array('name' => 'components'))
                        ->select(array('option' => 'com_extensions'), KDatabase::FETCH_ROW) 
                        ->set('params', $params->toString())       
			            ->save();

			return $result;
		}

		return true;
	}

	/**
     * Languages are newer new
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
        
        $data['name']      = (string) $this->name;
        $data['title']     = (string) $this->title;
        unset($data['path']);
          
        return $data;
    }
}