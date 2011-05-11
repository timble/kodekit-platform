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
 * @author      Ercan …zkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages   
 */

class ComLanguagesDatabaseRowLanguage extends KDatabaseRowAbstract
{
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
}