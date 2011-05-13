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
    	if($column == 'ini_file' && empty($this->_data['ini_file'])) {
            $this->_data['ini_file'] = $this->_data['path'].'/params.ini';
        }

        if($column == 'ini' && !isset($this->_data['ini']))
        {
        	if(file_exists($this->ini_file)) {
                $this->_data['ini'] = file_get_contents($this->ini_file);
            }
        }

        if($column == 'manifest_file' && empty($this->_data['manifest_file'])) {
            $this->_data['manifest_file'] = $this->_data['path'].'/templateDetails.xml';
        }
		
		if($column == 'manifest' && empty($this->_data['manifest'])) {
            $this->_data['manifest'] = simplexml_load_file($this->manifest_file);
        }

		if(in_array($column, self::$_manifest_fields) && empty($this->_data[$column])) {
            $this->_data[$column] = $this->manifest->{$column};
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
			$params = JComponentHelper::getParams('com_templates');
			$params->set($this->application, $this->name);

			$table = KFactory::get('admin::com.components.database.table.components', array('name' => 'components'));

			$row = $table->select(array('option' => 'com_templates'), KDatabase::FETCH_ROW);
			$row->params = $params->toString();

			if(!$row->save()) return false;
		}

		if(isset($this->_modified['params']))
		{
			$params = KFactory::tmp('admin::com.templates.filter.ini')->sanitize($this->params);
			if(!file_put_contents($this->ini_file, $params)) return false;
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
}
