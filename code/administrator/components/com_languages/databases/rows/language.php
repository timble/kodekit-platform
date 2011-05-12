<?php
/**
 * @version     $Id: templates.php 1161 2011-05-11 14:52:09Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
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
 * @subpackage  Languages
 */

class ComLanguagesDatabaseRowLanguage extends KDatabaseRowAbstract
{
	protected static $_manifest_fields = array(
		'name',
		'creationdate',
		'author',
		'copyright',
		'authorEmail',
		'authorUrl',
		'version',
		'description',
		'group'
	);

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'identity_column' => 'language'
        ));

        parent::_initialize($config);
    }

	public function save()
	{
		if (isset($this->_modified['default']) && $this->default)
		{
			$params = JComponentHelper::getParams('com_languages');
			$params->set($this->client->name, $this->language);

			$table = KFactory::get('admin::com.components.database.table.components',
			             array('name' => 'components')
			         );

			$row = $table->select(array('option' => 'com_languages'), KDatabase::FETCH_ROW);
			$row->params = $params->toString();

			return $row->save();
		}

		return true;
	}

	public function __get($column)
	{
		if ($column == 'language' && empty($this->_data['language'])) {
			$this->_data['language'] = substr(basename($this->_data['manifest_file']), 0, -4);
		}
		
		if ($column == 'manifest' && empty($this->_data['manifest'])) {
			$this->_data['manifest'] = simplexml_load_file($this->_data['manifest_file']);
		}

		if (in_array($column, self::$_manifest_fields) && empty($this->_data[$column])) {
			$this->_data[$column] = $this->manifest->{$column};
		}

		return parent::__get($column);
	}
	
    public function toArray()
    {
        //Make sure all the manifest data is included
        $this->_data['manifest'] = simplexml_load_file($this->_data['manifest_file']);
        
        return $this->_data;
    }
	
	/**
     * Languages are newer new, they simply exist or don't
     *
     * @return boolean
     */
    public function isNew()
    {
        return false;
    }
}