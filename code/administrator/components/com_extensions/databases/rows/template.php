<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
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
 * @subpackage  Extensions
 */
class ComExtensionsDatabaseRowTemplate extends KDatabaseRowAbstract
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

        	$params = '';
            if(file_exists($file)) {
               $params  = file_get_contents($file);
            }

            $this->_data['params'] = new JParameter($params, $this->_data['path'].'/templateDetails.xml', 'template');
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
		    $params = JComponentHelper::getParams('com_extensions');
		    $params->set('template_'.$this->application, $this->name);

		    //Save the params
			$result = KFactory::get('admin::com.extensions.database.table.components')
                           ->select(array('option' => 'com_extensions'), KDatabase::FETCH_ROW)
                           ->set('params', $params->toString())
			               ->save();
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
     * Templates are newer new
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
        $data['positions'] = $this->positions;
        $data['params']    = $this->params->toArray();

        unset($data['path']);

        return $data;
    }
}
