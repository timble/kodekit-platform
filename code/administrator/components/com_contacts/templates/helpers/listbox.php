<?php
/**
 * @version     $Id: templates.php 1161 2011-05-11 14:52:09Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Listbox Template Helper Class
 *
 * @author      Isreal Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts   
 */

class ComContactsTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
	public function users($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'model' 	=> 'users',
			'name'		=> 'user_id',
			'value'		=> 'id',
			'text'		=> 'name'
		));

		return parent::_listbox($config);
	}
	
    public function category( $config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'model'		=> 'categories',
			'name' 		=> 'category',
			'value'		=> 'id',
			'text'		=> 'title',
		));

		return parent::_listbox($config);
	}
}